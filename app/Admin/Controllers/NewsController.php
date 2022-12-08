<?php

namespace App\Admin\Controllers;

use App\Models\News;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{

   use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('最新消息')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('最新消息')
            ->description('檢視')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('最新消息')
            ->description('編輯')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('最新消息')
            ->description('新建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new News);
        $grid->model()->orderBy('pinned', 'desc');
        $grid->model()->orderBy('id', 'desc');

		// 關閉選擇器
		$grid->disableRowSelector();
		$grid->disableExport();
		$grid->disableColumnSelector();
		// $grid->disableFilter();
		// $grid->disableCreateButton();
		// $grid->disableActions();
		//自訂
		$grid->filter(function($filter){
			$filter->disableIdFilter();
				// 在这里添加字段过滤器
                $filter->like('post_title', '標題');
                $filter->equal('isActive','是否上架')->radio([
                    ''   => '全部',
                    'on'    => '是',
                    'off'    => '否',
                ]);

            $filter->equal('pinned', '是否置頂')->radio([
                '' => '全部',
                1 => '是',
                0 => '否',
            ]);
		});
		$grid->actions(function ($actions) {

			//$actions->disableEdit();

			$actions->disableView();

			// $actions->disableDelete();
		});
		/*
		$grid->tools(function ($tools) {
			$switch = $this->nowSwitch();
			$tools->append(new \App\Admin\Extensions\Tools\UserGender(admin_base_path('notice/check_switch'),$switch));
		});*/
        $grid->column('post_title', '標題');
		$grid->column('image_url', '圖片連結')->image('/', 400, 400);
        $grid->column('isActive', '是否呈現')->using(['on' => '是','off'=>'否']);
        $grid->column('pinned', '是否置頂')->using(['<div style="color:red;text-align: center;">否</div>', '<div style="color:blue;text-align: center;">是</div>']);
        $grid->column('release_time', '發布日期')->sortable();
        $grid->column('created_at', '創建日期')->sortable();
        $grid->column('updated_at', '最後更新日期')->sortable();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(News::findOrFail($id));

        $show->panel()->title('詳細資訊 可點列表回上一頁');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new News);

		$form->tools(function (Form\Tools $tools) {
			$tools->disableView();
			$tools->disableDelete();
			/*
			$tools->disableList();
			$tools->disableBackButton();
			$tools->disableListButton();
			*/
		});

		$form->footer(function ($footer) {

			// 去掉`重置`按钮
			//$footer->disableReset();

			// 去掉`提交`按钮
			//$footer->disableSubmit();

			// 去掉`查看`checkbox
			$footer->disableViewCheck();

			// 去掉`继续编辑`checkbox
			$footer->disableEditingCheck();

			// 去掉`继续创建`checkbox
			$footer->disableCreatingCheck();

		});
        $form->text('post_title', '文章標題')->required();
        $form->image('image_url', '圖片')->required()->move('/upload/article')->rules('max:8192',['max'=>'圖片檔案大小不能超過8MB']);
        $form->datetime('release_time','顯示的發佈時間')->format('YYYY-MM-DD HH:mm:ss')->help('若不填寫，則以建立時間為發佈時間');
        $form->ckeditor('post_content','內容');
        $form->switch('isActive', '是否上架')->states([
			'on'  => ['value' => 'on', 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 'off', 'text' => '否', 'color' => 'default'],
		])->default('on');
        $form->switch('pinned', '是否置頂')->states([
            'on' => ['value' => 1, 'text' => '是', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ])->default('off');

        $form->divider();
        $form->html('<h3>SEO 相關設定</h3>');
        $form->text('meta_description', '網頁description')->placeholder('本網頁於搜尋結果的描述；建議文意通順，提綱挈領。');
        $form->text('meta_og_description', 'og:description')->placeholder('本篇文章分享至社交平台的內文預覽；建議文意通順，提綱挈領。');
        $form->text('meta_og_title', 'og:title')->placeholder('本篇文章分享至社交平台的標題；建議中文保持在 30 個字元、英文 60 個字元以內。');
        $form->text('meta_og_image', 'og:image')->placeholder('本篇文章分享至社交平台時可見的縮圖。預設為本文第一張圖。');
        $form->saving(function (Form $form) {
            if(empty(dump($form->release_time))){
                $form->release_time = date('Y-m-d H:i:s');
            }
        });
        return $form;
    }

    public function upload(Request $request)
    {
        $image = $request->file('upload'); // get file

        // response
        $param = [
                'uploaded' => 1,
                'fileName' => 'fileName',
                'url' => 'url'
        ];
        return response()->json($param, 200);
    }
}
