<?php

class Site_IndexController extends App_Controller_Action {

    public function indexAction() {

        if (!$this->getAuth()->hasIdentity()) {
            $this->_redirect('login');
        }

        $this->view->search_value = 'Song title, artist name';

        if ($this->getParam('s')) {

            $search = strtolower(str_replace(', ', ',', $this->getParam('s')));
            $s = explode(',', $search);

            $test = strtolower(str_replace(', ', ',', $this->view->search_value));

            if (isset($s[0]) && isset($s[1]) && $search != $test) {

                $this->view->search = $search;

                $this->view->search_value = $this->getParam('s');

                $this->view->show_content = true;

                /*
                 * Vagalume
                 */
                $vg = new App_Service_Vagalume();
                $lyric = $vg->get($s[0], $s[1]);
                $this->view->lyrics = $this->view->partial('index/lyrics.phtml', array('lyric' => $lyric));

                /*
                 * Last.fm
                 */
                $lm = new App_Service_Lastfm($search);

                // Top Albums
                $albums = $lm->getTopAlbums();
                $this->view->albums = $this->view->partial('index/albums.phtml', array('albums' => $albums));

                // Top Tracks
                $tracks = $lm->getTopTracks();
                $this->view->tracks = $this->view->partial('index/tracks.phtml', array('tracks' => $tracks));

                // Artist Info
                $info = $lm->getArtist();
                $this->view->artist = $this->view->partial('index/artist.phtml', array('info' => $info));

                // Menu Info
                $this->view->menu = $this->view->partial('index/menu.phtml', array('info' => $info));

                /*
                 * Youtube Video
                 */
                $yt = new App_Service_Youtube();
                $video = $yt->get($search);

                if (count($video) > 0) {
                    $video_url = $video[0]->getFlashPlayerUrl();
                    $this->view->video = $this->view->partial('index/video.phtml', array('video_url' => $video_url));
                }

                if (isset($lyric['mus'][0]['name'])) {
                    $this->view->song_name = $lyric['mus'][0]['name'];
                }
                if (isset($info['artist']['name'])) {
                    $this->view->artist_name = $info['artist']['name'];
                }

                /*
                 * Set metas
                 */

                $this->setTitle($this->view->song_name . ' - ' . $this->view->artist_name);

                if (isset($info['artist']['image'][3]['#text'])) {
                    $this->view->og_image = $info['artist']['image'][3]['#text'];
                }

                $this->setOgImage($this->view->og_image);

                $model = new Searches();
                $model->insert($search, $this->view->song_name, $this->view->artist_name, $this->view->og_image);
                
            } else {
                $this->view->error = true;
            }
        }

        if (!$this->view->show_content) {
            $model = new Searches();
            $this->view->searches = $model->getTop();
        }
    }

    public function logoutAction() {
        $this->getAuth()->logout();
        $this->_redirect('login');
    }

}