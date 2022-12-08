<?php

namespace App\Admin\Controllers;

use App\Models\RouteMeta;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class RouteMetaController extends Controller
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
            ->header('SEO Meta')
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
            ->header('SEO Meta')
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
            ->header('SEO Meta')
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
            ->header('SEO Meta')
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
        $grid = new Grid(new RouteMeta);

        $grid->model()->orderBy('id', 'desc');

        // 關閉選擇器
        $grid->disableRowSelector();
        $grid->disableExport();
        $grid->disableColumnSelector();
        // $grid->disableFilter();
        // $grid->disableCreateButton();
        // $grid->disableActions();
        $grid->actions(function ($actions) {

            //$actions->disableEdit();

            $actions->disableView();

            // $actions->disableDelete();
        });
        $grid->column('route_path', '路徑');
        $grid->column('web_title', '網頁title');
        $grid->column('meta_og_image', 'og:image')->image('/', 400, 400);
        $grid->column('meta_og_title', 'og:title');
        $grid->column('meta_og_description', 'og:description');
        $grid->column('meta_description', 'meta:description');
        $grid->column('created_at', '創建日期')->sortable();
        $grid->column('updated_at', '最後更新日期')->sortable();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed  $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(RouteMeta::findOrFail($id));

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
        $form = new Form(new RouteMeta);

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
        $form->text('route_path', '路徑')->placeholder('網頁路徑')->help('如路徑為 https://www.influxfin.com/investment 只須填寫 investment 不需要 "\"')->rules('required');
        $form->text('web_title', '網頁title')->placeholder('網頁標題；建議中文保持在 30 個字元、英文 60 個字元以內。');
        $form->text('meta_description', '網頁description')->placeholder('本網頁於搜尋結果的描述；建議文意通順，提綱挈領。');
        $form->text('meta_og_description', 'og:description')->placeholder('本篇文章分享至社交平台的內文預覽；建議文意通順，提綱挈領。');
        $form->text('meta_og_title', 'og:title')->placeholder('本篇文章分享至社交平台的標題；建議中文保持在 30 個字元、英文 60 個字元以內。');
        $form->image('meta_og_image', 'og:image')->help('本篇文章分享至社交平台時可見的縮圖。')->move('/upload/meta_image')->rules('max:8192',['max'=>'圖片檔案大小不能超過8MB']);

        return $form;
    }
}
