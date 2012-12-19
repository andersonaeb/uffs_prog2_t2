<?php

/*
 * Custom Controller Action
 */

class App_Controller_Action extends Zend_Controller_Action {

    /**
     * Auth
     * @var App_Auth
     */
    private $auth;

    /**
     * Options application
     * @var type array
     */
    private $options;

    public function init() {
        parent::init();

        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $this->options = $bootstrap->getOptions();

        $this->auth = new App_Auth();

        $this->initView();
    }

    protected function setTitle($value) {
        $this->view->headTitle()->prepend($value);
        return $this;
    }

    protected function disableLayout() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function initView() {

        // Configure Doctype
        $doctypeHelper = new Zend_View_Helper_Doctype();
        $doctypeHelper->doctype(Zend_View_Helper_Doctype::HTML5);
        $this->view->headMeta()->setCharset('UTF-8');

        // Title
        $this->view->headTitle()->setSeparator(' / ');
        $this->view->headTitle($this->options['site']['title']);


        // Head-Link
        $this->view->headLink(
                array('rel' => 'icon',
                    'href' => $this->view->baseUrl(
                            'public/imgs/favicon.ico'),
                    'type' => 'image/x-icon'));

        $this->view->headLink(
                array('rel' => 'shortcut icon',
                    'href' => $this->view->baseUrl(
                            'public/imgs/favicon.ico'),
                    'type' => 'image/x-icon'));

        $this->view->headScript()->appendScript("var HOST = '" . $this->view->baseUrl() . "/';");

        // Add CSS and JS
        $this->addJs('jquery-1.2.6.min.js');
        $this->addJs('default.js');

        $this->addCss('reset.css');
        $this->addCss('main.css');
    }

    public function addJs($file) {
        $this->view->headScript()->appendFile(
                $this->view->baseUrl('js/' . $file));
    }

    public function addCss($file) {
        $this->view->headLink()->appendStylesheet(
                $this->view->baseUrl('css/' . $file));
    }

    protected function registerHelpers() {
        $this->view->addHelperPath('./application/views/helpers/');
    }

    /**
     * Auth
     * @return App_Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }
}

?>
