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
use Framework\Testing\Constraints\MatchedRouteName;
use Framework\Testing\Constraints\ResponseBodyContains;
use Framework\Testing\Constraints\ResponseContainsHeader;
use Framework\Testing\Constraints\ResponseHeader;
use Framework\Testing\Constraints\ResponseStatus;
use Framework\Testing\Constraints\ResponseStatusCode;
use Framework\Testing\Constraints\ResponseStatusReason;
use Framework\Testing\Constraints\StderrContains;
use Framework\Testing\Constraints\StdoutContains;
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

    public static function assertResponseStatus(string $status, string $message = '') : void
    {
        self::assertThat(
            $status,
            new ResponseStatus(App::response()->getStatus()),
            $message
        );
    }

    public static function assertResponseStatusCode(int $code, string $message = '') : void
    {
        self::assertThat(
            $code,
            new ResponseStatusCode(App::response()->getStatusCode()),
            $message
        );
    }

    public static function assertResponseStatusReason(string $reason, string $message = '') : void
    {
        self::assertThat(
            $reason,
            new ResponseStatusReason(App::response()->getStatusReason()),
            $message
        );
    }

    public static function assertResponseBodyContains(string $string, string $message = '') : void
    {
        self::assertThat(
            App::response()->getBody(),
            new ResponseBodyContains($string),
            $message
        );
    }

    public static function assertResponseHeader(string $name, string $value, string $message = '') : void
    {
        self::assertThat(
            $value,
            new ResponseHeader(App::response()->getHeader($name), $name),
            $message
        );
    }

    public static function assertResponseContainsHeader(string $name, string $message = '') : void
    {
        self::assertThat(
            App::response()->getHeader($name),
            new ResponseContainsHeader($name),
            $message
        );
    }

    public static function assertMatchedRouteName(?string $name, string $message = '') : void
    {
        self::assertThat(
            $name,
            new MatchedRouteName(App::router()->getMatchedRoute()?->getName()),
            $message
        );
    }

    public static function assertStderrContains(string $string, string $message = '') : void
    {
        self::assertThat(
            Stderr::getContents(),
            new StderrContains($string),
            $message
        );
    }

    public static function assertStdoutContains(string $string, string $message = '') : void
    {
        self::assertThat(
            Stdout::getContents(),
            new StdoutContains($string),
            $message
        );
    }
}
