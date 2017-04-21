<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    include(__DIR__ . '/../vendor/autoload.php');
}

if (!class_exists('TestService')) {
    include(__DIR__ . '/TestService.php');
}

