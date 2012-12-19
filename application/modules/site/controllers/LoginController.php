<?php

class Site_LoginController extends App_Controller_Action {

    /**
     * Session
     * @var Zend_Session_Namespace
     */
    private $session;

    public function init() {
        parent::init();

        $this->session = new Zend_Session_Namespace('php_trab2_login');
    }

    public function indexAction() {

        $this->setTitle('Login');

        $this->session->unsetAll();
        
        $this->view->error = $this->getParam('error');
    }

    public function signupAction() {

        $this->setTitle('Sign Up');

        $this->view->error = $this->getParam('error');
        $this->view->user = $this->session;
    }

    public function dosignAction() {

        if ($this->_request->isPost()) {

            $this->session->name = $name = addslashes($this->getParam('name'));
            $this->session->email = $email = addslashes($this->getParam('email'));
            $this->session->username = $username = addslashes($this->getParam('username'));
            $password = addslashes($this->getParam('password'));

            if (strlen($name) > 2 && strlen($username) > 2 && strlen($password) > 2 && filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $db = new DbTable_Users();
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'username' => $username,
                    'password' => md5($password)
                );


                // Verify if the email is already registered
                if ($db->fetchAll($db->select()->where('email = ?', $email))->count() > 0) {
                    $this->_redirect('login/signup?error=2');
                }

                // Verify if the username is already registered
                if ($db->fetchAll($db->select()->where('username = ?', $username))->count() > 0) {
                    $this->_redirect('login/signup?error=3');
                }

                // Case success, insert user redirect index
                $db->insert($data);
                $this->getAuth()->login($username, $password);
                $this->_redirect('');
            } else {
                /*
                 * Fields invalid
                 */
                $this->_redirect('login/signup?error=1');
            }
        } else {
            $this->_redirect('login/signup');
        }
    }

    public function doauthAction() {

        if ($this->_request->isPost()) {

            $username = addslashes($this->getParam('username'));
            $password = addslashes($this->getParam('password'));

            if (strlen($username) > 2 && strlen($password) > 2) {

                if ($this->getAuth()->login($username, $password)) {
                    $this->_redirect('');
                } else {
                    $this->_redirect('login?error=1');
                }
            } else {
                $this->_redirect('login?error=1');
            }
        } else {
            $this->_redirect('login');
        }
    }

}