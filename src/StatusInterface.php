<?php

namespace PhpDaemon;

/**
  Interface for watching a service's status
*/
interface StatusInterface
{
    /**
      Check if service is running
      @return [boolean]
    */
    public function isRunning();
}

