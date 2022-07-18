<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Testing Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Testing;

use PHPUnit\Framework\TestCase;

/**
 * Class EvaluateTest.
 *
 * @package testing
 */
final class EvaluateTest extends TestCase
{
    protected EvaluateConstraint $constraint;

    protected function setUp() : void
    {
        $this->constraint = new EvaluateConstraint('foo');
    }

    public function testEvaluate() : void
    {
        self::assertNull($this->constraint->evaluate('foo'));
        self::assertNull($this->constraint->evaluate('foo', 'Description'));
        self::assertTrue($this->constraint->evaluate('foo', 'Description', true));
        try {
            $this->constraint->evaluate('bar');
        } catch (\Exception $exception) {
            self::assertSame(
                "Failed asserting that 'bar' is equals foo.",
                $exception->getMessage()
            );
        }
    }

    public function testEvaluateFloat() : void
    {
        $value = 10.5;
        $constraint = new EvaluateConstraint($value);
        self::assertNull($constraint->evaluate($value));
    }
}
