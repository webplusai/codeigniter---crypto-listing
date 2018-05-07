<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    const PATH_IMAGE_LOGO = 'public/uploads/logo/';
    const PATH_IMAGE_TEAM = 'public/uploads/team/';
    const PATH_IMAGE_PASSPORT = 'id_docs/';
    const SS_USER = 'ss_user';
    const PAGE_HOME = 6;
    const LIMIT_TEAM_MEMBER = 30;

    protected $twig;
    public $mappingPage;
    protected $requiredLoginPage;
    public $userActID = [
        'vote_up' => 1,
        'vote_down' => 2,
        'add_fav' => 3,
        'remove_fav' => 4,
        'click_link' => 5,
    ];
    protected $layoutData = array(
        'head'      => array(
            'image'          => '',
            'title'          => '',
            'keywords'       => '',
            'description'    => '',
        ),
        'script'     => array(
            'css' => '',
            'js' => '',
        ),
        'breadcrumb' => '',
        'content'    => '',
        'sidebar'    => array(),
        'footer'     => array(),
        'social_img' => '',
        'error_image'=> '',
    );

    public function __construct($isAjax = false)
    {
        parent::__construct();
        $this->loadConfig();
        $this->load->helper('common');
        $this->load->driver('cache');
        $this->load->library('user_agent');

        if (!$isAjax) {
            $this->layoutData['isFullTitle'] = false;
            $this->layoutData['isDefer'] = true;
            $this->layoutData['reviveBanner'] = false;
            $this->layoutData['isHomepage'] = false;
            $this->layoutData['isMobile'] = false;
            $this->layoutData['thisYear'] = date('Y');
            $this->layoutData['isAddThis'] = true;
            if ($this->agent->is_mobile()) {
                $this->layoutData['isMobile'] = true;
            }

            $this->mappingPage = [
                'About' => 1,
                'Terms' => 2,
                'Disclaimer' => 3,
                'PrivacyPolicy' => 4,
                'CookiePolicy' => 5,
                'Homepage' => 6,
            ];

            $this->requiredLoginPage = array(
                'alerts' => 'alerts',
                'profile' => 'profile',
                'contact' => 'contact',
                'listings' => 'listings',
                'edit' => 'listing',
                'submit_entry' => 'submit_entry',
                'submission' => 'submission_entry',
                'slack' => 'slack'
            );

            $this->setNewDesignPage();
            $this->requiredLoginPages();
            $this->setActivePage();
            $this->loadStyleFile();
            $this->loadMemberData();

            $twigEnvOptions = ['cache' => APPPATH . 'compilation_cache'];
            $templatePath = APPPATH . 'views/template_' . $this->config->item('template');
            $loader = new Twig_Loader_Filesystem($templatePath);
            $this->twig = new Twig_Environment($loader, $twigEnvOptions);
        }
    }

    public function renderJS($arrJS, $isDefer = true)
    {
        $htmlJs = '';
        foreach ($arrJS as $id => $js) {
            if ($isDefer === true) {
                $htmlJs .= '<script defer id="id_'.$id.'" src="'.$js.'?nocache='.time().'"></script>';
            } else {
                $htmlJs .= '<script id="id_'.$id.'" src="'.$js.'?nocache='.time().'"></script>';
            }
        }

        $this->layoutData['isDefer'] = $isDefer;
        $this->layoutData['script']['js'] = $htmlJs;
    }

    public function renderCSS($arrCSS, $isDefer = true)
    {
        $htmlCss = '';
        foreach ($arrCSS as $css) {
            $htmlCss .= '<link type="text/css" href="'.$css.'" rel="stylesheet">';
        }

        if ($isDefer) {
            $this->layoutData['script']['css'] = $htmlCss;
        } else {
            $this->layoutData['script']['css_no_defer'] = $htmlCss;
        }
    }

    public function renderView($view, $template = 'base.layout.twig', $isAmp = false)
    {
        if (!$this->layoutData['isFullTitle']) {
            $this->layoutData['head']['title'] = $this->layoutData['site_name'] . ' - ' . $this->layoutData['head']['title'];
        }

        $this->layoutData['render'] = $view;
        $this->footerLink();

        // header
        header('X-Frame-Options: DENY');

        echo $this->twig->render($template, $this->layoutData);
    }

    public function renderViewCurlyBraces($view, $template = 'base_curly.layout.twig')
    {
        if (!$this->layoutData['isFullTitle']) {
            $this->layoutData['head']['title'] = $this->layoutData['site_name'] . ' - ' . $this->layoutData['head']['title'];
        }

        $this->layoutData['render'] = $view;
        $this->footerLink();

        $twigEnvOptions = ['cache' => APPPATH . 'compilation_cache'];
        $templatePath = APPPATH . 'views/template_' . $this->config->item('template');
        $loader = new Twig_Loader_Filesystem($templatePath);
        $twig = new Twig_Environment($loader, $twigEnvOptions);

        $lexer = new Twig_Lexer($twig, array(
            'tag_variable'  => array('[[{', '}]]'),
        ));
        $twig->setLexer($lexer);

        // header
        header('X-Frame-Options: DENY');

        echo $twig->render($template, $this->layoutData);
    }

    public function getPageInfo($pageID)
    {
        $arrPageInfo = array(
            'PageName' => '',
            'PageTitle' => '',
            'PageKeyword' => '',
            'PageDescription' => '',
            'PageContents' => ''
        );

        $pages = $this->cache->redis->get('cachedPages');
        if (!$pages) {
            $this->load->model('Page_model', 'PageModel');
            $pages = $this->PageModel->getData();
            $this->cache->redis->save('cachedPages', $pages, CACHE_EXPIRE);
        }

        $pages = array_column($pages, NULL, 'PageID');
        if (isset($pages[$pageID])) {
            return $pages[$pageID];
        }

        $arrPageInfo['PageContents'] = escape_string($arrPageInfo['PageContents']);
        return $arrPageInfo;
    }

    public function getSetting($settingID)
    {
        $settings = $this->cache->redis->get('cachedSetting');
        if (!$settings) {
            $this->load->model('Setting_model', 'SettingModel');
            $settings = $this->SettingModel->getData();
            $this->cache->redis->save('cachedSetting', $settings, 120);
        }

        $settings = array_column($settings, 'SettingValue', 'SettingID');
        if (isset($settings[$settingID])) {
            return $settings[$settingID];
        }

        return false;
    }

    public function getLinkByProject($projectID, $linkType = false)
    {
        $cachedName = 'cachedLink_' . $projectID;
        $links = $this->cache->redis->get($cachedName);
        if (!$links) {
            $this->load->model('Link_model', 'LinkModel');
            $links = $this->LinkModel->getLinkByProject($projectID);
            $this->cache->redis->save($cachedName, $links, CACHE_EXPIRE);
        }

        if (empty($links)) {
            return false;
        }

        if ($linkType !== false) {
            $links = array_column($links, NULL, 'LinkType');
            if (isset($links[$linkType]) && !empty($links[$linkType])) {
                return $links[$linkType];
            } else {
                return false;
            }
        }

        return $links;
    }

    private function loadMemberData()
    {
        $this->layoutData['ssUser'] = false;
        $this->layoutData['isLoggedIn'] = 0;
        if ($this->session->has_userdata(self::SS_USER)) {
            $this->layoutData['isLoggedIn'] = 1;
            $this->layoutData['ssUser'] = $this->session->userdata(self::SS_USER);
            $this->layoutData['ssUsername'] = $this->layoutData['ssUser']['username'];
            $this->layoutData['ssUserInit'] = strtoupper(substr($this->layoutData['ssUser']['username'], 0, 1));
            if ($this->layoutData['ssUser']['level'] == 1) {
                // admin
                $this->output->enable_profiler(TRUE);
                error_reporting(-1);
                ini_set('display_errors', 1);
            }
        }
    }

    private function loadStyleFile()
    {
        $arrPages = array(
            'welcome_index' => 'parts/homepage.css',
            'welcome_projectDetail' => 'parts/project.css',
            'user_login' => 'parts/login.css',
            'user_forgot_password' => 'parts/login.css',
            'user_change_password_token' => 'parts/login.css',
            'user_register' => 'parts/register.css',
            'page_advertise' => 'parts/advertise.css',
            'page_icos' => 'parts/ico.css',
            'submit_submit_entry' => 'parts/submission.css',
            'submit_submission' => 'parts/submission.css',
            'page_stats' => 'parts/stats.css',
            'user_profile' => 'parts/profile.css',
            'listing_listings' => 'parts/listing.css',
            'listing_edit' => 'parts/listing.css',
        );

        $scriptJS = [];
        $action = isset($this->layoutData['ci_action']) ? $this->layoutData['ci_action'] : $this->router->fetch_method();
        $controller = isset($this->layoutData['ci_controller']) ? $this->layoutData['ci_controller'] : $this->router->fetch_class();
        $key = $controller .'_'. $action;
        $isDefer = true;


        if ($this->layoutData['newDesign']) {
            if ($key == 'welcome_projectDetail') {
                $this->layoutData['gruntStyleFileNew'] = 'parts/new_detail.css';
                $newJsCommon = base_url() . 'public/dist/new_detail-min.js';
            } else {
                $this->layoutData['gruntStyleFileNew'] = 'parts/new_full.css';
                $newJsCommon = base_url() . 'public/dist/new-common-min.js';
            }
        } else {
            // render Grunt Style
            $this->layoutData['gruntStyleFile'] = 'parts/style.css';
            if (isset($arrPages[$key]) && !empty($arrPages[$key])) {
                $this->layoutData['gruntStyleFile'] = $arrPages[$key];
            }
            $this->layoutData['gruntStyleFileNew'] = 'parts/new_layout.css';
            $newJsCommon = base_url() . 'public/dist/new-layout-min.js';
        }

        $scriptJS['common'] = base_url() . 'public/dist/common-min.js';
        $scriptJS['new_common'] = $newJsCommon;

        if ($action == 'statsMonthly') {
            $scriptJS['jquery_modal'] = base_url() . 'public/js/jquery.modal.js';
            $scriptJS['jquery_cookie'] = base_url() . 'public/js/jquery.cookie.js';
            $scriptCSS['jquery_modal'] = base_url() . 'public/css/jquery.modal.min.css';

            $isDefer = false;
            $this->renderCSS($scriptCSS, $isDefer);
        }

        $this->renderJS($scriptJS, $isDefer);
    }

    private function requiredLoginPages()
    {
        $controller = $this->router->fetch_class();
        $action = $this->router->fetch_method();

        $this->layoutData['ci_action'] = $action;
        $this->layoutData['ci_controller'] = $controller;
        if (in_array($action, array_keys($this->requiredLoginPage)) && !$this->session->has_userdata(self::SS_USER)) {
            $currentUrl = $_SERVER['QUERY_STRING'] ? current_url().'?'.$_SERVER['QUERY_STRING'] : current_url();
            redirect('/login?redirect=' . urlencode($currentUrl), 'location', 301);
            exit();
        }
    }

    private function setActivePage()
    {
        $arrActivePages = array(
            'welcome_index' => '',
            'welcome_projectDetail' => '',
            'page_about' => '',
            'submit_submit_entry' => '',
            'page_advertise' => '',
            'page_icos' => '',
            'page_stats' => '',
            'user_login' => ''
        );
        $action = isset($this->layoutData['ci_action']) ? $this->layoutData['ci_action'] : $this->router->fetch_method();
        $controller = isset($this->layoutData['ci_controller']) ? $this->layoutData['ci_controller'] : $this->router->fetch_class();
        $key = $controller .'_'. $action;
        $arrActivePages[$key] = 'active';
        $this->layoutData['activePage'] = $arrActivePages;
    }

    private function footerLink()
    {
        $this->layoutData['footer']['copyright_year'] = date('Y');
    }

    private function loadConfig()
    {
        $this->layoutData['config']         = $this->config->config;
        $this->layoutData['base_url']       = base_url();
        $this->layoutData['domain_name']    = $this->config->item('domain_name');
        $this->layoutData['site_name']      = $this->config->item('site_name');
        $this->layoutData['head']['canonical'] = current_url();
        $this->layoutData['head']['alternate'] = $this->layoutData['head']['canonical'];
        $this->layoutData['head']['image'] = base_url() . 'public/images/img/CSLogoHighRes-min.png';

        $this->layoutData['config']['icon_cslogo'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAV/QAAFf0BzXBRYQAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAASUSURBVFiFvZddbBRVFMd/Z7qWAqWr2ZnZ3dI2JbSCPkkw+AF+BE1MTDQGvyAQhUADoX4QiJjQ2G6b7AOfCgYwAcSgvumDJj4pPJDw9cCDiUZAIyCwbXcbZLtAYypzfOjuZvbuTFFSOcl9uOee8///75mZM/eiqvhHoxtfb/omagRhV0wStrM8HrPzScfdPNHkScfdHI/Z+YTtLA8UkLCd1fGYraWRdNxd4wEuW7yE1qbm+tam5volr752O/JdfuyE7awurUkxYLmqHsQwy7I+zmQH3ynNG934XFV9QVUXADOAe4tL14DzInJMRL7NZAdP+3J2eZ73toktIiv6c9nPJGE776nqFjPAJ2I78J2q9qjqU2FxBvhREekFnvc8b8M4cRslHrPvA7qA0MD/ybYDaVFVABK2sxtYe5fI9wwM5ToBrJKn6NjzX1BEBBG5Y3KAcgVKlrCdHcA6IAz5koh8qaqHo9HoEEA+n7dFZKGqLgOaQ/IU+GhgKLe+YhOmgEY3Ps/zvBP4qlMOFtlaW1vbe/HK5RtBDNMm1U2ub2joUdX3A5Y9q6ZmfmZw4OS4ApKOe1xVHzOzLcvqyGQH94fsztzEKs/z9gVs4GR/LluBHTES5wSRi8i225Gn+tL1wDcisjeTHdyfdNw2sxKq+mijG5/r7xOWEfBKAPaVurq6Tf+C/DCwUFV37v5k36SBodwm4LIZq6ov++cRY3G+mSAiB85f+mM0gNQFFgM1wOvAPCAHPNe5puOvzjUdJB33gKr2GKkL/JNyBR5ov99irL2ain8IIG8CfgR2AjuAR4CLwJOp7q6ffOK/D8Brnd3WXlMloFAoTAWm+QKxLItEInHNBAFWAgnDty/V3XXG7xCRK4BnxE0rFApTqgSUcsxZSKOJBPhuBgWGWBm0LCAajd4ACuUIEbxbHplMpiEA4CAwbPgWpfrSU/0OVZ1O9SavN0Qbyn2kvPjz2TOeiFwwmSzLetb0pbq7fgceAvqAXuAIYy/XiVRf2vEJeCZA/IVfzp27VZqYpTwGPGHsYtWstvb02d9+/dsQcR7oAUj1pe8BjgIxxlouD86aXaOqK012ETlesUFj8esAxU3D+XxvgN8vZhRYCMxJdXcNAfx59Wov0BIg4KuKeUArPqWq8wIS3+jPZT8fT4gPY6mqfhGAcbo/l33Y76v64ViWtY5iGf2mqoeSjts3o7llUhjxjOaW2qTjpoLIARWRd01n1eekqovCCFT1g5GRkaVJxz0kIkdU9VLxM21W1adV9U1gZlg+8BJj71nZKh5B2AFyHCs1mapKhpl50C0LuAPyOza/CGlpnB4dHR3d5HnexrtB7hOxJRKJpGum1E1+y/O88T6zD0WkV0RmEn7cMu2UiHQw9nesOl/A2J9XREakeCtaAXwaEFdxgGx044+r6ovAfFVtBeqLS9dF5CJQupiUX7Sw07ZlWZ2Z7OAe/7F8DbA3jNy0WW3tkcLw8BRVJRqN3jxjdEq/mSIsy9qQyQ7uKJWiPOIxe0U8Zg/HY/a2ib6cxmP21njMvp503LWht+Ni4MaJJvddUntM3z+WF1yG8JaRGQAAAABJRU5ErkJggg==';
        $this->layoutData['config']['icon_gold_badge'] = 'data:image/png;base64,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';
        $this->layoutData['config']['icon_pointer'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAZCAYAAAAMhW+1AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAWdEVYdENyZWF0aW9uIFRpbWUAMTAvMTAvMTdoaGqsAAAAp0lEQVQokd3RsQ3CMBCF4f9sxaIwBbIoQjZIRcEK9KzAAHSMwTYsQs0CIKVHKZAeDYmUgHCBaDjpmrvPvpONJLoE1sB2UBuBI3AqimLyAsxsA9zNTCGE/QCklObe+zMgQM65Blj0wHu/c86pA888SIK6rpchhGbUlJm1ZTlbUVXVzczGpwUopakALu+a/T5kwgGWA9kb/gMoBz4/VIwx5sZc+fazfgwesRpnvr4QxzQAAAAASUVORK5CYII=';
        $this->layoutData['config']['icon_milestone'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAXRJREFUeNpi/P//PwM2EBAZ2QekUoB4zobly4sYcAAmXBJAg1O4RMV4QTQDHsAIc8HVJYXTgFQmA3FgunZMfxaKARfm5/xXVFZm+P/vH16djExMDPfv3gUbYpA4JYsFJvH9+w+Gzx/eM/z6+QPM//HnP0Pn3jcM9e6iKAawsLIySMvKMNy5dQfkWoQBP378YHj/7gPD18+f4Yoz9RkYXr14jWIAGzs7w8d7D8EuABuI5ILpj58+IyIMwBZM96pegxoG6MDF0/MTl7Awr7+Ly8/khAQOkqPxHzAwizMzGZYuX/4Ln3twGmBqbMzS3tfH8O/vXwayDDh+/Piff3//MERFRLDhNQEUBtiwta3tp30HDvwH0bjUgDBOF5ibm7M0tbeDw4IsLxw9cuQPyP+xMTF4vcCyZ88enLFQV1XJUFlR+UtVRQV7VLu4gBMSPxCrA7EBlAapVklKTOStr61jsLay4gXybwDxIyC+D8SXoWwQvgAQYACQn7rpq0O1xQAAAABJRU5ErkJggg==';
        $this->layoutData['config']['icon_prjlogo'] = 'data:image/png;base64,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';
        $this->layoutData['config']['icon_missing_image'] = 'data:image/png;base64,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';
        $this->layoutData['config']['icon_date_select'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAu0lEQVQ4T62TsQ3CMBBFX04iFR07BGomoKZBrMEMQINooIIhKBiAgpUgDQOckCNbsqyzpShx6bv/7vvbrhi4qoF6RgesgVfBVQ2sgHfoSR00wAa4GpBaRI6qegM+OYDbn3vIJYKYYlfPZRBDsuISIDjZisg0tR0fr3QLbvJZVX/AKRdsDuDEez95VgjWzGAiIgcvbv1kK9iulDoI4jvwTWybkBSwA56GOLAWwBJ4lN5Br+8x+l/oNd01/wEwADIRayP0yQAAAABJRU5ErkJggg==';

        $this->layoutData['config']['icon_blog'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAA0FJREFUSIntlt9vFFUUx8+5d+783F9dtnW7tQUpEEhtiPBSgo0mEjUkaAwh8cEHE94hxkRjAol/gPBI9N0YjBofMMEHA8akxkiwMSkUMUAtbLel3ba7s7Oz8+Pew0MVTNzu0Mq+cV5mTu7M95PvvXPOGVyZ+KT550WKfOhCMHuLplo16VZIxd0AkIoZMg5cdEMdAJBprEvSD+MpIDG0jquIXHC7QCQp8lVQf9IARC09kD34PsV+VLsXVSaj2l/KX9kQKcEBM9P28CEAXMtVq+5OnW9c/1q5FZLh4wASzoBkELuVR0+bmey+48WjnzvPvw30OPqdHZCKqrfmzx8FrmuZAWvwQGrkGE8VudOXGzth9I0sXzpFMvofAAClCGWkvCXVmA/nrjZuXLC3v5IbO8GMtL3rMAqr+v0HpFodFPiH774cLt2Atr0ImcgPF14/k9r9hjk4hkyE5V/D+cmoelPkhrR0SeR3aJlS89YPuI46E3bCIWtO3iztX0vMoXF7x2u1q5/5dy6p1mp2/COzuNfZ/WawcM2b+mK9veroAABIobBlc5FbPdzKiy07jeJouHw7mPtNNubsreMoLKM46s/8qJrVtg4SACqo+zOXW7MT0fJtAhLZrVqqaPTvD8pXgsokAFpDB1EzKXDDhan/frhM2EmtApEApL/i/fHd0sX3vGtfUdQUPduyB04C092pL2O3AkDOnre0zEBbgaQ6YFz0jaT3vmNtP8RQrP581p/5CQDM/hfsbS9C5DWnvyEZc6eXp0ptK6MzAJmee+bIp/mXTvcdOWcOvyoD17t5gWTIrLy96zCQ8mcnQEUAoPfuQW5s2IFwCszM/X2fG0CE6P71VvkKAIie5wAoqt5ZG7c8U2JmesOA2C237v4CABT7rblJIpD+ivIWAYCZOdDTJH1EBADu9KKw2mzC8uWPG9Pfrv9Xgdwp8FQ/hY24XiYZEICWGxTpZ2VQD5emkUDvHWW6EflVWbsHcfDvl7ldSGgVACS9RektPgICyNVZuXp3bRUAw4XfAYGAENpU9OYmGv5zwYdZW/VNAzYQTwHJACICpbolT6ShZjA9pWLeDXnUHc3o3wdMozgAXG8ubVpfMiP7AJggbEnXD5tBAAAAAElFTkSuQmCC';
        $this->layoutData['config']['icon_telegram'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAADSklEQVRYR+1WSUyTYRB9LW3pDhRKgVKxLGpcUAIIhCJijCHxYvTkgeiBhIMSlwMSExPlqNFo4sWrxkTjhhGM4AECRi81BuICiKxSkLKUUlpoS2vmJ4VudLGAl07SQ9uZb97MvHnfx6p+2u/EfzRWFEC0A1vVgXgBB3wOCxPzNg/KbyoJOWwW9qeJoMmUYI9CCCecqG8awazFvgpiUwAo43jQqKUozhBDHBvjUfHVtyPQm9a6sGEABFw2ClViaDKlUMti/SqL2erAxcZBuAtPxABy5Hym2gKVGLwY1mpi09IytL8XcDhLuvrbtwkz7naMR86BOH4MSrZLmMQKCden2o9D83jfZ0B1kQI0Dpc1f59F49eZfwPAZrGQmyaERi3BvlQh6Lu3TZpseKTVw2Cx41J5GmRCjofL/Q8T6NIthAeAKqRKqWKq3J8tO5x412NA849ZbIuPRW1ZKkQ8to/r5ddDmF9aDg4glsNGfroIZZlSZCfxA15V/VOLTNU6o5XpUE1JigcXXMHTZjvqm4Z9zvIgIbX1ZK4M5VlS8Dm+FbhHW2wOvOieRscvI8NqGk1VgdzvaChOO2rCg09/AgNIEHBw7Vg6JF676x1Fhz35MoW5xZV2Ht+dgBN7ZQE79axrGq29hsAA6F9Sr13JAhxQipiP+9xnzHY8/qxH97iZOYh4eDovCRXZcQGT058328bwU78YHIC7B/FcnchHnlKEimwp6t4Mw2xzMC4EtLo4Gfnp4qDJHU6g9uUArMu+b5+Qhei8JgVt/UaQmJDqnStNwc5kQdDk5DBqsKKhddSvb8gAaA1zkvh4qNUzAlOUEbxyV8bOASMT589CBiDksnGjUoUrTcOoO6JEVmLg9XRPRskJREQAKPjCoVS09BhwplCOJNGKBNNc3e8Af0mut4xibM4aOQASJlU8D6VqKZOU1vBOuw7KeB6q8uUMN7xtye5A7atBONd5e4c8AjqY7vaGShWjE6T3t9t1qy8cuZiLmhIFMhI8r+I+vQW32nTrkjUsAK4xyEVc3Osc93hYuFbzVG4iju5Y04WWXgOed01vHIBQ9o6eYWcPyiHmxTBd6pm0bC0AykZ8oOt4PfK5EIU9glA6EI5PFEC0A38BZulSMPIgYboAAAAASUVORK5CYII=';
        $this->layoutData['config']['icon_slack'] = 'data:image/png;base64,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';
        $this->layoutData['config']['icon_twitter'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAADAklEQVRYR+2WS0hUURjH//c1j+vojFnoqGkqJQZqL4Mco1ooZQ+IILBFKG2i2tUiKKiNi4jATbuCXAUZlChJhWWki7CItCFLwlIzHwzivO/MfcSZcjHOnXuPIrSZb3eZM+f8vv/3ZNC1pOE/GpMByCiwngrYOKBUZBFWgKmwSpXaukkocgzCCn1xbLIyuFVtQ8tmAQSC2ERIRfuYhPsT8cR3ZTaLWieHR9N/v5ctBaBEZNHjsePgmzAWY+YQbhuDoUNZKMtidT3um5VBAKudHPb2hzCypBgDnCsTcG+3HaNLKo4MhvArYgzRXS/iRCFvKHdQ1nDts5RQ9fmckhSeFAWubLPgdo0tceG8pKFtOIJns7LuA8V2FpNHHWBMok1cIGeueyW0f5GMFThVJODxPnvSoaczMm56JXxaId8xN48ej2iabKoGXB6JomM8lnI2RQEHz2Cy2YFcS6pfQz4FXdNxvF1QMOpX0JTPo5cCwB/X4OwO6ILqVsHZUgGddckqrPw38cova3AJZgEAZqMa3L2UABcqLNjp4rA9h0V93r+aMhXZ+MCHRQV7+kN0Chwu4NHXYB7X1TA9+BFH2/sIHQDHAN4mR6JxrJe1DkfQ+TO5AaVtROSHXS4Orw6IcFLE1wwyriIRf1+appZ2H9jqYHGn1obmAh5ElbXaw6k4zrzTl5/cmRbgYoUFLSUCqrJZbNApSRogUik1L4Pw+tMPprQAhXYGI40O5K3xcQJ493sMlz5GDVkNV7K6XA7dHhFk4KzWxgIq6vpDIHPAyEx3wmyeQesWAY35PI67jYfO8kMLkob9AyF8DZjvBKYA5FLScjtqbajKMS/N6YiK5sFwYprSWBIAmQMiB1g5BmUiA89GHqeLeexw0XXEF3MySM3/jprvEbp9QGCB8+UWXK20giQhrY0HVdzwSiAlt1rTDQEBIfV/skhAQx6HcgebNPNjKvAtqGJgXsaTGRmv52XQ+5yMSJUDZM/Lt7IgYAFZg0/SYJLc1EJQAVDftoaDGYCMAn8Aj3c+kKSP9b0AAAAASUVORK5CYII=';
        $this->layoutData['config']['icon_facebook'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABiklEQVRYR2O0jpzxn2EAAeOoAygNAR4uNgZTXRkGWQl+BnY2FoZff/4yfP32i+HctWcM9x6/Ixi5FEVBpI8+Q1KQMQMnByuGRbNXn2ZYuP4c7RwQH2DEkBpmitMCmjpARJCbYfWESAZWVuaBcUCgqzZDcaINiuU/fv5hOHPlCcO3H7/B4vtP3GM4fPYBbaKgOMmWIdBFC8XwpOq1DLfuvyFoIboCshJhQ44zg4uVCtysL99+MXikzCfZcpAGshzQmOvC4GypDLfw45cfDN5pC4exA5TlhBlU5ITgPgxy02bQVhGH87//+M3QM+8wSgicv/aM4dW7rwRDhagoSAo2YUgKNiZoGLKCtLr1DNfuvCKoh2YO8MlYyPDh04+BcQCoTHBJnEvQcqJzAalRAKqE4spXU88BoMoGucIpTbZlsDVWgFvw6ctPhpiyVXD+nz9/GUBixACi0gC6QSOvHBgNgdEQGA2B0RBADwFHcyUGRRlE++Dnrz8MSzdfIKbkxVBDVlFMlk04NI06AAA0C/yBxHdz8QAAAABJRU5ErkJggg==';
        $this->layoutData['config']['icon_reddit'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAE4klEQVRYR+2WeWwVVRTGfzNv5m19rywCgrYSBKKlGCoFDCigIkWIIoJFCRbZhCKLRZA2VhRQFiUFFCwGrVYMqRJcEMFElkQwgGJRQZuwyVqgZW3f0rfMmzFzH5TWFlvSGv/h/jUz98493/3Od75zpUsp7Q3+xyHdBHCTgf+KAbl5K5RufcDQCf+0DaP8cq1Sb7gIJRm19wCUzt1B14kcKcLwXMaZtQzJ4RRBDW8Z3sw0IoeLaoBoMADnjEVYU57CqPCDLCHZHCKIXnoa36vjwGbHNf9jIscO4H15ZOMCUBKScC1bR2jTZ/hXvA66gZKYjCungNDGAvzvzhYBY7KXoyT1pCy1W+MCsI+egX3EJMrT+ogTXx3u3A3ILdvgX5IJqg3nzLeIFP2KN2tU4wJwZi1FvT+FssGdwbhmqJa7uuBa8BGSq0k0HRdL8WWOInLicAMASDLKPd1QuvbG0jERS+s45FvjQLWinzqKXlKMdnA/WuF2tD8KkdyxqMm9MXQdbc8PGH5v/arA3NSRno3SqStGhY/QtvXoJaexj3gBuU18VNWXzhMpPlpZWpK7KZa4dkjNWkRPfOYkgc9XYolvj/WRoWC3o+3dSUXuPPTS4mpAqlWB5IjBvWoTcrOWhHduRr69HZYOnaKbnj5O8Ns14rsZoCaXEnLreJES22MjK8FGDu4X7Kg9+6FfKMEzcVC0Yq6MagCsA4fjzFiA740pmD+6lq5Fbtqciry3Ca5fDZFI/fqmomB7cgyO0TPQL5/HO/1pLAlJxLzyDv6cLELfr6sdgP2ZdOxjZuKZ9DjO6fOxtLsbb/ZYtN93ix9EjdvtGGWXas9nbFMIBjGCFWJeSX4A17wPiRz5E/+KObiXf0XFqoUEv8irHYAZ0L1yA/rFc8i3tBI5EycHLHd0EIxIzhj8i2cJbVQd1pRhOF9ahOErx/NiKvqpv8S0LfV5HOMz0S+UIjdvgWfCoGrVUMMJbYOfxTFpNkYwQFlqdwiHohsNHYNjYrZ4Du/agm9OejUAMW/moXbvK75VvDeX4DefXmHNTuzaPUiKij93rjCoqqNWK3a/vxFCATzThlWulVvdhmvhJ0hNmuFbkIG298dqG6k9HsKZmYNh5jwzDf18SeW8ST2yjGfyEzW1W1s3FFS7m+AZP6B+oquUtBR9qmJK5mts/jb0c2fq3wsc42ZhGz4Bz4SBRI4fuiYYRwxYLBje8uuLMBwW/nF1WNonYFpzoCCXQP6S+jEgt44jNm8z2oF9gs6rOoiZvQIlqRfBr/MJ79qKfvYkKKoQqNqrP9ZHhwvX882fGg2kWnEtXoPSIZHysf1rmJC55LrtWNRxejbhX7bjX5ghTm2akmPKXJSEe2syYBiEC3cQ+GARkWMHRR8w694sxarV9M8fr38fkCTsI6diT5smBBVYvSxaeuEQlrYdsSQmi1JFCwtn1Pb9LJqOZLWh9huC/bkM4aiB/BwCBSuvq6U6LyRqjwdxTJ6DmRbzWhXevRWtqBC9+Fi0F0gSZi+Q4+8UdwFzvfmunzmBf/lraIXVq6X+DFRdqahY+w7C+vAQlC73idzWOrQw2m+7CW35ktCO70DT6qyiOhmogdhqQ27bUTAS7feG0Id+9hT68UMYoWCdQes0ohvaoYGLb5iBBsarnw80dpB/2+8mA38Dv5IlHxfQfX0AAAAASUVORK5CYII=';
        $this->layoutData['config']['icon_linkedin'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAAr9JREFUSIndVktPE1EUPufeebQDaWtbgoKgBhoxMVE3BqMLYwhGE3cu9C+4dWHiQhcs9B+4cuPGf2CITdwY44OEBQuUhwmkAkoptDBt53XvcTEtrWVKdQILPKt5nu9+97vfOQejT7NVV8KhBYsqHPGwsiMCO6zc9Tj6AEq7F5yhoXIEKLtCSDp4gEzKGB9OMYQ33zbmNysHDNAf0yfGhu+e7wWAOyOb917P5KtOOIBgDYaSRiZl1MDieiYdDZe9LcDcenl6ddu//pIrzeZNgJAyYHLi3ZblUvPvBACQ7lIvnoh5kmZ+7mxWHYAwbkQM1AAporC+mG55wvLkYCJSsj2VwZXBY73dGhF8zZdn182rpxLnerp1BXds8WG5OLdRDsQIFBmThvJsPHP7bA8AvF/auvFyKh5Rnt/MXD4ZB4C3C4XJhY2H1073x3T/h0+54pPs9+xiYS/PIAACDRWF1eTRODJEznhMr308nkmNDSddQZYndc4QYXQg8fj6mZWSPZs3WzCCREaQRLIuCwEQSCLpiEbRXdqyXnzOvZpe/WXa/pNLfbFbI2nOWym0Ndo+sVCoPJqczy4Wqp4sWt6D0YFujccjylAyGlFY2RGdGHSKXMn6mCuZjhCSpn6U1rZrJBKRgNIfBsB2pevVtst0hV3fOs4CjvJfA1BjbaxpmQxh9y7Qiv/AAEN1vrYA2HLcsLG+ZqAmAsFeDwIgYAi7+4l+FgS1fgRV1iCDSLqy6xi2l2RwqXBJFqqOkGQLWai4UpInaGXbThuaJ2nNtEXdJRVHLBerPV2axtnqjr23MwUVOwBEUBnjDABAEvgW0zhjCERAAI6QRIAIDFFh6NMVElwpm1O1KXYA5Cf9wzFge63jExEIov0b6tGfKv4DAE8ShZ96OgQRKClDZRi2o3cKBFDuXzjuV3AMPTi0SU0Akug3pogo2DOxr1sAAAAASUVORK5CYII=';
        $this->layoutData['config']['icon_profile'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAmdEVYdFRpdGxlAENvbnRhY3Q7Q2FyZDtJbmZvO0RldGFpbDtQZXJzb247MubyWQAAAnVJREFUOE+l0+tP0lEYB/CzsrXW5f9x8q6X/gW9MTN1KqBIKd7diGwi4AUR5WJoAorEli1kK4vSZW5JoKK2Vm8qrQAvyB302/P7mZuZW239ts+e891znp1zXvwYfWfIWZJ3inN/kceMzuUXJlcQxodBGJxkcgVDjmUMOpagnyDjAejsxBZAv9UPrfUd+h740Du6CPXwwkvGbd4/OPg3+79TmhbA+m1+ZLP7SKRymF1YxV3NOJoUFig0dryaDyKezB6TQYxqLJFFmmYU+jmw7pG3FHLUyECuskLSPIgaTpMe7cox7CUyvF7LPDSW11Cb56AyzSGZzqGtxwvWaXzDh2g8g6ERD8SNAxA16CCU6aC3PMFuLI2dPcLX1KFYCvFUFjLlMzCFbpaun8U2bdgMRyGs70dlnRZVZCMUxVY0xevQe6HQeSHXzkDeN8PfqlbhAWvt9tKbMohEk4jsJtFybxTl0l7Uy80IUw7t/OkHidKBVa2PwWSdTylksPbpGwYsbpTVdqOkWsNTDbgQWP+M79sJtKg9aFZ50Ng1jQalG1v0lNIGF5jkjhvbFKRtRhSL1SgWqXCdI+xCUVUXym714Gs4hs1IgsSxwQnH+dsVSR1glS1TfDhq8H6tucHDSkIxPn/h13F6RgLXxDawwjY35FMfUGlZQsX9ACqGAygf9qOMY/aj1OTDTY7RhxKDDzdIiWERdRNBFLZPg12VPUKH+yNuO1YhdaxBOk6V1NpXIbEHUWMj1iCqidi6AvHYCkSk2fUe3CzLF1mfF0icENRMokDC4dZOvp4koP5RT0DyhWNe+qHYeXKRXDrh8imuHMPlCwzAfwD7CTUOwfM8zXwIAAAAAElFTkSuQmCC';
        $this->layoutData['config']['icon_listing'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAXdEVYdFRpdGxlAExlZ2VuZDtDaGFydDtMaXN0sC+Q1AAAAj9JREFUOE+Nku9LU2EUxx/sh/34M6KI3kX0VwRFSFGQRKAQE5eupJDpdE1XW+psOVtZmMmKSCtjkoVLJHWaCUH4Yi+GP17l2lzWtrvt3n0757nbmErRhc895z73nM89z+URXt9s4MGzOWzF6/s3nsHgRyFEGQnm8Lcrx9BtOzn0DgZBgh2CE76W2o0IW2uxePUiYhtpxH4SMiqSKD1HZVSgaTn0DMywYKfwPJ2R1pDZgJV77Vi4fF4Ws6CqfgC27ve4ybjGYCVYoJLA3T/Fgl3C3T8tBfPV5zB76SymKyv0L8UVJBV1G7yeVXNwPf6kC7op0cgQjedH5FjMFfwoxBKyqoaOh5Ms2C06+yblSLXPV2HwLePC/dCm4iLrCiIE55msBkfvhC5weieg0khVT8JoebuKCte3YlNlTR+anKMwO/0wOyg6/FKSzmiw94yzoFzc8gTknk53fMVJ+wJO2OZlUYQEiVR2C6p8p6RV+WOloM39QY4UiaeKjZF1zon82hrla/nIzykSWDrfsWCPaO0aQ5oEK3ePI9x1DIuWI0WB3lhoLohSSNI0vB0S7BXNd0ah0J5C7Uex/OgUvjQc1otjKZyp9qDB9oZ4LblG+Xda5+3csI/ogsbbfjnSZ9MhBI0HMVVzgJr1r/Gef5f8A855/VcyK8Uk2Ceut43IA5Lgg1JSLBuokOGGAhuJjMTUMqwLjE0vxk3WV5C0DssX9XnqJEOos+hcsbzUaR6CodEXIEE5QYeBTZvZ/x9Qsyj7A9BX+tWavTlbAAAAAElFTkSuQmCC';
        $this->layoutData['config']['icon_logout'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACwklEQVQ4T22TS0hUURzGv/+5Xq/eHGfSTOlhkiEOEhS2SiolixZWBi0MKsiFVBCGEUFUzKY2FbQOSsHCIowoipJJsSKQqMi3k7bJzF720LnPufcf906awpzVWZzvd/7f951DSLGOrYFSULikjCUIWc4E0mTY+h/ILKV5x23YgIVPZ55NjVMKPd3dt7JZZC3ez8HllL8sD25CAwOQ0hQ4lgXH0BH/Oj7T2TNWnBLQWhOMFh68VOXmFJE83A7r+xhc1wUzw3UZruOybehuV8+70pSA6zsC0XBjS5WhBEkauAPr26gP8MRJiAtbN5zOZ73hVABxtToQLWtqqbQyQiT6b8P8mgTMir29pZtO94u+BQAf1nSv92jhtT11G45crLAzgkR9t2B8eb/Qwj9A9GV/mMobTgU37qo/KyQIIkHff2mHlk5/yN5elI10AaK+NsTHBwEh5llgWJ6F7sEwldYdLyreunfUEwMg+HkDuWo6VYQMrB5qwXBXFPmFeUjPTPdtOE7SwpPnQ2EqrT1cVFC5e5SEEL6SGSCCBFBFnsCmiTZokzEveTD/b8KIG86jrpEwFW8/sDR3y852+AAiAq/L1KYy6stXYHlAIfftTWifh/+Nn2zCC9GIm87j7thciMk2IhFab+af3xy9UF177ko5q0FKvLmJ+IQHmN8Cw5vgwdPR1DWeKJGjNRfbKjkrRNbrG9AmhuZuns3A0CznYedASgA1rpKiOy+3VolQHsxXrYj7Frzxkxa8pU0b7v2OoQUv0bcRiUTI7Lx9WlXVk4uLw5RDHxWJdJmZHTtB08aM7vnHj2/xkQm9YCs1NzevFUJsY+aVQohcABkEBEAkwExI6CFKGItYyZoEyT8BGJywfyRc56MkKx3U0NAgl5SUBBVFCcj+34WqqqrXRxYz+9USkcvMM5qm+dPbtq2bpjkdi8V+/wX8ZZapfj0Z0gAAAABJRU5ErkJggg==';

        $this->layoutData['config']['icon_filter_open'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAADG0lEQVR4Xo2RXWxTZRzGf6en3dqtG1sZ4vwYTMISkiU6v0mWSCTDTCcJokMu0CvJwAsTPzGIgMpQmC4a425HlC+jJH5krBubRYRlbBYNOtiYqxtT2w1WespO17Xn/Xty0mRXJj7JL8/7XrzP/5/n1dbsPbly7QNVW34Zn2lArCpRqlAEskpRmucikc6SzigEQZQNzNo2cufigu8nY4nPtdrdHdL+Rj3HBmNUlHipKPbgcmmIBmIJWREymSwOlsVw1GAklmT18gDHfriMW4mwwgdvtXcSKFlEaXEBi/x+SgptL/LhcXswbpokZk0M0/akyXTiJnseX8/h3ku4AZIC5/Y+w9h0kvHrJsNTs0ybCqXERmHZXrWkgDJfEQUejXJ/Hn8nUogAD716pGfwusgfhsjRkXnpi1ryZ1LkatKSwX9Scn5yVi5NmTIwkZTD4ajs/G5Yzo3F5fjPk7L69aM9GnDXml0n2l7avHbd0oCfM5NplLJIpeeZszFtUnMZh0wmw4bqW5hJpjjUMdA1cLBxm163Lxjv3f3k2av+mlXLlt+x4rZiH/F5Rb5bA8GRiKApeHhZgFg8y5edFzvOt2zYXrcvHGFdcxcbP+0DqKzdcTzY2jcj7UNz0nrBlOb+uOwKTckrnVH54LRhrx+X2p0H+4HKpz6GjZ+AC0DTNJ5oCUV+en9T04lvu7smpmdx67q9hYf8PC9FvjKGI9vpv1hKddWeB+sPMJa1+EypXEAuhIYPT0fO7N/UdCrYc3Jo9C/7cR7pjIfR0edAvuDZBni7sY2n6+HWJWyzFG25DXBELuTH5sYXfw3/ztZqD1cmbhCLHeHR+wsp895DuXcLZfk1PFITIGvR5LYnk7hhAJDLwS42MjefZcgAXdedAasWb+Xe8o8AWL8yTDj6GiItCx3YgINzRqWM3tBv1/C4Xbh1CF1p5T3n13E8NNKC7lroYIFc4NkDm1/46ptTwWT8GiINXLgMukDf+A7H7Ttpg6+1x/Z3818KvllXWfduT1i0pSWkqg95AzzvNK/hPO5+h5f5H7obuA+43fEFKgD+BdDWi/BunUBoAAAAAElFTkSuQmCC';
        $this->layoutData['config']['icon_filter_close'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAADIklEQVR4Xo2QbUyVdRyGr+c5zznE+5vFSCTIxVbkGmQxJ5sWk7LMtblSP7S2Gg6rrVmNaubItci5GquFfiWbojScK4eCcNISEUQoVyBsQSgiL3HwnMN5DnDO8//1H3La+tDWtV27v927dxsb9595qOyJgld+GfVtQZwCUSpRBKJKke4x8S9EWYgoBEGUFkI6hlZlJpwem/R/a5RWN0v9+5s53jNJbto95Ka4MU0DMUAcISpCJBK9q+MwOBFgaDLIurwMjv94HUuJsDoePqo/S0ZaKukpCaQmJZGWqDM5HrflJjBn4w/ZBGydQZtp/xwfP7eVo94BLICgwKX9LzE8HWR0xmZwKsS0rVBKtApHZ8G9CayITybBbZCd5GHcH0YEKHnvWHvPjMgfAZGGoUXpnHDkz6DIzaAjPbfD0j0WkoEpW67cCMrR3gnZ+8OgXBqelRNXx2RdVUO7ATy4cd/Jw2/vLCvPykji57EFlHIILywyr7W14fnIkpFIhBfX3IcvGKa+ubv16sHtu12bPm2Z9Va/0HEzqejhB/JyVt+fEs/soiLOMkAAQERAW5KfznggwjHvtea+A9veWF97ZYTymla2fd0JkF/6wYmW2k6f1PfPS22fLTVds7LvwpTsab0tn3TekT3t07L1ra+6gHzvo5l416zABDAMg+c/Pz9y8cD2ypPfn2u9MR3Ccrn0CjdWXBye1DSyj1RT9tpKqvq+fPJUYdZw0Eo4ZHuSsQBiJVu+uDBy+t0NlYbRWFe4du3mRwpylr7Ira+iaPAsxRWvgycOHEVXx+XdE+NTxvIC7rJc8lPNy2/+2vs7uwrdDNyaJe/MIYqfLYecfEjPguxVlGxYjxONVlp6Pv47AQBiPfrYkfnFKP1zYLpciOmC4lIo30EM2r5DjjRixebHiBWpcMB7/re/nvZYJpbLhMbDRBvqUErpUhPTHYehy03+DbHCjoM7K5pOtbXYMzN0F+3gcv8oSBS3SxBxuHj9Fj5Fk/HMZ+f4L1o+3JT/VE1br52YmbarruybjMSUV5VhggG+iNNUcW30Hf4HjwGPAyuXM2YuwN9eaYvP8zUR2AAAAABJRU5ErkJggg==';
        $this->layoutData['config']['icon_silver_badge'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACyklEQVQ4T3VSXUhTYRh+jvvHpRNzkjvmReoQQe2ihEyKIDQEpYvAEUQaGXXTRUhK+LcMutIu1EFtGMq6mN1oUgtTk5Y1V5YpEfkDrUXLnEMdbu47P/EdmSnMAy/n8D7v83zPed6Pwa6n7O6EWFOZjXc/ViFwInhekErgBXCcgJNHUtFjn8ZHywUmRtv5oI2zrWNiTVUu3i4FtonctgBP31QgJw0W+wd8flQdX+BM0yuxtioXbxZWJEKmRkCWhmDkJyMJncrVo8fuwWzvxfgCpxtfiqZz2fBvRLD0J4QKA0GWToHHMxFoE7XQH1Ci9+knfO27FF+g9PbzNuNhXfNRYypWQ5s4kRREJstidPYXQrKDcM/4MPf9t/mbvbYlbga0WXxzqM2Yldxclq9BEZuItLQ0vHa9h+OLHHPz/j1kOi9ZmZyc1MjlcqMgCDJCCGwTq9fK81VXjxXkSAf5/X48GZ1/VnSItFGcVjgc/tvQ0OCVBNxut0+hUBh4ngfHcVKlpKQgISEBW1tbkMvlWF5exsrKikSm+Pr6OoLB4HYYLperkuM4u8Fg0Op0OqytrWFzcxORSASiKEImk0GtVkOr1UKlUmFqagrT09NDDMP8X4fT6czmeX44IyPDSIc3NjZAHVEBWrQnCAKcTidPCGkxm833djKIJepwOLSiKPbl5eWdp1ZjDiielJSEwcHBVUJItdlsHtl3C/39/ddZlu2hGYTDYWmOnkztDwwMeNvb27N2X/89V5kCVqt1uLi4uEKhUCAajSIQCCA9PR1KpZI6gM/nK+js7JyN66Cjo0OjVqsDJpNJ4/F44PF4FgghLpZlLxcWFsLr9WJ8fLyxq6vrflyB7u7ucr1e/4KuaXFx8SHHcbdaW1tD9fX15YQQa0lJiWFsbMxlsVhK93NwhRByJxqN3mhqanLu/te6urpkhmEecBx33Gaz5cewf29TZ4yje7V9AAAAAElFTkSuQmCC';
        $this->layoutData['config']['icon_gold_badge'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC10lEQVQ4T3WSbUiTURTH/8+2ZsqmpqWgokHLiSulKIXQkiC0As0PiVKEGlZK0IuESqlZBPXBkgIljQRlEWYoJqlkoWiGaZkvSZiYTc1tuq3ppnP3ee6NTSyFdeFyL+fc/++ce87hsG7F3+5kGYkKfPhpBOUZBIE6NxUoeJ4iZocvytWf8aniJLcm+3txGI7cfMcykkLxfsKwKuRXAYLjdAB2bkOFuh9fqlJdAw4XtrPMpFB0jc87BSq3XuyVtqNSe80JOhTqh3J1H4arT7kGxBW0sbSjCmgXbZjQWXDV/wYUnjoUfb0MkTwSfnIpqusHMFpzxjUgNu91iTLYu2iP0hfWRQ2yZachCUhDx8AcRmT56B2cxsjY7K1v6sxilzVwGKMvNZUoQ7yKMlVtiA5cgGjLQeg6LyJ37CmGv+s3iB3vnamwniB3wiRKUComBCjsST9/IbI1KzgiCxBJYdc8R22X+FXKrqESkNXYhCzOBaSYNE7ASof/NPMID2QMYIyBUeaMDI8QMGICI2YQwxCEJT0YOFBCYF+chN00v1oM60skUgK1ODxHJglMA7NrQe0mUGIEhCWA8QAnASeRg/IE5v5nMPSNNIm98a8d5looBB7N0rBkpTjoBJhdB8ZbwegSmLAMcGLwxl/QtrwQ7BYURz7Enb81WKuovg4yEY8a98i8ZM7NB5Q3gTkyoDZwIhmm6+8byQJSd5fizX+7YGyQZYv848s3+UeB2Q0AXQETLAAnx0xjmSasECHrx3/DKDschkaf5s3KK8c5Nw8I1hmQ31OQbt0OTuQBXedjWLTaiIgiDLvMYKouyN1NvGzwPFDpbv5YCkNPzzjj0C1TKtLlYXFYnv+B2e63Bfvu4a5LgL7OM4HzUrbYNHMwj05WcivIVZXDMpiPBCLgie/+2MCZrq7umEeIdQmYqcJZmx7XbfPIUT1A6/q/9p+Dl9mKspUlRB1rgGrN9wfR71VTy2FMbAAAAABJRU5ErkJggg==';
        $this->layoutData['config']['icon_silver_badge_large'] = 'data:image/png;base64,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';
        $this->layoutData['config']['icon_gold_badge_large'] = 'data:image/png;base64,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';
        $this->layoutData['config']['icon_plat_badge'] = 'data:image/png;base64,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';
        $this->layoutData['config']['icon_open_window'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAABkUlEQVQ4T92Uv0/CUBDH715xk0Ha0VkTR/8Com0ZTUpiCHGR1tVFHXRicdfEgaGtW9UVJygKYlwdXPwH3ChinO07UxKQ8qMFwuQb710+777fe3cICz64YB7MDBRV4xABUv1CiJJAeO/VzEYQCwFF1dhC4mvjqvZcuxTERVU/JSKxm0O4Csg1huzSq1onIaAoGzucUYkBfwQu0DC0XTP3BmOSbKR9RjcCYRMQ3r2qWQwBJdUoAlHSc62jOF8DJT75DsOEhsR3EfFrLJATX/507eMooKjo28TJIYZax7VeREW/mBsoygUZODpsCbOtivnc9VQpnAHhd7tmXY1IjqowpRRUBHQYgtaqWs1JKvpdDjycBEzJBxmkHwcZaJ57/RRlSSxQyuxvko81FCDrVex6uNP6LSA1el9qOsnpYmJF+NjoPJhvw5VJ8jzACH3/ADjtpPRckBTjDoDXxzdF1vNEcA6MygA4MsujVhIiUJ6A5dqu9dC7H9w2KClGDhDW42b5b3XxV8+1y4P5M+/DuMcWDvwF13XrFZ3XEq0AAAAASUVORK5CYII=';
    }

    private function setNewDesignPage()
    {
        $arrNewDesignPage = array(
            'welcome_projectDetail' => TRUE,
        );
        $action = isset($this->layoutData['ci_action']) ? $this->layoutData['ci_action'] : $this->router->fetch_method();
        $controller = isset($this->layoutData['ci_controller']) ? $this->layoutData['ci_controller'] : $this->router->fetch_class();
        $key = $controller .'_'. $action;

        $this->layoutData['newDesign'] = FALSE;
        if (isset($arrNewDesignPage[$key]) && $arrNewDesignPage[$key]) {
            $this->layoutData['newDesign'] = TRUE;
        }
    }

    public function renderPageInfo($page)
    {
        $pageInfo = $this->getPageInfo($page);
        $this->layoutData['pageInfo'] = $pageInfo;
        $this->layoutData['isFullTitle'] = true;
        $this->layoutData['head']['title'] = $pageInfo['PageTitle'];
        $this->layoutData['head']['keyword'] = $pageInfo['PageKeyword'];
        $this->layoutData['head']['description'] = truncate($pageInfo['PageDescription'], 300);
    }

    public function renderFormError($fields)
    {
        $this->layoutData['formError'] = validation_errors();
        $inlineScript = '';
        foreach ($fields as $field) {
            if (form_error($field)) {
                $inlineScript .= '$("#'.$field.'").addClass("input-error");' . "\n";
            }
        }
        $this->layoutData['inlineScript'] = $inlineScript;
    }

    public function processImage($filename, $w, $h)
    {
        $config['image_library'] = 'gd2';
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;

        // 48x48
        $this->load->library('image_lib');
        $config['source_image'] = $filename;
        $config['width']        = $w;
        $config['height']       = $h;
        $config['new_image']    = pathinfo($filename, PATHINFO_DIRNAME).'/'.pathinfo($filename, PATHINFO_FILENAME).$w.'x'.$h.'.'.pathinfo($filename, PATHINFO_EXTENSION);
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        if (file_exists($config['new_image'])) {
            return base64_encode(file_get_contents($config['new_image']));
        }

        return '';
    }

    public function renderReviveBanner($id, $zoneId, $class = '')
    {
        return '<div class="bn '.$class.'"><ins data-revive-zoneid="'.$zoneId.'" data-revive-id="'.$id.'"></ins></div>';
    }

    public function render404()
    {
        $this->layoutData['head']['title'] = '404';
        $this->renderView('404.twig');
    }

    public function insertUserActivity($actID, $projectDetail)
    {
        if (!$this->session->has_userdata(self::SS_USER)) {
            return false;
        }

        $this->load->model('User_activity_model', 'UserActivityModel');
        $user = $this->session->userdata(self::SS_USER);
        $arrInsert = [
            'UAUserID' => $user['id'],
            'UAActID' => $actID,
            'UAProjID' => $projectDetail['ProjID'],
            'UAEventID' => $projectDetail['EventID'],
            'UADateTime' => date('Y-m-d H:i:s')
        ];
        $this->UserActivityModel->insert($arrInsert);
    }

    public function setICORankProject($projectId)
    {
        $this->load->model('Cron_model', 'CronModel');
        $this->load->model('Project_model', 'ProjectModel');
        $project = $this->ProjectModel->getSingleData(['ProjID' => $projectId]);
        if (empty($project)) {
            return FALSE;
        }


        $ICOrank=0; //initialise variable

        //get all links of this project
        $links_st = $this->CronModel->getProjectLinks($project['ProjID']);
        foreach ($links_st as $links) {
            //if it's a website link give 7 points (type 1)
            if ($links['LinkType']==1)$ICOrank=$ICOrank+7;

            //if it's a PDF white paper give 10 points (type 14)
            if ($links['LinkType']==14)$ICOrank=$ICOrank+10;

            //if it's Twitter give 2 points (type 5)
            if ($links['LinkType']==5)$ICOrank=$ICOrank+2;

            //if it's reddit give 2 points (type 6)
            if ($links['LinkType']==6)$ICOrank=$ICOrank+2;

            //if it's slack give 5 points (type 9)
            if ($links['LinkType']==9)$ICOrank=$ICOrank+6;

            //if it's bitcointalk give 5 points (type 4)
            if ($links['LinkType']==4)$ICOrank=$ICOrank+5;
        }


        //get all team members of this project
        $team_st = $this->CronModel->getProjectPeople($project['ProjID']);
        if (!empty($team_st)) {
            //if it's 1 team member give 4 points if 2 or more give 10 points
            if(count($team_st)==1)$ICOrank=$ICOrank+5;
            if(count($team_st)>=1)$ICOrank=$ICOrank+9;

            //if it's 1 linkedin 2 points if 2 or more give 5 points
            $links2 = $this->CronModel->getProjectLinksSt($project['ProjID']);
            if (!empty($links2)){
                if(count($links2)==1)$ICOrank=$ICOrank+3;
                if(count($links2)>=1)$ICOrank=$ICOrank+5;
            }

            //if it's 1 uploaded ID give 12 points if more give 18 points
            //*** THIS IS NOT IMPLEMENTED YET ***

        }


        //get editorial grade of this project and assign to ICOrank
        $ICOrank=$ICOrank+$project['ProjEditorialGrade'];

        //get paid level of this project
        if ($project['ProjSponsored']==1){
            //if project is SILVER, give 6 points
            //**** SILVER DOES NOT EXIST IN COINSCHEDULE YET

            //if project is GOLD, give 14 points
            //AT the moment, non platinum means is gold
            if ($project['ProjPlatinum']==0)$ICOrank=$ICOrank+11;

            //if project is PLATINUM give 22 points
            if ($project['ProjPlatinum']==1)$ICOrank=$ICOrank+17;

        }

        //record in DB
        $this->ProjectModel->update(['ProjICORank' => $ICOrank], ['ProjID' => $project['ProjID']]);
    }
}