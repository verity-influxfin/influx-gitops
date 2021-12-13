<?php

namespace App\Admin\Controllers;

use App\Models\Partner;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
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
            ->header('合作夥伴')
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
            ->header('合作夥伴')
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
            ->header('合作夥伴')
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
            ->header('合作夥伴')
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
        $grid = new Grid(new Partner);

        $grid->model()->orderBy('order', 'asc');

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
                $filter->like('name', '名稱');
                $filter->like('title', '標題');
                $filter->equal('type','單位類型')->radio([
                    ''   => '全部',
                    'society' => '不知道是啥的分類，看到請告訴我打啥',
                    'edu'=>'大學院校'
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
        $grid->column('order', '排序')->sortable();
        $grid->column('name', '單位名稱');
        $grid->column('title', '標題');
        $grid->column('link', '網站連結');
		$grid->column('imageSrc', '圖片連結')->image('/', 400, 400);
        $grid->column('type', '單位類型')->using(['edu'=>'大學院校','society'=>'不知道是啥的分類，看到請告訴我打啥']);
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
        $show = new Show(Partner::findOrFail($id));

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
        $form = new Form(new Partner);

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
        $form->text('name', '單位名稱')->required();
        $form->text('title', '標題')->required();
        $form->url('link', '網站連結');
        $form->image('imageSrc', '圖片連結')->required()->move('/upload/partner')->rules('max:8192',['max'=>'圖片檔案大小不能超過8MB']);
        $form->select('type', '單位類型')->options(['edu'=>'大學院校','society'=>'不知道是啥的分類，看到請告訴我打啥'])->required();
        $form->textarea('text', '說明');

        // 找最後排序數字
        $new_default_last_order = 1;
        $all_order = Partner::all()->pluck(
          'order',
          'id'
        );
        if(! empty($all_order)){
            $new_default_last_order = is_numeric(max($all_order->all())) ? max($all_order->all()) + 1 : 1;
        }

        $form->number('order', '排序')->required()->min(1)->default($new_default_last_order)->rules(function ($form) {
			if ($id = $form->model()->id) {
				return "unique:partner,order,$id,id";
			}
			if (!$id = $form->model()->id) {
				return 'unique:partner,order';
			}
		},['警告:已有相同排序，請重新設定']);
        return $form;
    }
}
