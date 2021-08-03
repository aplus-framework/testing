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

use Framework\Testing\AppTesting;
use Framework\Testing\TestCase;

class TestCaseMock extends TestCase
{
    protected string $configDir = __DIR__ . '/config';
    public AppTesting $app;

    public function __construct(mixed ...$arguments)
    {
        parent::__construct(...$arguments);
        $this->prepareDefaults();
    }
}
