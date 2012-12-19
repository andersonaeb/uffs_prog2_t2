<?php

class Site_IndexController extends App_Controller_Action {

    public function indexAction() {

        if (!$this->getAuth()->hasIdentity()) {
            $this->_redirect('login');
        }

        
        $search = 'jorge & mateus, flor';
        $this->view->search = $search;
        
        /*
         * Last.fm
         */
        
        $lm = new App_Service_Lastfm($search);
        $albums = $lm->getTopAlbums();
        
        print_r($albums);
        
        /*
         * Youtube Video
         */
        $yt = new App_Service_Youtube();
        $video = $yt->get($search);

        if (count($video) > 0) {
            $video_url = $video[0]->getFlashPlayerUrl();
            $this->view->video = $this->view->partial('index/video.phtml', array('video_url' => $video_url));
        }
    }

    public function logoutAction() {
        $this->getAuth()->logout();
        $this->_redirect('login');
    }

}

