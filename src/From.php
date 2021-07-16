<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject;

use ReflectionException;
use Throwable;

trait From
{
    /**
     * @throws ReflectionException
     * @throws Throwable
     */
    public static function from(array $input): static
    {
        $dto = new DataTransferObject(static::class);
        $dto->from($input);
        $dto->finalize($input);

        return $dto->getInstance();
    }
}
