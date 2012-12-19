<?php

/*
 * Get youtube video
 */

class App_Service_Youtube {

    /**
     * Cache file
     * @var App_Cache
     */
    private $cache;

    public function __construct() {
        $this->cache = new App_Cache();
    }

    public function get($query) {

        $video = $this->cache->load($query);

        if ($video === false) {

            $yt = new Zend_Gdata_YouTube();
            $ytq = $yt->newVideoQuery();

            $ytq->videoQuery = $query;
            $ytq->maxResults = 1;

            $res = $yt->getVideoFeed($ytq);

            $this->cache->save($res, $query);

            return $res;
        } else {
            return ($video);
        }
    }

}

?>
