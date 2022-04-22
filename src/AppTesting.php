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

use Closure;
use Framework\CLI\Streams\Stderr;
use Framework\CLI\Streams\Stdout;
use Framework\Config\Config;
use Framework\HTTP\URL;
use Framework\MVC\App;

/**
 * Class AppTesting.
 *
 * @package testing
 */
class AppTesting
{
    protected App $app;

    /**
     * AppTesting constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->app = new class($config) extends App {
            public function runCliWithExec(string $command) : void
            {
                $this->prepareToRun();
                static::console()->exec($command);
            }
        };
    }

    /**
     * Run App in a Closure suppressing the output buffer.
     *
     * It avoids buffer to be output in the PHPUnit terminal.
     *
     * @param Closure $closure
     */
    protected function suppressOutputBuffer(Closure $closure) : void
    {
        \ob_start(static function () {
            return '';
        });
        $closure($this->app);
        \ob_end_clean();
    }

    /**
     * Simulate a CLI request for tests.
     *
     * @param string $command Command line
     * @param array<string,string> $env Environment variables
     *
     * @return void
     */
    public function runCli(string $command, array $env = []) : void
    {
        App::setIsCli(true);
        Stderr::init();
        Stdout::init();
        $this->suppressOutputBuffer(static function (App $app) use ($command) : void {
            if ($command === '') {
                $command = 'index';
            }
            $app->runCliWithExec($command); // @phpstan-ignore-line
        });
    }

    /**
     * Simulate an HTTP Request for tests.
     *
     * @param string|URL $url
     * @param string $method
     * @param array<string,string> $headers
     * @param string $body
     *
     * @return void
     */
    public function runHttp(
        URL | string $url,
        string $method = 'GET',
        array $headers = [],
        string $body = ''
    ) : void {
        App::setIsCli(false);
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
        $this->suppressOutputBuffer(static function (App $app) : void {
            $app->runHttp();
        });
    }
}
