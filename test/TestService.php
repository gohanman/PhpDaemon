<?php

use PhpDaemon\ServiceInterface;
use PhpDaemon\StatusInterface;

include_once(__DIR__ . '/../vendor/autoload.php');

/**
  Simple service implementation logs the time
  to TestService.log until stopped
*/
class TestService implements ServiceInterface
{
    private $running = false;
    private $cleanup = true;

    /**
      Cleanup:
        true => delete the log file on exit
        false => leave the log file on exit
    */
    public function __construct($cleanup=true)
    {
        $this->cleanup = $cleanup;
    }

    public function start(StatusInterface $status)
    {
        $this->running = true;
        $logfile = sys_get_temp_dir() . '/TestService.log';
        $fp = fopen($logfile, 'w');
        fwrite($fp, "Starting TestService\n");
        while ($this->running && $status->isRunning()) {
            fwrite($fp, microtime(true) . "\n");
            sleep(1);
        }
        fwrite($fp, "Stopping TestService\n");
        fclose($fp);
        if ($cleanup) {
            unlink($logfile);
        }
    }

    public function stop()
    {
        $this->running = false;
    }
}

