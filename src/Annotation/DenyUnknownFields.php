<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use InvalidArgumentException;
use Throwable;

#[Attribute(flags: Attribute::TARGET_CLASS)]
final class DenyUnknownFields implements Finalize
{
    /**
     * @param class-string<Throwable>|null $exception
     * @param string|null $message
     */
    public function __construct(private ?string $exception = null, private ?string $message = null)
    {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function finalize(array $input): void
    {
        if ($input === []) {
            return;
        }

        $message = $this->getMessage(array_keys($input));
        if ($this->exception !== null) {
            $exception = new ($this->exception)($message);
            /** @phpstan-ignore-next-line */
            assert($exception instanceof Throwable);

            throw $exception;
        }

        throw new InvalidArgumentException($message);
    }

    /**
     * @param string[] $fields
     *
     * @return string
     */
    private function getMessage(array $fields): string
    {
        return $this->message ?? match (count($fields)) {
            0 => 'Found unexpected fields',
            1 => 'The field "' . implode(', ', $fields) . '" is not expected',
            default => 'The fields "' . implode(', ', $fields) . '" are not expected',
        };
    }
}
