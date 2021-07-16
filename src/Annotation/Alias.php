<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Alias
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
