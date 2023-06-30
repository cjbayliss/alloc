<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();

// apply the PHP version -> PHP-CS-Fixer's style -> Symfony's style ->
// PSR standards -> custom styles. this should give us the most consistant
// result.
return $config->setRules([
    '@PHP74Migration' => true,
    '@PhpCsFixer' => true,
    '@Symfony' => true,
    '@PSR1' => true,
    '@PSR2' => true,
    '@PSR12' => true,
    'binary_operator_spaces' => ['operators' => ['=>' => 'align_single_space_minimal']],
    'concat_space' => ['spacing' => 'one'],
])
    ->setFinder($finder)
;
