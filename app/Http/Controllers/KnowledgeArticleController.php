<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeArticle;
use Illuminate\Http\Request;

class KnowledgeArticleController extends Controller
{
    public function get_meta_info($id): array
    {
        $knowledge_info = KnowledgeArticle::select(
            [
                'media_link',
                'post_title',
                'post_content',
                'web_title',
                'meta_description',
                'meta_og_description',
                'meta_og_title',
                'meta_og_image'
            ])
            ->where('id', $id)
            ->get()
            ->first();

        $result = [
            'meta_description' => $knowledge_info['meta_description'],
            'meta_og_description' => $knowledge_info['meta_og_description'],
            'web_title' => !empty($knowledge_info['web_title']) ? $knowledge_info['web_title'] : $knowledge_info['title'],
            'meta_og_title' => !empty($knowledge_info['meta_og_title']) ? $knowledge_info['meta_og_title'] : $knowledge_info['title'],
            'meta_og_image' => !empty($knowledge_info['meta_og_image']) ? $knowledge_info['meta_og_image'] : asset($knowledge_info['media_link'])
        ];

        if (!empty($knowledge_info['post_content'])) {
            $substr_post_content = substr(
                    preg_replace("/&.+;|\r\n|\r|\n/m", '',
                        preg_replace("/(<([^>]+)>)/i", '', trim($knowledge_info['post_content']))
                    ),
                    0, 140
                ) . '...';

            $result['meta_description'] = !empty($result['meta_description']) ? $result['meta_description'] : $substr_post_content;
            $result['meta_og_description'] = !empty($result['meta_og_description']) ? $result['meta_og_description'] : $substr_post_content;
        }

        return $result;
    }
}
