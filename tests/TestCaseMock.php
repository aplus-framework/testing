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

use Aplus\Testing\AppTesting;
use Aplus\Testing\TestCase;

class TestCaseMock extends TestCase
{
    protected array | string | null $configs = __DIR__ . '/config';
    public AppTesting $app;

    public function __construct(string $name) // @phpstan-ignore-line
    {
        parent::__construct($name);
        $this->prepareDefaults();
    }
}
