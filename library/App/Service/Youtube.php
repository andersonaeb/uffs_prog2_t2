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

    private function getKey($query) {
        return md5('youtube_video_' . $query);
    }

    public function get($query) {

        $key = $this->getKey($query);
        $video = $this->cache->load($key);

        if ($video === false) {

            $yt = new Zend_Gdata_YouTube();
            $ytq = $yt->newVideoQuery();

            $ytq->videoQuery = $query;
            $ytq->maxResults = 1;

            $res = $yt->getVideoFeed($ytq);

            $this->cache->save($res, $key);

            return $res;
        } else {
            return ($video);
        }
    }

}

?>
