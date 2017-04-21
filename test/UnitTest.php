<?php

use PhpDaemon\ServiceWrapper;

class UnitTest extends PHPUnit_Framework_TestCase
{
    public function testServices()
    {
        $service = new TestService(true);
        $wrapper = new ServiceWrapper($service);
        $this->assertEquals(false, $wrapper->status());

        $init = realpath(__DIR__ . '/init.php');
        // make sure file exists
        $this->assertNotEquals(false, $init);

        // start service, wait, verify
        exec("php {$init} start");
        sleep(2);
        $this->assertEquals(true, $wrapper->status());

        // stop service, wait, verify
        exec("php {$init} stop");
        sleep(2);
        $this->assertEquals(false, $wrapper->status());

        // double-check cleanup
        $pidFile = sys_get_temp_dir() . '/TestService.pid';
        $this->assertEquals(false, file_exists($pidFile));
    }
}

