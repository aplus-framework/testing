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

use Exception;
use Framework\Testing\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class RealTestCaseTest extends TestCase
{
    protected array | string | null $configs = __DIR__ . '/config';

    public function testHttp() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseStatus('404 Not Found');
        self::assertResponseStatusCode(404);
        self::assertResponseStatusReason('Not Found');
        self::assertResponseBodyContains('Error 404');
        self::assertResponseContainsHeader('content-type');
        self::assertResponseHeader('content-type', 'text/html; charset=UTF-8');
        self::assertMatchedRouteName('not-found');
    }

    public function testResponseStatus() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseStatus('404 Not Found');
    }

    public function testResponseStatusFail() : void
    {
        try {
            self::assertResponseStatus('404 Not Found');
        } catch (Exception $exception) {
            self::assertSame(
                "Failed asserting that '404 Not Found' is the Response Status.",
                $exception->getMessage()
            );
        }
    }

    public function testResponseStatusCode() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseStatusCode(404);
    }

    public function testResponseStatusCodeFail() : void
    {
        try {
            self::assertResponseStatusCode(404);
        } catch (Exception $exception) {
            self::assertSame(
                'Failed asserting that 404 is the Response Status Code.',
                $exception->getMessage()
            );
        }
    }

    public function testResponseStatusReason() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseStatusReason('Not Found');
    }

    public function testResponseStatusReasonFail() : void
    {
        try {
            self::assertResponseStatusReason('Not Found');
        } catch (Exception $exception) {
            self::assertSame(
                "Failed asserting that 'Not Found' is the Response Status Reason.",
                $exception->getMessage()
            );
        }
    }

    public function testResponseBodyContains() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseBodyContains('Error 404');
        self::assertResponseBodyContains('');
    }

    public function testResponseBodyContainsFail() : void
    {
        try {
            self::assertResponseBodyContains('Error 404');
        } catch (Exception $exception) {
            self::assertSame(
                "Failed asserting that Response Body '' contains \"Error 404\".",
                $exception->getMessage()
            );
        }
    }

    public function testResponseBodyContainsFailMessage() : void
    {
        try {
            self::assertResponseBodyContains('Error 404', 'ABC');
        } catch (Exception $exception) {
            self::assertSame(
                "ABC\nFailed asserting that Response Body '' contains \"Error 404\".",
                $exception->getMessage()
            );
        }
    }

    public function testResponseContainsHeader() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseContainsHeader('content-type');
    }

    public function testResponseContainsHeaderFail() : void
    {
        try {
            self::assertResponseContainsHeader('content-type');
        } catch (Exception $exception) {
            self::assertSame(
                'Failed asserting that the Response contains header "content-type".',
                $exception->getMessage()
            );
        }
    }

    public function testResponseContainsHeaderFailMessage() : void
    {
        try {
            self::assertResponseContainsHeader('content-type', 'ABC');
        } catch (Exception $exception) {
            self::assertSame(
                "ABC\nFailed asserting that the Response contains header \"content-type\".",
                $exception->getMessage()
            );
        }
    }

    public function testResponseHeader() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseHeader('content-type', 'text/html; charset=UTF-8');
    }

    public function testResponseHeaderFail() : void
    {
        try {
            self::assertResponseHeader('content-type', 'text/html; charset=UTF-8');
        } catch (Exception $exception) {
            self::assertSame(
                "Failed asserting that 'text/html; charset=UTF-8' is equals the Response Header \"content-type\".",
                $exception->getMessage()
            );
        }
    }

    public function testCli() : void
    {
        $this->app->runCli('help about');
        self::assertStdoutContains('command-line interface');
    }

    public function testStdoutContains() : void
    {
        $this->app->runCli('help about');
        self::assertStdoutContains('command-line interface');
        self::assertStdoutContains('');
    }

    public function testStdoutContainsFail() : void
    {
        try {
            self::assertStdoutContains('foo');
        } catch (Exception $exception) {
            self::assertSame(
                "Failed asserting that STDOUT '' contains \"foo\".",
                $exception->getMessage()
            );
        }
    }

    public function testStdoutContainsFailMessage() : void
    {
        try {
            self::assertStdoutContains('foo', 'ABC');
        } catch (Exception $exception) {
            self::assertSame(
                "ABC\nFailed asserting that STDOUT '' contains \"foo\".",
                $exception->getMessage()
            );
        }
    }

    public function testStderrContains() : void
    {
        $this->app->runCli('index');
        \fwrite(\STDERR, 'Foobar');
        self::assertStderrContains('Foobar');
        self::assertStderrContains('');
    }

    public function testStderrContainsFail() : void
    {
        try {
            self::assertStderrContains('foo');
        } catch (Exception $exception) {
            self::assertSame(
                "Failed asserting that STDERR '' contains \"foo\".",
                $exception->getMessage()
            );
        }
    }

    public function testStderrContainsFailMessage() : void
    {
        try {
            self::assertStderrContains('foo', 'ABC');
        } catch (Exception $exception) {
            self::assertSame(
                "ABC\nFailed asserting that STDERR '' contains \"foo\".",
                $exception->getMessage()
            );
        }
    }

    public function testMatchedRouteName() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertMatchedRouteName('not-found');
    }

    public function testMatchedRouteNameFail() : void
    {
        try {
            self::assertMatchedRouteName('foo');
        } catch (Exception $exception) {
            self::assertSame(
                "Failed asserting that 'foo' is the Matched Route Name.",
                $exception->getMessage()
            );
        }
    }
}
