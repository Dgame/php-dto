<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);
    $containerConfigurator->import(SetList::PSR_12);
    $parameters->set(Option::SKIP, [
        BinaryOperatorSpacesFixer::class, // So that union types like `int|bool` won't be replaced with `int | bool`
        FunctionDeclarationFixer::class // So that `fn(int $a)` won't be replaced with `fn (int $a)`
    ]);
    $containerConfigurator->import(SetList::CLEAN_CODE);

    $services = $containerConfigurator->services();
    $services->set(StrictParamFixer::class);
    $services->set(DeclareStrictTypesFixer::class);
    $services->set(NoUselessElseFixer::class);
    $services->set(NoUnusedImportsFixer::class);
};
