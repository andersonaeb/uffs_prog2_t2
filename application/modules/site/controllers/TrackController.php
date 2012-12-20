<?php

class Site_TrackController extends App_Controller_Action {

    public function indexAction() {

        if ($this->getParam('s')) {

            $search = strtolower(str_replace(', ', ',', $this->getParam('s')));
            $s = explode(',', $search);

            $test = strtolower(str_replace(', ', ',', $this->view->search_value));

            if (isset($s[0]) && isset($s[1]) && $search != $test) {

                $this->view->search = $search;
                
                $model = new Searches();
                $res = $model->get($search);

                if (count($res) > 0) {

                    $this->view->song_name = $res->song_name;
                    
                    $this->view->artist_name = $res->artist_name;
                    
                    $this->view->og_image = $res->image;
                    
                    /*
                     * Set metas
                     */

                    $this->setTitle($this->view->song_name . ' - ' . $this->view->artist_name);

                    $this->setOgImage($this->view->og_image);
                    
                } else {
                    $this->_redirect('');
                }
            } else {
                $this->_redirect('');
            }
        }
    }

}

