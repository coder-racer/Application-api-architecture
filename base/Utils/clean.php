<?php
/**
 * @param $data
 * @return string
 */
function clean($data) {
        return trim(htmlspecialchars($data, ENT_COMPAT, 'UTF-8'));
    }

/**
 * @param $url
 * @return array|string|string[]
 */
function cleanUrl($url) {
        return str_replace(['%20', ' '], '-', $url);
    }

