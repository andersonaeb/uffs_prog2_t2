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

        $this->artist = $s[1];

        $this->cache = new App_Cache();
    }

    private function getKey($query) {
        return md5('last_fm' . $query);
    }

    public function getArtist() {
        $key = $this->getKey('_artist_' . $this->artist);

        $res = $this->cache->load($key);

        if ($res === false) {

            $artist = urlencode($this->artist);
            $url = 'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=' . $artist . '&api_key=' . $this->api_key . '&format=json';

            $json = file_get_contents($url);
            $decoded = Zend_Json::decode($json);

            $this->cache->save($decoded, $key);

            return $decoded;
        } else {
            return $res;
        }
    }

    public function getTopTracks() {

        $key = $this->getKey('_toptracks_' . $this->artist);
        
        $res = $this->cache->load($key);

        if ($res === false) {

            $artist = urlencode($this->artist);
            $url = 'http://ws.audioscrobbler.com/2.0/?method=artist.gettoptracks&artist='. $artist .'&api_key='. $this->api_key .'&limit=10&format=json';
            
            $json = file_get_contents($url);
            $decoded = Zend_Json::decode($json);

            $this->cache->save($decoded, $key);

            return $decoded;
        } else {
            return $res;
        }
    }
    
    public function getTopAlbums() {

        $key = $this->getKey('_topalbums_' . $this->artist);
        
        $res = $this->cache->load($key);

        if ($res === false) {

            $artist = urlencode($this->artist);
            $url = 'http://ws.audioscrobbler.com/2.0/?method=artist.gettopalbums&artist=' . $artist . '&api_key=' . $this->api_key . '&limit=5&format=json';

            $json = file_get_contents($url);
            $decoded = Zend_Json::decode($json);

            $this->cache->save($decoded, $key);

            return $decoded;
        } else {
            return $res;
        }
    }

}

?>
