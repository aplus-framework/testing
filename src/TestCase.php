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

use Framework\CLI\Streams\Stderr;
use Framework\CLI\Streams\Stdout;
use Framework\Config\Config;
use Framework\MVC\App;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Class TestCase.
 *
 * @package testing
 */
abstract class TestCase extends PHPUnitTestCase
{
    /**
     * @var array<string,mixed>|string|null
     */
    protected array | string | null $configs = null;
    protected Config $config;
    protected AppTesting $app;

    protected function setUp() : void
    {
        $this->prepareDefaults();
    }

    protected function prepareDefaults() : void
    {
        $this->app = new AppTesting($this->config ?? new Config($this->configs));
    }

    public static function assertResponseStatus(string $status) : void
    {
        self::assertSame(
            $status,
            App::response()->getStatus(),
            'Assert Response Status:'
        );
    }

    public static function assertResponseStatusCode(int $code) : void
    {
        self::assertSame(
            $code,
            App::response()->getStatusCode(),
            'Assert Response Status Code:'
        );
    }

    public static function assertResponseStatusReason(string $reason) : void
    {
        self::assertSame(
            $reason,
            App::response()->getStatusReason(),
            'Assert Response Status Reason:'
        );
    }

    public static function assertResponseBodyContains(string $string) : void
    {
        self::assertStringContainsString(
            $string,
            App::response()->getBody(),
            'Assert Response Body Contains:'
        );
    }

    public static function assertResponseHeader(string $name, string $value) : void
    {
        self::assertSame(
            $value,
            App::response()->getHeader($name),
            'Assert Response Header:'
        );
    }

    public static function assertResponseContainsHeader(string $name) : void
    {
        self::assertNotNull(
            App::response()->getHeader($name),
            'Assert Response Contains Header:'
        );
    }

    public static function assertMatchedRouteName(?string $name) : void
    {
        self::assertSame(
            $name,
            App::router()->getMatchedRoute()?->getName(),
            'Assert Matched Route Name:'
        );
    }

    public static function assertStderrContains(string $string) : void
    {
        self::assertStringContainsString(
            $string,
            Stderr::getContents(),
            'Assert STDERR Contains:'
        );
    }

    public static function assertStdoutContains(string $string) : void
    {
        self::assertStringContainsString(
            $string,
            Stdout::getContents(),
            'Assert STDOUT Contains:'
        );
    }
}
