<?php

class Site_IndexController extends App_Controller_Action {

    public function indexAction() {

        if (!$this->getAuth()->hasIdentity()) {
            $this->_redirect('login');
        }
    }

    public function logoutAction() {
        $this->getAuth()->logout();
        $this->_redirect('login');
    }

}

