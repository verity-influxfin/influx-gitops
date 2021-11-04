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
            // 關鍵字, 需大於 1 個字
            $keyword = $request->input('q');
            if (empty($keyword) || strlen($keyword) < 1) {
                throw new Exception('Invalid keyword.');
            }

            // 搜尋類型：小學堂, 常見問題, 所有頁面
            $type = strtolower($request->input('type', 'all'));
            if (! in_array($type, ['blog', 'qa', 'all'])) {
                throw new Exception('Invalid type.');
            }

            // 每頁數量
            $page_size = (int) $request->input('perPage', 10);
            if ($page_size > 10) {
                throw new Exception('perPage MUST less than 10.');
            }

            // 目前頁面
            $current_page = (int) $request->input('currentPage', 1);

            // 總數不能大於 100 筆
            $start = 1 + ($page_size * ($current_page > 1 ?: 0));
            if ($start > 100) {
                throw new Exception('Invalid page value.');
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
                    $result = $gcs_engine->getResults($keyword, [
                        'num'   => $page_size,
                        'start' => $start,
                    ]);
                    $retval = [
                        'list' => array_map(function(&$item) {
                            $item = [
                                'title'   => $item->title,
                                'link'    => $item->link,
                                'snippet' => $item->snippet,
                            ];
                            return $item;
                        }, $result),
                        'pagination' => [
                            'lastPage'    => (int) $gcs_engine->getTotalNumberOfpages(),
                            'currentPage' => $current_page,
                            'total'       => (int) $gcs_engine->getSearchInformation()->totalResults,
                            'perPage'     => $page_size,
                        ]
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
