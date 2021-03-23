<?php
if (isset($_LoadJS)) {
    if (!is_array($_LoadJS)) {
        echo '$_LoadJS must be an array';
    }
    if (count($_LoadJS) > 0) {
        foreach($_LoadJS as $js) {
            echo script_tag(ReplaceAlias($js));
        }
    }
}