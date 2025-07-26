<?php 

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = new Finder()->in(__DIR__);

$customRules = [
    'braces_position' => [
        'classes_opening_brace' => 'same_line',
        'functions_opening_brace' => 'same_line'
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
->setFinder($finder);