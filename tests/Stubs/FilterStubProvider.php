<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

/**
 * Class FilterStubProvider
 * @package Dgame\DataTransferObject\Tests\Stubs
 */
final class FilterStubProvider
{
    public function __invoke(array $input): array
    {
        $output = [];
        foreach ($input as $key => $value) {
            $output[$key] = base64_encode((string) $value);
        }

        return $output;
    }

    public function toInt(int|string $id): int
    {
        return (int) $id;
    }
}
