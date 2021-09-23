<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
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
            ->header('Banner')
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
            ->header('Banner')
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
            ->header('Banner')
            ->description('编辑')
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
            ->header('Banner')
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
        $grid = new Grid(new Banner);

        $grid->model()->orderBy('id', 'desc');

		// 關閉選擇器
		$grid->disableRowSelector();
		$grid->disableExport();
		$grid->disableColumnSelector();
		// $grid->disableCreateButton();
		// $grid->disableActions();
		// 關閉搜尋
		//$grid->disableFilter();
		//自訂
		$grid->filter(function($filter){
			$filter->disableIdFilter();
				// 在这里添加字段过滤器
                $filter->equal('type','頁面位置')->radio([
                    ''   => '全部',
                    'index' => '首頁',
                    'freshGraduate' => '上班族貸款',
                    'college'=>'學生貸款',
                    'engineer'=>'資訊工程師專案',
                    'invest'=>'債權投資',
                    'transfer'=>'債權轉讓'
                ]);
                $filter->equal('isActive','是否上架')->radio([
                    ''   => '全部',
                    'on'    => '是',
                    'off'    => '否',
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
        $grid->column('type', '頁面')->using(['index' => '首頁', 'freshGraduate' => '上班族貸款','college'=>'學生貸款','engineer'=>'資訊工程師專案','invest'=>'債權投資','transfer'=>'債權轉讓']);
		$grid->column('desktop', '網頁版圖片')->image('/', 400, 400);
        $grid->column('mobile', '手機版圖片')->image('/', 400, 400);
		$grid->column('link', '連結')->editable()->qrcode();
        $grid->column('isActive', '是否上架')->using(['on'=>'<div style="color:blue;text-align: center;">是</div>','off'=>'<div style="color:red;text-align: center;">否</div>']);
        $grid->column('created_at','創建時間')->sortable();
        $grid->column('updated_at','最後更新時間')->sortable();
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
        $show = new Show(Banner::findOrFail($id));

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
        $form = new Form(new Banner);

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
        $form->select('type', '頁面')->options(['index' => '首頁'])->required()->default('index');
        $form->image('desktop', '網頁版圖片')->required()->move('/upload/banner')->rules('max:8192',['max'=>'圖片檔案大小不能超過8MB']);
        $form->image('mobile', '手機版圖片')->required()->move('/upload/banner')->rules('max:8192',['max'=>'圖片檔案大小不能超過8MB']);
        $form->url('link','連結');
		$form->switch('isActive', '是否上架')->states([
			'on'  => ['value' => 'on', 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 'off', 'text' => '否', 'color' => 'default'],
		])->default('on');
        return $form;
    }
}
