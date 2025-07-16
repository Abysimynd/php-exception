<?php 

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = new Finder()->in(__DIR__);

$customRules = [
    'braces_position' => [
        'classes_opening_brace' => 'same_line',
        'functions_opening_brace' => 'same_line'
    ],
    'ordered_class_elements' => [
        'order' => [
            'use_trait', 'constant_public', 'constant_protected', 'constant_private','property_public_static', 'property_protected_static', 'property_private_static', 'property_public', 'property_protected', 'property_private', 'construct', 'method_public', 'method_protected', 'method_private', 'method_public_static', 'method_protected_static', 'method_private_static'
        ]
    ],
    'declare_equal_normalize' => [
        'space' => 'single'
    ],
    'concat_space' => [
        'spacing' => 'one'
    ],
    'blank_line_before_statement' => [
        'statements' => [
            'break', 'continue', 'for', 'foreach', 'if', 'return', 'switch', 'throw', 'try', 'while'
        ]
    ],
    'spaces_inside_parentheses' => [
        'space' => 'single'
    ],
    'phpdoc_to_comment' => [
        'ignored_tags' => ['var']
    ]
];

return new Config()
->setRules([
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    ...$customRules
])
->setFinder($finder)
->setCacheFile(__DIR__ . '/dependencies/.php-cs-fixer.cache');