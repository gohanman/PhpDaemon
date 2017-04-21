<?php
declare(ticks = 1);

namespace PhpDaemon;

class InitScript
{
    private $wrapper;

    public function __construct(ServiceWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function run()
    {
        global $argv;
        if (count($argv) !== 2) {
            $script = basename($_SERVER['PHP_SELF']);
            echo "Usage: {$script} [start|stop|status]" . PHP_EOL;
            exit(1);
        }

        switch (strtolower($argv[1])) {
            case 'start':
                $this->wrapper->start();
                break;
            case 'stop':
                $this->wrapper->stop();
                break;
            case 'status':
                echo ($this->wrapper->status() ? 'TestService is running' : 'TestService is stopped') . PHP_EOL;
                break;
            default:
                echo 'Unknown command ' . $argv[1] . PHP_EOL;
        }
    }
}

