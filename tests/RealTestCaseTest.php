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

use Framework\Testing\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class RealTestCaseTest extends TestCase
{
    protected string $configDir = __DIR__ . '/config';

    public function testHttp() : void
    {
        $this->app->runHttp('http://localhost');
        self::assertResponseStatus('404 Not Found');
        self::assertResponseStatusCode(404);
        self::assertResponseStatusReason('Not Found');
        self::assertResponseBodyContains('Error 404');
        self::assertMatchedRouteName('not-found');
    }

    public function testCli() : void
    {
        $this->app->runCli('help about');
        self::assertStdoutContains('command-line interface');
    }
}