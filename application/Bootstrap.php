<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    public function __construct($application) {

        parent::__construct($application);

        $front = Zend_Controller_Front::getInstance();

        /**
         * Registra o plugin para controle de erros
         */
        $plugin = new App_Controller_Plugin_ErrorHandler ();
        $front->registerPlugin($plugin);
    }

    /**
     * Bootstraps the Autoloader
     *
     * @access protected
     * @return void
     */
    protected function _initAutoloader() {
        require_once 'Zend/Loader/Autoloader.php';

        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(TRUE);
    }

    /**
     * Inits the layouts (full configuration)
     *
     * @access protected
     * @return void
     */
    protected function _initLayout() {
        Zend_Layout::startMvc(APPLICATION_PATH . '/modules/site/views/layouts/');
    }

}