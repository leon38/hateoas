<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$sources = ['src'];

if (is_dir('tests')) {
    $sources[] = 'tests';
}

if (is_dir('config')) {
    $sources[] = 'config';
}

return (new Config())
    ->setRules([
        '@Symfony' => true,
        '@PHP80Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_to_comment' => false,
        'php_unit_method_casing' => false,
        'single_line_throw' => false, // We can't use condition in throws with that rule
        'binary_operator_spaces' => false, // This rule is run against union types, which causes false positives
        'increment_style' => false,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['const', 'class', 'function'],
        ],
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    ])
    ->setFinder(Finder::create()->in($sources));
