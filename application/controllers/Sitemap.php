<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';

class Sitemap extends MY_Controller
{

    const TOTAL_RECORD = 72000000;
    const RECORD_PER_FILE = 2000;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('sitemaps');
    }

    public function index()
    {
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
        } else {
            $if_modified_since = '';
        }

        $mtime = filemtime($_SERVER['SCRIPT_FILENAME']);
        $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
        if ($if_modified_since == $gmdate_mod) {
            header("HTTP/1.0 304 Not Modified");
            exit;
        }

        $header     = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n";
        $header     .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $footer     = '</sitemapindex>';
        $content    = $header."\n";

        $arrSitemap = array(
            base_url() . 'sitemap_project.xml',
            base_url() . 'sitemap_blog.xml',
            base_url() . 'sitemap_static.xml',
        );

        foreach ($arrSitemap as $link) {
            $content .= '<sitemap>';
            $content .= "\n\t<loc>" . $link . "</loc>";
            $content .= "\n\t<lastmod>" . date('c', time()) . "</lastmod>";
            $content .= "\n</sitemap>\n";
        }
        $content .= $footer;

        $numSecondsToCache = 3600; // 1 hour
        header('Content-type: application/xml; charset=utf-8');
        header("Pragma: cache");
        header("Cache-Control: max-age=$numSecondsToCache");
        header("Last-Modified: $gmdate_mod");
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $numSecondsToCache) . ' GMT');
        echo $content;
    }

    public function project()
    {
        $this->load->model('Project_model', 'ProjectModel');

        $listData = $this->ProjectModel->getSitemapData();
        if (!empty($listData)) {
            foreach ($listData as $item) {
                $loc = slugEvent($item['EventID'], $item['EventName']);
                $item = array(
                    "loc"           => $loc,
                    "lastmod"       => date("c", time()),
                    "changefreq"    => "weekly",
                    "priority"      => "0.5"
                );
                $this->sitemaps->add_item($item);
            }
        }

        header('Content-type: application/xml; charset=utf-8');
        echo $this->sitemaps->build(null, 'w', false);
    }

    public function blog()
    {
        $grabJson = grab_url_payment('https://coinschedule.com/blog/wp-json/wp/v2/posts/?per_page=10');
        $listData = json_decode($grabJson, true);

        if (!empty($listData)) {
            foreach ($listData as $item) {
                $item = array(
                    "loc"           => $item['link'],
                    "lastmod"       => date("c", time()),
                    "changefreq"    => "weekly",
                    "priority"      => "0.5"
                );
                $this->sitemaps->add_item($item);
            }
        }

        header('Content-type: application/xml; charset=utf-8');
        echo $this->sitemaps->build(null, 'w', false);
    }
}