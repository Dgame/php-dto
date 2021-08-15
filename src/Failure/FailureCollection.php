<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Failure;

class FailureCollection
{
    protected const PATH = '{path}';

    /**
     * @var string[]
     */
    private array $path = [];
    /**
     * @var string[]
     */
    private array $failures = [];

    public function pushPath(string $path): void
    {
        $this->path[] = $path;
    }

    public function popPath(): ?string
    {
        return array_pop($this->path);
    }

    public function setFailure(string $failure): void
    {
        $this->failures[] = strtr($failure, [self::PATH => implode('.', $this->path)]);
    }

    public function hasFailures(): bool
    {
        return $this->failures !== [];
    }

    /**
     * @return string[]
     */
    public function getFailures(): array
    {
        return $this->failures;
    }
}
