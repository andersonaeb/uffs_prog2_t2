<?php

/**
 * Helper Facebook Like
 */
class Zend_View_Helper_Facebook extends Zend_View_Helper_Abstract {

    public function facebook() {
        return $this;
    }

    public function like($url, $width = 90, $height = 20, $layout = 'button_count', $color = 'light') {
        $html = "
                <iframe src=\"http://www.facebook.com/plugins/like.php?href=" . urlencode($url) . "&amp;send=false&amp;layout={$layout}&amp;width={$width}&amp;show_faces=true&amp;action=like&amp;colorscheme={$color}&amp;font&amp;height={$height}\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:{$width}px; height:{$height}px;\" allowTransparency=\"true\"></iframe>
            ";
        return $html;
    }

}