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

use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Trait EvaluateTrait.
 *
 * @package testing
 */
trait EvaluateTrait
{
    public function evaluate(
        mixed $other,
        string $description = '',
        bool $returnResult = false
    ) : ?bool {
        $success = $this->evaluateSuccess($other);
        if ($returnResult) {
            return $success;
        }
        if (!$success) {
            $comparison = null;
            if (\is_string($this->value) && \is_string($other)) {
                $comparison = new ComparisonFailure(
                    $this->value,
                    $other,
                    \sprintf("'%s'", $this->value),
                    \sprintf("'%s'", $other)
                );
            }
            $this->fail($other, $description, $comparison);
        }
        return null;
    }

    private function evaluateSuccess(mixed $other) : bool
    {
        if (\is_float($this->value) && \is_float($other) &&
            !\is_infinite($this->value) && !\is_infinite($other) &&
            !\is_nan($this->value) && !\is_nan($other)) {
            return \abs($this->value - $other) < \PHP_FLOAT_EPSILON;
        }
        return $this->value === $other;
    }
}
