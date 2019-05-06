<?php

namespace Nicu\Tests;

use DI\Bridge\Slim\App;
use InvalidArgumentException;
use Nicu\{
    Nicu,
    Tests\Base\UnitTest
};
use Noodlehaus\Config;

class NicuTest extends UnitTest
{
    private function getConfigMock()
    {
        $config = $this->createMock(Config::class);
        $config->method('get')
            ->willReturn([]);
        return $config;
    }

    public function testConstruct()
    {
        $nicu = new Nicu($this->getConfigMock());
        $this->assertInstanceOf(Nicu::class, $nicu);
    }

    public function testRunApp()
    {
        $nicu = Nicu::getInstance('./config');
        $appMock = $this->createMock(App::class);
        $appMock->method('run')->willReturn(null);
        $this->setProtectedProperty($nicu, 'app', $appMock);
        $this->assertInstanceOf(Nicu::class, $nicu);
        $nicu->run();
    }

    public function testGetInstance()
    {
        $nicu = Nicu::getInstance('./config');
        $this->assertInstanceOf(Nicu::class, $nicu);
    }

    public function testGetApp()
    {
        $nicu = Nicu::getInstance('./config');
        $this->assertInstanceOf(App::class, $nicu->getApp());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWrongDirectoryOnCreation()
    {
        Nicu::getInstance('./notExistingFolder');
    }
}
