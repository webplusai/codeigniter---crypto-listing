<?php

use Sunra\PhpSimple\HtmlDomParser;

class CSProject
{
    public $website = '';
    public $favicon = '';
    public $links   = '';

    const PATH_IMAGE_LOGO = 'public/uploads/logo/';
    const PATH_IMAGE_TEAM = 'public/uploads/team/';

    public function __construct()
    {
        $arrKeys = array('website', 'explorer', 'forum', 'announce', 'twitter', 'reddit', 'youtube',
            'facebook', 'slack', 'linkedin', 'telegram', 'instagram', 'github', 'paper', 'meetup',
            'google', 'blog', 'wikipedia', 'gitter', 'stakexchange', 'discord', 'steemit'
        );
        $this->links = array_fill_keys($arrKeys, '');
        $this->CI =& get_instance();
    }

    public function scrapeLogo($url, $isLarge = false)
    {
        $domParser = HtmlDomParser::file_get_html($url);
        $ProjImage = '';
        if (empty($domParser)) {
            return FALSE;
        }

        if ($isLarge) {
            $imageElement = $domParser->find('link[rel=apple-touch-icon]', 0);
            if (empty($imageElement)) {
                $imageElement = $domParser->find('link[rel=apple-touch-icon-precomposed]', 0);
            }
        } else {
            $imageElement = $domParser->find('link[rel=shortcut icon]', 0);
            if (empty($imageElement)) {
                $imageElement = $domParser->find('link[rel=apple-touch-icon]', 0);
                if (empty($imageElement)) {
                    $imageElement = $domParser->find('link[rel=apple-touch-icon-precomposed]', 0);
                }
            }
        }

        if (empty($imageElement)) {
            return $ProjImage;
        }

        $imageUrl = $imageElement->href;
        if (strpos($imageUrl, '//') === FALSE) {
            $imageUrl = parse_url($url, PHP_URL_SCHEME) .'://'. parse_url($url, PHP_URL_HOST) . '/' . trim($imageUrl, '/');
        }

        $filename = FCPATH . self::PATH_IMAGE_LOGO . rand(100, 999).time().'.jpg';
        $curlDownloadFile = $this->curlDownloadFile($imageUrl, $filename);

        if ($curlDownloadFile && file_exists($filename)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileinfo = finfo_file($finfo, $filename);
            if (strpos($fileinfo, 'image') !== FALSE) {

                if ($isLarge) {
                    $w = 48;
                    $h = 48;
                } else {
                    $w = 16;
                    $h = 16;
                }
                $config['image_library'] = 'gd2';
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;

                // 48x48
                $this->CI->load->library('image_lib');
                $config['source_image'] = $filename;
                $config['width']        = $w;
                $config['height']       = $h;
                $config['new_image']    = pathinfo($filename, PATHINFO_DIRNAME).'/'.pathinfo($filename, PATHINFO_FILENAME).$w.'x'.$h.'.'.pathinfo($filename, PATHINFO_EXTENSION);
                $this->CI->image_lib->initialize($config);
                $this->CI->image_lib->resize();
                if (file_exists($config['new_image'])) {
                    $ProjImage = base64_encode(file_get_contents($config['new_image']));
                }
            }
        }

        return $ProjImage;
    }

    public function scrapeCoinscheduleSearch($url)
    {
        $domParser = HtmlDomParser::file_get_html($url);
        if (empty($domParser)) {
            return FALSE;
        }

        $arrResult = array();
        foreach ($domParser->find('._NId .g') as $element) {
            $aElement = $element->find('h3.r a', 0);
            $nameElement = $element->find('.slp span', 0);
            $dateElement = $element->find('.slp span', 2);

            $arrTmp = array();
            $arrTmp['headline'] = $aElement->plaintext;
            $arrTmp['link'] = $aElement->href;
            $arrTmp['name'] = $nameElement->plaintext;
            $arrTmp['date'] = $dateElement->plaintext;
            $arrResult[] = $arrTmp;
        }

        return $arrResult;
    }

    public function scrapeIcoBench($url, $startPage = 1, $endPage = 5)
    {
        $domParser = HtmlDomParser::file_get_html($url);
        if (empty($domParser)) {
            return FALSE;
        }

        $arrResult = array();
        for ($i = $startPage; $i <= $endPage; $i ++) {
            $htmlUrl = 'https://icobench.com/icos?page='.$i.'&sort=raised-desc';
            $domParser = HtmlDomParser::file_get_html($htmlUrl);
            if (empty($domParser)) {
                continue;
            }

            foreach ($domParser->find('.ico_list table tr') as $element) {
                $contentElement = $element->find('.ico_data .content a.name', 0);
                if (empty($contentElement)) {
                    continue;
                }

                $raisedElement = $element->find('td', 1);
                $startElement = $element->find('td', 2);
                $endElement = $element->find('td', 3);

                $arrTmp = array();
                if (strtotime($startElement->plaintext) > 0) {
                    $arrTmp['ResStart'] = date('Y-m-d H:i:s', strtotime($startElement->plaintext));
                } else {
                    $arrTmp['ResStart'] = '';
                }

                if (strtotime($endElement->plaintext) > 0) {
                    $arrTmp['ResEnd'] = date('Y-m-d H:i:s', strtotime($endElement->plaintext));
                } else {
                    $arrTmp['ResEnd'] = '';
                }

                $arrTmp['ResName'] = trim(str_replace('Premium', '', $contentElement->plaintext));
                $arrTmp['ResTotalRaised'] = filter_var($raisedElement->plaintext, FILTER_SANITIZE_NUMBER_INT);
                $arrTmp['ResLink'] = $contentElement->href;
                if (strpos($arrTmp['ResLink'], 'http') === FALSE) {
                    $arrTmp['ResLink'] = 'https://icobench.com' . $arrTmp['ResLink'];
                }
                $arrResult[] = $arrTmp;
            }
        }

        return $arrResult;
    }

    public function scrapeWebsite()
    {
        if (empty($this->website)) {
            return FALSE;
        }

        $this->extractFavicon();
        $domParser = HtmlDomParser::file_get_html($this->website);
        if (empty($domParser)) {
            return FALSE;
        }
        foreach ($domParser->find('a') as $element) {
            if ($element->href && substr($element->href, 0, 1) != '#') {
                $this->extractLinkType($element->href, $element->plaintext);
            }
        }
    }

    private function extractFavicon()
    {
        $scrapingLink = 'https://www.google.com/s2/favicons?domain_url=' . $this->website;
        $img = grab_url($scrapingLink);
        $this->favicon = base64_encode($img);
    }

    public function extractLinkType($link, $text)
    {
        $link = preg_replace('/\/\?.+/', "", trim($link, "/"));
        $text = strtolower(preg_replace('/\s+/', ' ', trim($text)));
        $link = urldecode($link);

        $this->foundWebsite();
        $this->foundExplorer($link, $text);
        $this->foundForum($link, $text);
        $this->foundAnnounce($link, $text);
        $this->foundTwitter($link, $text);
        $this->foundReddit($link, $text);
        $this->foundYoutube($link, $text);
        $this->foundFacebook($link, $text);
        $this->foundSlack($link, $text);
        $this->foundLinkedIn($link, $text);
        $this->foundTelegram($link, $text);
        $this->foundInstagram($link, $text);
        $this->foundGithub($link, $text);
        $this->foundPaper($link, $text);
        $this->foundMeetup($link, $text);
        $this->foundGooglePlus($link, $text);
        $this->foundBlog($link, $text);
        $this->foundWikipedia($link, $text);
        $this->foundGitter($link, $text);
        $this->foundStackExchange($link, $text);
        $this->foundDiscord($link, $text);
        $this->foundSteemit($link, $text);

        foreach ($this->links as &$l) {
            if (!empty($l) && strpos($l, 'http') === FALSE) {
                $l = rtrim($this->website, "/") . '/' . ltrim($l, "/");
            }

            if (strpos($l, "..\\") !== FALSE) {
                $l = str_replace(array("..\\", "\\"), array('', '/'), $l);
                $parseUrl = parse_url($l);
                if (!empty($parseUrl)) {
                    $l = $parseUrl['scheme'] . '://' . $parseUrl['host'] . '/' . ltrim($parseUrl['path'], "/");
                }
            }
        }
    }

    private function foundWebsite()
    {
        $this->links['website'] = $this->website;
    }

    private function foundExplorer($link, $text)
    {

    }

    private function foundForum($link, $text)
    {
        if (!empty($this->links['forum'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'forum') !== FALSE || strpos($text, 'forum') !== FALSE) {
            $this->links['forum'] = $this->addInternalLink($link);
        }
    }

    private function foundAnnounce($link, $text)
    {
        if (!empty($this->links['announce'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'bitcointalk') !== FALSE || strpos($text, 'bitcointalk') !== FALSE) {
            $this->links['announce'] = $link;
        }
    }

    private function foundTwitter($link, $text)
    {
        if (!empty($this->links['twitter'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'twitter.com') !== FALSE) {
            $this->links['twitter'] = $link;
        }
    }

    private function foundReddit($link, $text)
    {
        if (!empty($this->links['reddit'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'reddit.com/r') !== FALSE) {
            $this->links['reddit'] = $link;
        }
    }

    private function foundYoutube($link, $text)
    {
        if (!empty($this->links['youtube'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'youtube.com/channel') !== FALSE) {
            $this->links['youtube'] = $link;
        }
    }

    private function foundFacebook($link, $text)
    {
        if (!empty($this->links['facebook'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'facebook.com') !== FALSE) {
            $this->links['facebook'] = $link;
        }
    }

    private function foundSlack($link, $text)
    {
        if (!empty($this->links['slack'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'slack') !== FALSE) {
            $this->links['slack'] = $link;
        }
    }

    private function foundLinkedIn($link, $text)
    {
        if (!empty($this->links['linkedin'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'linkedin.com/company') !== FALSE) {
            $this->links['linkedin'] = $link;
        }
    }

    private function foundTelegram($link, $text)
    {
        if (!empty($this->links['telegram'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 't.me') !== FALSE) {
            $this->links['telegram'] = $link;
        }
    }

    private function foundInstagram($link, $text)
    {
        if (!empty($this->links['instagram'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'instagram.com') !== FALSE) {
            $this->links['instagram'] = $link;
        }
    }

    private function foundGithub($link, $text)
    {
        if (!empty($this->links['github'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'github.com') !== FALSE) {
            $this->links['github'] = $link;
        }
    }

    private function foundPaper($link, $text)
    {
        if (!empty($this->links['paper'])) {
            return FALSE;
        }

        if ($link) {
            if (preg_match('/(white)?.*(paper)/', strtolower($link)) || preg_match('/(white)?.*(paper)/', $text)
                || strpos(strtolower($link), 'paper') !== FALSE)
            {
                $this->links['paper'] = $this->addInternalLink($link);
            }
        }
    }

    private function foundMeetup($link, $text)
    {
        if (!empty($this->links['meetup'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'meetup.com') !== FALSE) {
            $this->links['meetup'] = $link;
        }
    }

    private function foundGooglePlus($link, $text)
    {
        if (!empty($this->links['google'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'plus.google.com') !== FALSE) {
            $this->links['google'] = $link;
        }
    }

    private function foundBlog($link, $text)
    {
        if (strpos(strtolower($link), 'medium.com') !== FALSE) {
            $this->links['blog'] = $link;
        }

        if (preg_match('/(blog)/', $text)) {
            $this->links['blog'] = $this->addInternalLink($link);
        }
    }

    private function foundWikipedia($link, $text)
    {
        if (!empty($this->links['wikipedia'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'wikipedia') !== FALSE) {
            $this->links['wikipedia'] = $link;
        }
    }

    private function foundGitter($link, $text)
    {
        if (!empty($this->links['gitter'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'gitter') !== FALSE) {
            $this->links['gitter'] = $link;
        }
    }

    private function foundStackExchange($link, $text)
    {
        if (!empty($this->links['stakexchange'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'stackexchange') !== FALSE) {
            $this->links['stakexchange'] = $link;
        }
    }

    private function foundDiscord($link, $text)
    {
        if (!empty($this->links['discord'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'discordapp') !== FALSE || strpos(strtolower($link), 'discord.gg') !== FALSE || $text == 'discord') {
            $this->links['discord'] = $link;
        }
    }

    private function foundSteemit($link, $text)
    {
        if (!empty($this->links['steemit'])) {
            return FALSE;
        }

        if (strpos(strtolower($link), 'steemit.com') !== FALSE) {
            $this->links['steemit'] = $link;
        }
    }

    private function addInternalLink($link)
    {
        if (strpos($link, 'http') !== FALSE) {
            return $link;
        }

        $domainLink = parse_url($link, PHP_URL_HOST);
        if (empty($domainLink)) {
            return trim($this->website, "/") .'/'. trim($link, "/");
        }

        return $link;
    }

    public function curlDownloadFile($url, $saveto)
    {
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);

        if(file_exists($saveto)){
            unlink($saveto);
        }

        if ($raw) {
            $fp = fopen($saveto,'x');
            fwrite($fp, $raw);
            fclose($fp);
            chmod($saveto, 0777);
            return true;
        }
        return false;
    }
}