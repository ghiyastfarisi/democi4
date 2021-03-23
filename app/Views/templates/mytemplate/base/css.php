<?php
if (isset($_LoadCSS)) {
    if (!is_array($_LoadCSS)) {
        echo '$_LoadCSS must be an array';
    }
    if (count($_LoadCSS) > 0) {
        foreach($_LoadCSS as $css) {
            echo link_tag(ReplaceAlias($css));
        }
    }
}