<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Path;
use Dgame\DataTransferObject\DataTransfer;

final class PersonStub
{
    use DataTransfer;

    #[Path('child.{born, age}')]
    public array $firstChild = [];

    #[Path('ancestor.{id, name}')]
    public ?self $parent = null;

    public function __construct(
        #[Path('id')]
        public ?int $id = null,
        #[Path('person.name')]
        public ?string $name = null,
        #[Path('married.$value')]
        public ?bool $married = null,
        #[Path('first.name.#text')]
        public ?string $firstname = null
    ) {
    }
}
