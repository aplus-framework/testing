<?php
/*
 * This file is part of Aplus Framework Testing Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Testing;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Class TestCaseTest.
 *
 * @runTestsInSeparateProcesses
 */
final class TestCaseTest extends PHPUnitTestCase
{
    protected TestCaseMock $testCase;

    protected function setUp() : void
    {
        $this->testCase = new TestCaseMock();
    }

    public function testRunHttp() : void
    {
        self::assertFalse(isset($_SERVER['SERVER_PROTOCOL']));
        self::assertFalse(isset($_SERVER['REQUEST_METHOD']));
        self::assertFalse(isset($_SERVER['REQUEST_SCHEME']));
        self::assertFalse(isset($_SERVER['HTTP_HOST']));
        self::assertFalse(isset($_SERVER['REQUEST_URI']));
        self::assertSame([], $_COOKIE);
        self::assertSame([], $_GET);
        self::assertSame([], $_POST);
        $this->testCase->app->runHttp(
            'http://domain.tld:8080/users?page=10',
            'GET',
            [
                'user-agent' => 'Foo-Bar/1.1',
                'cookie' => 'foo=1; bar=2',
            ]
        );
        self::assertSame('HTTP/1.1', $_SERVER['SERVER_PROTOCOL']);
        self::assertSame('GET', $_SERVER['REQUEST_METHOD']);
        self::assertSame('http', $_SERVER['REQUEST_SCHEME']);
        self::assertSame('domain.tld:8080', $_SERVER['HTTP_HOST']);
        self::assertSame('/users?page=10', $_SERVER['REQUEST_URI']);
        self::assertSame('Foo-Bar/1.1', $_SERVER['HTTP_USER_AGENT']);
        self::assertSame('foo=1; bar=2', $_SERVER['HTTP_COOKIE']);
        self::assertSame('1', $_COOKIE['foo']);
        self::assertSame('2', $_COOKIE['bar']);
        self::assertSame('10', $_GET['page']);
        self::assertSame([], $_POST);
    }

    public function testRunHttpWithPost() : void
    {
        $this->testCase->app->runHttp(
            'https://domain.tld/contact',
            'POST',
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'name=John&opt[]=x&opt[]=y'
        );
        self::assertSame('HTTP/1.1', $_SERVER['SERVER_PROTOCOL']);
        self::assertSame('POST', $_SERVER['REQUEST_METHOD']);
        self::assertSame('https', $_SERVER['REQUEST_SCHEME']);
        self::assertSame('domain.tld', $_SERVER['HTTP_HOST']);
        self::assertSame('/contact', $_SERVER['REQUEST_URI']);
        self::assertSame('application/x-www-form-urlencoded', $_SERVER['HTTP_CONTENT_TYPE']);
        self::assertSame([], $_COOKIE);
        self::assertSame([], $_GET);
        self::assertSame([
            'name' => 'John',
            'opt' => [
                'x',
                'y',
            ],
        ], $_POST);
    }
}
