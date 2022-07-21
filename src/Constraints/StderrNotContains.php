<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Testing Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Testing\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Class StderrNotContains.
 *
 * @package testing
 */
final class StderrNotContains extends Constraint
{
    private string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    protected function fail($other, $description, ComparisonFailure $comparisonFailure = null) : void
    {
        $failureDescription = \sprintf(
            'Failed asserting that STDERR %s',
            $this->failureDescription($other)
        );
        if ( ! empty($description)) {
            $failureDescription = $description . "\n" . $failureDescription;
        }
        throw new ExpectationFailedException(
            $failureDescription,
            $comparisonFailure
        );
    }

    public function toString() : string
    {
        return \sprintf(
            "does not contain '%s'.",
            $this->string
        );
    }

    protected function matches(mixed $other) : bool
    {
        return ! \str_contains($other, $this->string);
    }
}
