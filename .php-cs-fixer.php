<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('node_modules')
    ->notPath('vendor')
    ->notPath('wp-content')
    ->in(__DIR__)
    ->in('./config')
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config)
    ->setRules([
        '@PSR2'                  => true,
        'array_syntax'           => [
            'syntax' => 'short',
        ],
        'ordered_imports'        => [
            'sort_algorithm' => 'alpha',
        ],
        'no_unused_imports'      => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=>' => null,
                '|' => 'no_space',
            ]
        ],
        'full_opening_tag'       => true,
    ])
    ->setFinder($finder);
