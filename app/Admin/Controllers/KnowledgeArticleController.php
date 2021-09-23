<?php

namespace App\Admin\Controllers;

use App\Models\KnowledgeArticle;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class KnowledgeArticleController extends Controller
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
            ->header('小學堂')
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
            ->header('小學堂')
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
            ->header('小學堂')
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
            ->header('小學堂')
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
        $grid = new Grid(new KnowledgeArticle);

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
                $filter->equal('isActive','是否上架')->radio([
                    ''   => '全部',
                    'on'    => '是',
                    'off'    => '否',
                ]);
                $filter->equal('type','文章類型')->radio([
                    ''   => '全部',
                    'article' => '小學堂',
                    'video'=>'小學堂影音',
                    'investtonic'=>'投資理財大補帖'
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
		$grid->column('media_link', '圖片連結')->image('/', 400, 400);
        $grid->column('type', '文章類型')->using(['article' => '小學堂','video'=>'小學堂影音','investtonic'=>'投資理財大補帖']);
        $grid->column('isActive', '是否呈現')->using(['on' => '是','off'=>'否']);
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
        $show = new Show(KnowledgeArticle::findOrFail($id));

        $show->setting_id('Setting id');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new KnowledgeArticle);

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
        $form->text('post_title', '標題')->required();
        $form->select('type', '文章類型')->options(['article' => '小學堂','video'=>'小學堂影音','investtonic'=>'投資理財大補帖'])->required()->default('article');
        $form->image('media_link', '圖片')->required()->move('/upload/article')->rules('max:8192',['max'=>'圖片檔案大小不能超過8MB']);
        $form->url('video_link', '影片連結')->help('文章類型為小學堂影音再填寫');
        $form->ckeditor('post_content','內容');
        $form->switch('isActive', '是否上架')->states([
			'on'  => ['value' => 'on', 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 'off', 'text' => '否', 'color' => 'default'],
		])->default('on');
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
