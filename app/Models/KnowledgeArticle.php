<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use App\Es\KnowledgeArticleIndexConfigurator;
use ScoutElastic\Searchable;

class KnowledgeArticle extends Model
{

    use Searchable;

    protected $table = 'knowledge_article';
    protected $primaryKey = 'id';

    protected $indexConfigurator = KnowledgeArticleIndexConfigurator::class;

    protected $mapping = [
        'properties' => [
            'post_title' => [
                'type'     => 'text',
                'analyzer' => 'custom_thulac_analyzer'
            ],
            'post_content' => [
                'type'     => 'text',
                'analyzer' => 'custom_thulac_analyzer'
            ],
        ]
    ];

    protected $fillable = [
        'post_author',
        'post_modified',
        'post_title',
        'post_content',
        'status',
        'type',
        'order',
        'media_link',
        'video_link',
        'post_date'
    ];

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {
        return $this->id;
    }

    /**
     * Get the key name used to index the model.
     *
     * @return mixed
     */
    public function getScoutKeyName()
    {
        return 'id';
    }

    /**
     * Returns a searchable array representation of the object.
     *
     * @return     array  Searchable array representation of the object.
     */
    public function toSearchableArray()
    {
        return [
            'post_title'   => $this->post_title,
            'post_content' => strip_tags($this->post_content),
        ];
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
