<?php
declare(ticks = 1);

namespace PhpDaemon;

/**
  Daemonize an instance of a service
*/
class ServiceWrapper
{
    /**
      Service object being daemonized
    */
    private $wrappedService;

    /**
      PID file
    */
    private $pidFile;

    /**
      Daemon's PID
    */
    private $pid;

    /**
      Object to watch service's status
    */
    private $status;

    /**
      Create a service wrapper
      @param $service the servicee being daemonized
    */
    public function __construct(ServiceInterface $service)
    {
        $this->wrappedService = $service;
        $this->pidFile = sys_get_temp_dir() . '/' . str_replace('\\', '-', get_class($service)) . '.pid';
        $this->status = new PidFileStatus($this->pidFile);
    }

    /**
      Getter for PID
    */
    public function pid()
    {
        return $this->pid;
    }

    /**
      Check if service is running
      @return [boolean]
    */
    public function status()
    {
        return $this->status->isRunning();
    }

    /**
      Start the service

      Note: after the first fork() STDIN, STDOUT, and STDERR
      are all closed. If anything goes wrong after that the
      underlying service will need to log it or otherwise
      route notifications
    */
    public function start()
    {
        $child1 = $this->fork(); 
        fclose(STDIN);  // Close all of the standard
        fclose(STDOUT); // file descriptors as we
        fclose(STDERR); // are running as a daemon. 
        if (posix_setsid() < 0) {
            exit(1);
        }
        chdir('/');

        $daemon = $this->fork();
        $this->pid = $daemon;
        file_put_contents($this->pidFile, $daemon);
        $this->registerSignals();
        $this->wrappedService->start($this->status);
    }

    /**
      Tell the wrapped service to stop
    */
    public function stop()
    {
        $this->wrappedService->stop();
        unlink($this->pidFile);
    }

    /**
      Helper method
      Effectively call stop() on SIGTERM and SIGHUP 
    */
    private function registerSignals()
    {
        $pidfile = $this->pidFile;
        $service = $this->wrappedService;
        $handler = function($signo) use ($pidfile, $service) {
            switch ($signo) {
                case SIGTERM:
                case SIGHUP:
                    $service->stop();
                    unlink($pidfile);
                    break;
            }
        };
        pcntl_signal(SIGTERM, $handler);
        pcntl_signal(SIGHUP, $handler);
    }

    /**
      Fork the process
      @return [int] child PID

      Note: parent process exits
    */
    private function fork()
    {
        $parent = pcntl_fork();
        if ($parent === -1) {
            echo "Cannot fork!";
            exit(1);
        } elseif ($parent > 0) {
            exit(0);
        }

        return posix_getpid();
    }
}

