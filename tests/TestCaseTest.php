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
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

final class TestCaseTest extends PHPUnitTestCase
{
    protected TestCase $testCase;

    protected function setUp() : void
    {
        $this->testCase = new TestCase();
    }

    public function testSample() : void
    {
        self::assertSame(
            'Framework\Testing\TestCase::test',
            $this->testCase->test()
        );
    }
}
