<?php

use Symfony\Component\Yaml\Yaml;
use phpDocumentor\Reflection\DocBlock\Tag;

$loader = require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/DocBuilder.php';

Tag::registerTagHandler('type', '\SignatureTag');
$docBuilder = new DocBuilder($loader, __DIR__ . '/../../docs/');

$mkdocsYaml = [
    'site_name' => 'Vector',
    'site_description' => 'Functional primitives for PHP',
    'site_author' => 'Joseph Walker',
    'site_url' => 'https://packagist.org/packages/vector/core',
    'repo_name' => 'GitHub',
    'repo_url' => 'https://github.com/joseph-walker/vector',
    'copyright' => 'Copyright (c) 2016 Joseph Walker',
    'theme' => 'material',
    'markdown_extensions' => ['admonition'],
    'pages' => [
        [
            'Introduction' => 'index.md'
        ],
        [
            'User Guide' => [
                ['Functional Basics' => 'user-guide/basics.md'],
                ['The Function Capsule' => 'user-guide/capsule.md']
            ]
        ],
        [
            'API Reference' => [
                [
                    'Data' => [
                        ['Maybe' => 'api-reference/maybe.md'],
                        ['Either' => 'api-reference/either.md']
                    ]
                ],
                ['Lib' => $docBuilder->apiDoc('Lib')],
                ['Control' => $docBuilder->apiDoc('Control')]
            ]
        ]
    ]
];

file_put_contents(__DIR__ . '/../../mkdocs.yml', Yaml::dump($mkdocsYaml, 8));