<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use InvalidArgumentException;
use Safe\Exceptions\PcreException;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Path
{
    private const SEPARATOR = '.';
    private const WILDCARD = '*';

    private int|string|null $key = null;

    public function __construct(private string $path)
    {
    }

    public function getKey(): int|string|null
    {
        return $this->key;
    }

    /**
     * @throws PcreException
     */
    public function extract(mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        return $this->get($this->path, $value);
    }

    /**
     * @param string $path
     * @param array<string, mixed> $input
     *
     * @return mixed
     * @throws PcreException
     */
    private function get(string $path, array $input): mixed
    {
        if ($input === []) {
            return null;
        }

        $pathSteps = $this->split($path);
        if ($this->key === null) {
            $this->key = key($pathSteps);
        }

        $output = $input;
        foreach ($pathSteps as $i => $step) {
            // Wildcard
            if ($step === self::WILDCARD) {
                $stepsLeft = array_slice($pathSteps, $i + 1);
                if (count($stepsLeft) === 0) {
                    return $output;
                }

                $nestedPath = $this->assemble($stepsLeft);
                $results = [];
                foreach ($output as $value) {
                    $results[] = $this->get($nestedPath, $value);
                }

                return $results;
            }

            // Multiselect
            if (\Safe\preg_match('/^{(.*?)}$/S', $step, $matches) === 1) {
                [, $subSteps] = $matches;

                $results = [];
                foreach (explode(',', $subSteps) as $subStep) {
                    $subStep = trim($subStep);
                    $results[$subStep] = $this->get($subStep, $output);
                }

                return $results;
            }

            if (!array_key_exists($step, $output)) {
                return null;
            }

            $output = $output[$step];
        }

        return $output;
    }

    /**
     * @param string $path
     *
     * @return string[]
     */
    private function split(string $path): array
    {
        if ($path === '') {
            throw new InvalidArgumentException('Path can\'t be empty.');
        }

        if (str_contains($path, '{')) {
            if (!str_contains($path, '}')) {
                throw new InvalidArgumentException('Multimatch syntax not closed');
            }

            if (strpos($path, '}') !== strlen($path) - 1) {
                throw new InvalidArgumentException('Multimatch must be used at the end of path');
            }
        }

        if (str_starts_with($path, '{') && str_contains($path, '}')) {
            return [$path];
        }

        return explode(self::SEPARATOR, $path);
    }

    /**
     * @param string[] $steps
     *
     * @return string
     */
    private function assemble(array $steps): string
    {
        return implode(self::SEPARATOR, $steps);
    }
}
