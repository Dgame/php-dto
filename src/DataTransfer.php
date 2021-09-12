<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject;

use ReflectionException;
use Throwable;

trait DataTransfer
{
    /**
     * @throws ReflectionException
     * @throws Throwable
     */
    public static function from(array $input): static
    {
        $dto = new DataTransferObject(static::class);
        $dto->from($input);

        return $dto->getInstance();
    }
}
