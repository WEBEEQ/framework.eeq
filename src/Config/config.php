<?php

session_start();

ini_set('register_globals', '0');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Europe/Warsaw');
set_time_limit(60);
ini_set('memory_limit', '256M');
ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '10M');
