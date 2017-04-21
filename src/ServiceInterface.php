<?php

namespace PhpDaemon;

/**
  Interface for a wrapped service
*/
interface ServiceInterface
{
    /**
      Execute the service. This method should not return unless
      the service has stopped
      @param $status interface to check whether the service is
        supposed to be running
    */
    public function start(StatusInterface $status);

    /**
      Stop the service
    */
    public function stop();
}

