<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Testing Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Testing;

use Framework\CLI\Stream;
use Framework\Config\Config;
use Framework\MVC\App;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Class TestCase.
 */
abstract class TestCase extends PHPUnitTestCase
{
    protected string $configDir;
    protected Config $config;
    protected AppTesting $app;

    protected function setUp() : void
    {
        $this->prepareDefaults();
    }

    protected function prepareDefaults() : void
    {
        $this->app = new AppTesting($this->config ?? new Config($this->configDir));
    }

    public static function assertResponseStatus(string $status) : void
    {
        self::assertSame($status, App::response()->getStatus());
    }

    public static function assertResponseStatusCode(int $code) : void
    {
        self::assertSame($code, App::response()->getStatusCode());
    }

    public static function assertResponseStatusReason(string $reason) : void
    {
        self::assertSame($reason, App::response()->getStatusReason());
    }

    public static function assertResponseBodyContains(string $string) : void
    {
        self::assertStringContainsString($string, App::response()->getBody());
    }

    public static function assertMatchedRouteName(?string $name) : void
    {
        self::assertSame($name, App::router()->getMatchedRoute()->getName());
    }

    public static function assertStdoutContains(string $string) : void
    {
        self::assertStringContainsString($string, Stream::getOutput());
    }
}
