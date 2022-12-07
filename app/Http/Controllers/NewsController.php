<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function get_meta_info($id): array
    {
        $news_info = News::select(
            [
                'image_url',
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
            'meta_description' => $news_info['meta_description'],
            'meta_og_description' => $news_info['meta_og_description'],
            'web_title' => !empty($news_info['web_title']) ? $news_info['web_title'] : $news_info['post_title'],
            'meta_og_title' => !empty($news_info['meta_og_title']) ? $news_info['meta_og_title'] : $news_info['post_title'],
            'meta_og_image' => !empty($news_info['meta_og_image']) ? $news_info['meta_og_image'] : asset($news_info['img_url'])
        ];

        if (!empty($news_info['post_content'])) {
            $substr_post_content = mb_substr(
                    preg_replace("/&.+;|\r\n|\r|\n/m", '',
                        preg_replace("/(<([^>]+)>)/i", '', trim($news_info['post_content']))
                    ),
                    0, 140
                ) . '...';

            $result['meta_description'] = !empty($result['meta_description']) ? $result['meta_description'] : $substr_post_content;
            $result['meta_og_description'] = !empty($result['meta_og_description']) ? $result['meta_og_description'] : $substr_post_content;
        }

        return $result;
    }
    public function get_news($id)
    {
        return DB::table('news')->select('*', DB::raw('CASE WHEN release_time IS NULL THEN created_at ELSE release_time END AS post_date'))->where('id', '=', $id)->first();
    }
}
