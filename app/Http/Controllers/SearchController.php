<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;
use Exception;

use App\Models\KnowledgeArticle;

class SearchController extends BaseController
{
    /**
     * 全站頁面搜尋
     *
     * @param      \Illuminate\Http\Request  $request  HTTP請求
     *
     * @return     \Illuminate\Http\Response           JSON 回應
     */
    public function page(Request $request)
    {
        try {
            $keyword = $request->input('q');
            $type = strtolower($request->input('type', 'all'));

            // 取得關鍵字, 需大於 1 個字
            if (empty($keyword) || strlen($keyword) < 1) {
                throw new Exception('Invalid keyword.');
            }

            // 小學堂, 常見問題, 所有頁面
            if (! in_array($type, ['blog', 'qa', 'all'])) {
                throw new Exception('Invalid type.');
            }

            switch ($type) {

                // TODO: 小學堂
                // case 'blog':
                //     dd(KnowledgeArticle::search($keyword)
                //         ->minScore(4)
                //         ->select(['id', 'post_title'])
                //         ->get()
                //         ->map(function($row) {
                //         return sprintf('%s - %s', $row['id'], $row['post_title']);
                //     }));
                //     break;

                // 所有頁面: Google Custom Search
                case 'all':
                default:
                    $gcs_engine = new LaravelGoogleCustomSearchEngine();
                    $result = $gcs_engine->getResults($keyword);
                    $retval = [
                        'list' => array_map(function(&$item) {
                            $item = [
                                'title'   => $item->title,
                                'link'    => $item->link,
                                'snippet' => $item->snippet,
                            ];
                            return $item;
                        }, $result),
                        'total_amount' => $gcs_engine->getSearchInformation()->totalResults
                    ];
                    break;

            }

            if (empty($retval)) {
                throw new Exception('No results.');
            }

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => $retval,
        ]);
    }
}
