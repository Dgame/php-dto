<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class ParentType extends ObjectType
{
    public function __construct()
    {
        parent::__construct('parent');
    }
}
