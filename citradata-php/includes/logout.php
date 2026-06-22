<?php
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION = [];
session_destroy();
redirect(url('index.php'));
