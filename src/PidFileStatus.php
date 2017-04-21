<?php

namespace PhpDaemon;

/**
  Status implementation that watches a PID file
  to check is a service is running
*/
class PidFileStatus implements StatusInterface
{
    /**
      [string] filename to watch
    */
    private $file;

    /**
      @param $file [string] filename to watch
    */
    public function __construct($file)
    {
        $this->pidFile = $file;
    }

    /**
      Watched service is running
      @return [boolean]
    */
    public function isRunning()
    {
        return file_exists($this->pidFile);
    }
}

