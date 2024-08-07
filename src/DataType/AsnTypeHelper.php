<?php

namespace IMEdge\Snmp\DataType;

use GMP;
use InvalidArgumentException;
use Stringable;

final class AsnTypeHelper
{
    public static function wantGmpIntString(mixed $value): GMP|int|string
    {
        if ($value instanceof GMP || is_int($value) || is_string($value)) {
            return $value;
        }

        throw new InvalidArgumentException('Value must be GMP|int|string');
    }

    public static function wantString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }
        if ($value instanceof Stringable) {
            return (string) $value;
        }

        throw new InvalidArgumentException('Value must be a string');
    }
}
