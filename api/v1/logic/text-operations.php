<?php

function generate_extract($content, $length) {
    $content = preg_replace('@\n|\r@si', ' ', strip_tags($content));
    $res = "";
    $contentArray = explode(' ', $content);
    for ($i = 0; $i < $length; $i++) {
        if ($i >= count($contentArray)) {
            return $res;
        } else {
            if ($contentArray[$i] != "") {
                $res = $res . $contentArray[$i] . ' ';
            }
        }
    }
    return $res;
}

function generate_error_body($e) {
    return json_decode('{ "error": "' . $e->getMessage() . '" }');
}