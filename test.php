#!/usr/bin/env php
<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "CLI only.\n";
    exit(1);
}

define('SPARKED', true);
define('FCPATH', __DIR__ . '/public' . DIRECTORY_SEPARATOR);

$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];
$_SERVER['SERVER_PORT'] = $_SERVER['SERVER_PORT'] ?? '80';
$_SERVER['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/test.php';
$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] ?? '/test.php';
$_SERVER['SCRIPT_FILENAME'] = $_SERVER['SCRIPT_FILENAME'] ?? __FILE__;
$_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

require __DIR__ . '/app/Config/Paths.php';

$paths = new Config\Paths();

chdir(FCPATH);

require rtrim($paths->systemDirectory, '/ ') . '/bootstrap.php';
require_once APPPATH . 'Helpers/Curl_Helper.php';

$api = new App\Libraries\ApiTreem_Lib();
$result = $api->getAgentInfo();

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;

exit(isset($result['status']) && intval($result['status']) === 1 ? 0 : 1);
