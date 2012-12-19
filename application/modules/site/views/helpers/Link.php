<?php

/**
 * Helper que exibe a url especificada
 */
class Zend_View_Helper_Link extends Zend_View_Helper_Abstract {

    public function link($path) {

        $url = 'http://' . $_SERVER['HTTP_HOST'];

        $url .= $this->view->baseUrl($path);

        return $url;
    }

}