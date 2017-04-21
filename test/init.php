<?php
declare(ticks = 1);

/**
  A pseudo init script to run TestService using
  a ServiceWrapper
*/

if (basename(__FILE__) !== basename($_SERVER['PHP_SELF'])) {
    return;
}

if (count($argv) != 2) {
    echo "Usage: init.php [start|stop|status]" . PHP_EOL;
    exit(1);
}

include_once(__DIR__ . '/../vendor/autoload.php');
include_once(__DIR__ . '/TestService.php');
$service = new TestService();
$wrapper = new PhpDaemon\ServiceWrapper($service);
switch (strtolower($argv[1])) {
    case 'start':
        $wrapper->start();
        break;
    case 'stop':
        $wrapper->stop();
        break;
    case 'status':
        echo ($wrapper->status() ? 'TestService is running' : 'TestService is stopped') . PHP_EOL;
        break;
    default:
        echo 'Unknown command ' . $argv[1] . PHP_EOL;
}

