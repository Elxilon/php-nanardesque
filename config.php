<?php
const __PRJ__ = __DIR__ . '/';
define("__RPRJ__", "/" . basename(__DIR__) . "/");
const __IMG__ = __PRJ__ . "images/";
const __RIMG__ = __RPRJ__ . "images/";
$GLOBALS['session_options'] = [
    'use_strict_mode' => true,
    'gc_maxlifetime' => 60 * 60 * 24, // 1 jour
    'use_only_cookies' => true,
    'cookie_lifetime' => 60 * 60 * 24, // 1 jour
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
];