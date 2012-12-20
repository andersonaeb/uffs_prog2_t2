<?php

/*
 * Get Vagalume lyrics
 */

class App_Service_Vagalume {

    /**
     * Cache file
     * @var App_Cache
     */
    private $cache;

    public function __construct() {
        $this->cache = new App_Cache();
    }

    private function getKey($query) {
        return md5('vagalume_' . $query);
    }

    public function get($song, $artist) {

        $key = $this->getKey($song.'_'.$artist);
        $lyric = $this->cache->load($key);

        if ($lyric === false) {

            $url = "http://www.vagalume.com.br/api/search.php?art=" . urlencode($artist) . '&mus=' . urlencode($song);

            $json = file_get_contents($url);
            $decoded = Zend_Json::decode($json);

            $this->cache->save($decoded, $key);

            return $decoded;
        } else {
            return ($lyric);
        }
    }

}
?>
