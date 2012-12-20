<?php

/**
 * Helper Twitter
 */
class Zend_View_Helper_Twitter extends Zend_View_Helper_Abstract {

    private $_enabled = false;

    public function enable() {
        if ($this->_enabled == false) {
            $this->view->headScript()->appendFile('http://platform.twitter.com/widgets.js');
            $this->_enabled = true;
        }
    }

    public function twitter() {
        return $this;
    }

    public function share($url, $text, $via, $type = 'horizontal') {
        if (!$this->_enabled)
            $this->enable();

        $html = "<a href='http://twitter.com/share' class='twitter-share-button' data-url='{$url}' data-text='{$text}' data-count='{$type}' data-via='{$via}'>Tweet</a>";
        return $html;
    }

}