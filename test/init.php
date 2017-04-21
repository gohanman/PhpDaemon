<?php
declare(ticks = 1);

/**
  A pseudo init script to run TestService using
  a ServiceWrapper
*/

if (basename(__FILE__) !== basename($_SERVER['PHP_SELF'])) {
    return;
}

include_once(__DIR__ . '/../vendor/autoload.php');
include_once(__DIR__ . '/TestService.php');

$service = new TestService();
$wrapper = new PhpDaemon\ServiceWrapper($service);
$init = new PhpDaemon\InitScript($wrapper);
$init->run();

