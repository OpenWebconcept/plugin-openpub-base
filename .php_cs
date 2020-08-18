<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('wp-content')
    ->notPath('vendor')
    ->notPath('node_modules')
    ->in(__DIR__)
    ->in('./config')
    ->name('*.php')
    ->notName('*.blade.php');

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2'                  => true,
        'array_syntax'           => [
            'syntax' => 'short',
        ],
        'ordered_imports'        => [
            'sortAlgorithm' => 'alpha',
        ],
        'no_unused_imports'      => true,
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
        'full_opening_tag'       => true,
        'yoda_style'             => [
            'always_move_variable' => true,
            'equal'                => true,
            'identical'            => true,
            'less_and_greater'     => true,
        ],
    ])
    ->setFinder($finder);
