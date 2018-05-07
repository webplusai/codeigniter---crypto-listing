<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';

class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'UserModel');
    }

    public function register()
    {
        $initFormData = [
            'tx_username' => '',
            'tx_email' => '',
            'tx_password' => '',
            'agree' => ''
        ];
        $this->layoutData['formData'] = $initFormData;
        $this->layoutData['isValid'] = true;
        $this->layoutData['formError'] = false;
        $this->layoutData['isSuccess'] = false;
        $this->layoutData['loginError'] = false;
        $this->layoutData['loginSuccess'] = false;

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="diverror">', '</p>');

        if ($this->input->post('submit', TRUE)) {

            $this->layoutData['isValid'] = false;
            $formData = $this->input->post(NULL, TRUE);
            $this->layoutData['formData'] = $formData;

            $this->form_validation->set_rules('tx_username', 'Username', 'required|alpha_numeric|min_length[3]|max_length[40]|is_unique_mg[tx_username]');
            $this->form_validation->set_rules('tx_email', 'Email', 'required|valid_email|max_length[40]|is_unique_mg[tx_email]');
            $this->form_validation->set_rules('tx_password', 'Password', 'required|min_length[6]|max_length[15]');
            $this->form_validation->set_rules('agree', 'Agree with the terms & conditions', 'required');

            if ($this->form_validation->run() === TRUE) {
                $this->layoutData['isValid'] = true;
                $this->layoutData['isSuccess'] = true;
                $this->layoutData['formData'] = $initFormData;

                $arrInsert = [
                    'tx_username' => $formData['tx_username'],
                    'tx_email' => $formData['tx_email'],
                    'tx_password' => encryptPassword($formData['tx_password']),
                ];
                $this->UserModel->insert($arrInsert);
            } else {
                $fields = array('tx_username', 'tx_email', 'tx_password');
                $this->renderFormError($fields);
            }
        }

        $this->layoutData['head']['title'] = 'New user registration';
        $this->renderView('register.twig');
    }

    public function login()
    {
        if ($this->session->has_userdata(self::SS_USER) && $this->session->userdata(self::SS_USER)) {
            redirect('/profile', 'location', 301);
            exit();
        }

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->layoutData['isValid'] = true;
        $initFormData = ['tx_email' => ''];
        $this->layoutData['redirect'] = $this->input->get('redirect', TRUE);
        $this->layoutData['htmlHiddenForm'] = '';

        if ($this->input->post('submit', TRUE)) {
            $this->layoutData['isValid'] = false;
            $formData = $this->input->post(NULL, TRUE);
            $initFormData['tx_email'] = $formData['tx_email'];
            $this->form_validation->set_rules('tx_email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('tx_password', 'Password', 'required');

            // render hidden form
            $hiddenForm = array_merge($initFormData, $formData);
            if (!empty($hiddenForm)) {
                foreach ($hiddenForm as $name => $value) {
                    if ($name == 'tx_email' || $name == 'tx_password') {
                        continue;
                    }
                    $this->layoutData['htmlHiddenForm'] .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
                }
            }

            if ($this->form_validation->run() === TRUE) {
                $password = encryptPassword($formData['tx_password']);
                $authenticate = $this->UserModel->loginUser($formData['tx_email'], $password);
                if ($authenticate && $authenticate['id_user']) {
                    $userSession = array(
                        'id'        => $authenticate['id_user'],
                        'email'     => $authenticate['tx_email'],
                        'username'  => $authenticate['tx_username'],
                        'level'     => $authenticate['level'],
                    );
                    $this->loginSession($userSession, $formData);
                }
            }
        }

        $this->layoutData['formData'] = $initFormData;
        $this->layoutData['head']['title'] = 'User log in';
        $this->renderView('login.twig');
    }

    public function googleAuthLink()
    {
        $client = new Google_Client();
        $client->setClientId($this->layoutData['config']['google_client_id']);
        $client->setClientSecret($this->layoutData['config']['google_client_secret']);
        $client->setRedirectUri(base_url().'login/googlecallback');
        $client->addScope('https://www.googleapis.com/auth/plus.login');
        $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $auth_url = $client->createAuthUrl();
        redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
    }

    public function googleCallback()
    {
        $client = new Google_Client();
        $client->setClientId($this->layoutData['config']['google_client_id']);
        $client->setClientSecret($this->layoutData['config']['google_client_secret']);
        $client->setRedirectUri(base_url().'login/googlecallback');
        $client->addScope('https://www.googleapis.com/auth/plus.login');
        $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");

        try {
            if (isset($_GET['code'])) {
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                $client->setAccessToken($token);
                $oauth = new Google_Service_Oauth2($client);
                $authInfo = $oauth->userinfo_v2_me->get();
                if ($authInfo) {
                    $email = $authInfo->getEmail();
                    if (!empty($email)) {
                        $this->loginSocial($authInfo);
                    }
                }

                redirect('register?success=1', 'location', 301);
                exit();
            } else {
                redirect('register?error=1');
            }
        } catch (\Exception $e) {
            redirect('register?error=1');
        }
    }

    public function facebookAuthLink()
    {
        $fb = new \Facebook\Facebook([
            'app_id' => $this->layoutData['config']['facebook_app_id'],
            'app_secret' => $this->layoutData['config']['facebook_app_secret'],
            'default_graph_version' => 'v2.10',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl(base_url().'login/facebookcallback', $permissions);
        redirect($loginUrl, 'localhost', 301);
    }

    public function facebookCallback()
    {
        $fb = new Facebook\Facebook([
            'app_id' => $this->layoutData['config']['facebook_app_id'],
            'app_secret' => $this->layoutData['config']['facebook_app_secret'],
            'default_graph_version' => 'v2.10',
        ]);
        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken(base_url().'login/facebookcallback');
            $response = $fb->get('/me?fields=id,name,email', $accessToken);
            $user = $response->getGraphUser();
            $this->loginSocial($user);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            redirect('register?error=1');
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            redirect('register?error=1');
            exit;
        } catch (\Exception $e) {
            redirect('register?error=1');
            exit;
        }
    }

    public function forgot_password()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->layoutData['isValid'] = true;
        $this->layoutData['isSuccess'] = false;
        $formData = ['tx_email' => ''];

        if ($this->input->post('submit', TRUE)) {
            $this->layoutData['isValid'] = false;
            $this->form_validation->set_rules('tx_email', 'Email', 'required|valid_email');

            if ($this->form_validation->run() === TRUE) {
                $this->layoutData['isValid'] = true;
                $this->layoutData['isSuccess'] = true;

                $formData = $this->input->post(NULL, TRUE);
                $userInfo = $this->UserModel->getUserByEmail($formData['tx_email']);
                if (!empty($userInfo)) {
                    $this->load->model('Password_reset_model', 'PasswordResetModel');
                    $userInfo['reset_token'] = $this->PasswordResetModel->updatePasswordReset($userInfo);
                    $userInfo['reset_link'] = base_url() . 'change_password?token=' . $userInfo['reset_token'];

                    // send mail
                    $this->load->helper('email');
                    $mailBody = $this->load->view('emails/reset_password', $userInfo, true);
                    $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
                    $sendTo = array('email' => $formData['tx_email'], 'name' => '');
                    $subject = 'Your password reset';
                    send_email($sendFrom, $sendTo, $subject, $mailBody);
                }
            } else {
                $this->layoutData['formError'] = validation_errors();
            }
        }

        $this->layoutData['formData'] = $formData;
        $this->layoutData['head']['title'] = 'Forgot Password';
        $this->renderView('forgot_password.twig');
    }

    public function change_password_token()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        if (empty($this->input->get('token', TRUE))) {
            redirect('');
            exit;
        }

        $token = $this->input->get('token', TRUE);
        $this->layoutData['token'] = $token;
        $this->layoutData['isValid'] = true;
        $this->layoutData['isError'] = false;
        $this->load->model('Password_reset_model', 'PasswordResetModel');
        $tokenAccess = $this->PasswordResetModel->getDataByToken($token);

        if (empty($tokenAccess)) {
            $this->layoutData['isError'] = true;
        } else {
            if ($this->input->post('token', TRUE)) {
                $this->layoutData['isValid'] = false;
                $this->form_validation->set_rules('tx_password', 'New Password', 'required|min_length[6]|max_length[15]');
                $this->form_validation->set_rules('tx_repassword', 'Re-type new password', 'required|min_length[6]|max_length[15]|matches[tx_password]');

                if ($this->form_validation->run() === TRUE) {
                    $this->layoutData['isValid'] = true;

                    $formData = $this->input->post(NULL, TRUE);
                    $password = encryptPassword($formData['tx_password']);
                    $this->UserModel->update(['tx_password' => $password], ['id_user' => $tokenAccess['PassUserID']]);
                    $this->PasswordResetModel->delete(['PassUserID' => $tokenAccess['PassUserID']]);
                    redirect('login');
                } else {
                    $this->layoutData['formError'] = validation_errors();
                }
            }
        }

        $this->layoutData['head']['title'] = 'Reset Password';
        $this->renderView('change_password_token.twig');
    }

    public function logout()
    {
        $this->session->unset_userdata(self::SS_USER);
        $this->session->sess_destroy();
        redirect('');
        exit();
    }

    public function profile()
    {
        $ssUser = $this->session->userdata(self::SS_USER);
        $userInfo = $this->UserModel->getUserInfo($ssUser['id']);
        $this->layoutData['userInfo'] = $userInfo;

        $scriptJS = [];
        $scriptJS['new_common'] = base_url() . 'public/dist/new-common-min.js';
        $scriptJS['common'] = base_url() . 'public/dist/common-min.js';
        $scriptJS['bootstrap'] = base_url() . 'public/js/bootstrap.bundle.js';
        $scriptJS['notify'] = base_url() . 'public/js/notify.min.js';
        $this->renderJS($scriptJS, false);

        $this->layoutData['head']['title'] = 'User Profile';
        $this->renderView('profile.twig');
    }

    public function contact()
    {
        $this->layoutData['head']['title'] = 'Contact';
        $this->renderView('contact.twig');
    }

    public function alerts()
    {
        $this->layoutData['head']['title'] = 'Alerts';
        $this->renderView('alerts.twig');
    }

    private function processProjectAction($formData)
    {
        $this->load->model('Vote_model', 'VoteModel');
        $this->load->model('Project_model', 'ProjectModel');
        $projectDetail = $this->ProjectModel->getProjectDetail(false, $formData['id']);
        $user = $this->session->userdata(self::SS_USER);

        if (!empty($projectDetail)) {
            $updateVote = $this->VoteModel->updateVotes($user['id'], $formData);
            if ($updateVote === true) {
                $actID = 0;
                if ($formData['type'] == 1) {
                    $actID = $this->userActID['vote_up'];
                } else if ($formData['type'] == 2) {
                    $actID = $this->userActID['vote_down'];
                }

                if ($actID) {
                    $this->insertUserActivity($actID, $projectDetail);
                }
            }
        }
    }

    private function loginSocial($authInfo)
    {
        try {
            $userInfo = $this->UserModel->getUserByEmail($authInfo->getEmail());
            if (empty($userInfo)) {
                $parts = explode("@", $authInfo->getName());
                $username = $parts[0];
                $userInfo = array(
                    'tx_username'   => $username,
                    'tx_email'      => $authInfo->getEmail(),
                    'tx_password'   => encryptPassword($authInfo->getId()),
                    'first_pass'    => 1,
                    'level'         => 2,
                );
                $userId = $this->UserModel->insertUser($userInfo);
            } else {
                $userId = $userInfo['id_user'];
            }

            log_message('info', 'UserID: '.$userId);
            if ($userId) {
                $userSession = array(
                    'id'        => $userId,
                    'email'     => $userInfo['tx_email'],
                    'username'  => $userInfo['tx_username'],
                    'level'     => $userInfo['level'],
                );
                $this->loginSession($userSession);
            }
        } catch (\Exception $e) {
            log_message('error', 'User: '.$e->getMessage());
        }
    }

    private function loginSession($userSession, $formData = [])
    {
        if (!isset($userSession['id']) || empty($userSession['id'])) {
            return false;
        }

        session_regenerate_id(TRUE);
        $this->session->set_userdata(array(self::SS_USER => $userSession));
        $redirect = urldecode($this->input->post('redirect', TRUE));

        if (!empty($formData) && isset($formData['id']) && $formData['id']) {
            $this->processProjectAction($formData);
        }

        redirect($redirect);
        exit();
    }
}
