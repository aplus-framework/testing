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

use Framework\Config\Config;
use Framework\HTTP\URL;
use Framework\MVC\App;
use LogicException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Class TestCase.
 */
abstract class TestCase extends PHPUnitTestCase
{
    protected string $configDir;

    /**
     * Run the application.
     *
     * @param bool $isCli True if is a CLI request, otherwise false
     */
    protected function runApp(bool $isCli = false) : void
    {
        if ( ! isset($this->configDir)) {
            throw new LogicException(static::class . '::$configDir is not set');
        }
        \ob_start(); // Avoid phpunit terminal output
        App::setIsCli($isCli);
        (new App(new Config($this->configDir)))->run();
        \ob_end_clean();
    }

    /**
     * Prepare an HTTP Request for tests.
     *
     * @param string|URL $url
     * @param string $method
     * @param array<string,string> $headers
     * @param string $body
     *
     * @return static
     */
    protected function prepareRequest(
        URL | string $url,
        string $method = 'GET',
        array $headers = [],
        string $body = ''
    ) : static {
        if ( ! $url instanceof URL) {
            $url = new URL($url);
        }
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['REQUEST_METHOD'] = \strtoupper($method);
        $_SERVER['REQUEST_SCHEME'] = $url->getScheme();
        $_SERVER['HTTP_HOST'] = $url->getHost();
        $query = $url->getQuery();
        $query = $query === null ? '' : '?' . $query;
        $_SERVER['REQUEST_URI'] = $url->getPath() . $query;
        foreach ($headers as $name => $value) {
            $name = \strtoupper($name);
            $name = \strtr($name, ['-' => '_']);
            $_SERVER['HTTP_' . $name] = $value;
        }
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = \explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $cookie = \explode('=', $cookie, 2);
                $cookie = \array_pad($cookie, 2, '');
                $cookie[0] = \ltrim($cookie[0]);
                $cookie[0] = \strtr($cookie[0], [' ' => '_']);
                $_COOKIE[$cookie[0]] = $cookie[1];
            }
        }
        $_GET = $url->getQueryData();
        App::request()->setBody($body); // @phpstan-ignore-line
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_SERVER['HTTP_CONTENT_TYPE'])
            && $_SERVER['HTTP_CONTENT_TYPE'] === 'application/x-www-form-urlencoded'
        ) {
            \parse_str($body, $_POST);
        }
        return $this;
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

    public static function assertMatchedRouteName(string $name) : void
    {
        self::assertSame($name, App::router()->getMatchedRoute()->getName());
    }
}
