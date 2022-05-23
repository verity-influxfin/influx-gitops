<?php

namespace App\Admin\Controllers;

use App\Models\Campaign2022_add;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class Campaign2022Controller extends Controller
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
            ->header('五週年活動灌票')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('五週年活動灌票')
            ->description('檢視')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('五週年活動灌票')
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
            ->header('五週年活動灌票')
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
        $grid = new Grid(new Campaign2022_add);

        $grid->model()->orderBy('id', 'desc');

        // 關閉選擇器
        $grid->disableRowSelector();
        $grid->disableExport();
        $grid->disableColumnSelector();
        $grid->disableFilter();
        // $grid->disableCreateButton();
        $grid->disableActions();

        $grid->actions(function ($actions) {

            $actions->disableEdit();

            $actions->disableView();

            $actions->disableDelete();
        });

        $grid->column('campaign2022s_id', '活動作品id');
        $grid->column('votes', '票數');
        $grid->column('created_at', '創建日期')->sortable();
        $grid->column('updated_at', '最後更新日期')->sortable();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Campaign2022_add::findOrFail($id));

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
        $form = new Form(new Campaign2022_add);

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

            // 去掉`送出`按钮
            //$footer->disableSubmit();

            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`繼續編輯`checkbox
            $footer->disableEditingCheck();

            // 去掉`繼續新增`checkbox
            $footer->disableCreatingCheck();

        });
        $form->number('campaign2022s_id', '活動作品id')->required()->default(18)->rules('integer', ['integer' => '請輸入正確的作品id']);
        $form->number('votes', '票數')->required()->rules('min:0|integer', ['min' => '請輸入大於0的整數', 'integer' => '請輸入整數']);
        return $form;
    }
}
