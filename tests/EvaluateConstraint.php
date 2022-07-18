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

use Framework\Testing\Constraints\EvaluateTrait;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class EvaluateConstraint.
 *
 * @package testing
 */
class EvaluateConstraint extends Constraint
{
    use EvaluateTrait;

    protected mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function toString() : string
    {
        return 'is equals ' . $this->value;
    }
}
