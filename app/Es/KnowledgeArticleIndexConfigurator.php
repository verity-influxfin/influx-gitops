<?php

namespace App\Es;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class KnowledgeArticleIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $name = 'knowledge_article';

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'tokenizer' => [
                'custom_thulac_tokenizer' => [
                    'type'   => 'thulac',
                    't2s'    => false,
                    'filter' => false
                ]
            ],
            'analyzer' => [
                'custom_thulac_analyzer' => [
                    'tokenizer' => 'custom_thulac_tokenizer',
                    'filter'    => [ 'lowercase' ]
                ]
            ]
        ]
    ];
}