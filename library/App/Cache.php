<?php

/*
 * Cache file
 */

class App_Cache {

    /**
     * Lifetime in seconds
     * @var type int
     */
    private $lifetime = null;

    /**
     * Directory from cache
     * @var type string
     */
    private $directory = null;

    /**
     * Zend_Cache
     * @var Zend_Cache_Frontend
     */
    private $cache;

    public function __construct() {

        $this->lifetime = 86400;

        $this->directory = APPLICATION_PATH . '/cache';

        $this->cache = Zend_Cache::factory('Core', 'File'
                        , array('lifetime' => $this->lifetime, 'automatic_serialization' => true)
                        , array('cache_dir' => $this->directory));
    }
    public function load($cache_id) {

        if (($data = $this->cache->load($cache_id)) === false) {
            return false;
        } else {
            return $data;
        }
    }

    public function save($content, $cache_id) {
        
        $this->cache->save($content, $cache_id);
    }

}

?>
