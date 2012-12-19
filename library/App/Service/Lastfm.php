<?php

/*
 * Get informations from Last.fm
 */

class App_Service_Lastfm {

    /**
     * Cache file
     * @var App_Cache
     */
    private $cache;

    /**
     * Api key Last.fm
     * @var type string
     */
    private $api_key = '18e3ab41c7dc2537aea3117864e442d4';

    /**
     * Artist
     * @var type string
     */
    private $artist;

    public function __construct($query) {

        $s = explode(',', $query);

        $this->artist = $s[0];

        $this->cache = new App_Cache();
    }

    public function getTopAlbums() {

        $url = 'http://ws.audioscrobbler.com/2.0/?method=artist.gettopalbums&artist=' . urlencode($this->artist) . '&api_key=' . $this->api_key . '&format=json';
        
        $json = file_get_contents($url);
        
        if ($json) {
            return Zend_Json::decode($json);
        }
    }

}

?>
