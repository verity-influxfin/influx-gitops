<?php

namespace App\Admin\Controllers;

use App\Models\EventUsers;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Widgets\Table;

class EventUsersController extends Controller
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
            ->header('註冊資訊')
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
            ->header('註冊資訊')
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
            ->header('註冊資訊')
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
            ->header('註冊資訊')
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
        $grid = new Grid(new EventUsers);

        $grid->model()->orderBy('created_at', 'desc');

		// 關閉選擇器
		$grid->disableRowSelector();
		// $grid->disableExport();
		// $grid->disableColumnSelector();
		// $grid->disableFilter();
		$grid->disableCreateButton();
		$grid->disableActions();
		//自訂
		$grid->filter(function($filter){
			$filter->disableIdFilter();
				// 在这里添加字段过滤器
			/*
				$filter->group('play_count', '可游玩次数', function ($group) {
					$group->equal('等于');
					$group->notEqual('不等于');
					$group->gt('大于');
					$group->lt('小于');
					$group->nlt('大于等于');
					$group->ngt('小于等于');
				});
			*/
            $filter->like('promo', '推薦碼');
            $filter->where(function ($query) {

                $query->whereRaw(" JSON_EXTRACT(`promo_info`,'$.nick_name') = '{$this->input}'");

            }, '推薦人暱稱');
            $filter->where(function ($query) {

                $query->whereRaw(" JSON_EXTRACT(`promo_info`,'$.name') = '{$this->input}'");

            }, '推薦人姓名');
		});
		// 關閉搜尋
		//$grid->disableFilter();
		$grid->actions(function ($actions) {

			$actions->disableEdit();

			$actions->disableView();

			$actions->disableDelete();
		});
		/*
		$grid->tools(function ($tools) {
			$switch = $this->nowSwitch();
			$tools->append(new \App\Admin\Extensions\Tools\UserGender(admin_base_path('notice/check_switch'),$switch));
		});*/
        $grid->export(function ($export) {
            $export->only(['phone', 'promo', 'email', 'nick_name', 'name', 'created_at']);
        });
		$grid->column('phone', '註冊電話');
        $grid->column('promo', '推薦碼');
        $grid->column('email', '信箱');
        $grid->column('created_ip', '註冊IP位置');
        $grid->column('nick_name', '推薦人暱稱')->display(function () {
            return isset(json_decode($this->promo_info)->nick_name) ? json_decode($this->promo_info)->nick_name : '';
        });
        $grid->column('name', '推薦人姓名')->display(function () {
            return isset(json_decode($this->promo_info)->name) ? json_decode($this->promo_info)->name : '';
        });
        $grid->column('created_at', '創建時間')->sortable();
        $grid->column('updated_at', '更新時間')->sortable();

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
        $show = new Show(EventUsers::findOrFail($id));

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
        $form = new Form(new EventUsers);

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
        $form->number('transactionEventUsers', '累積成交筆數')->attribute(['style' => 'width:40vw;'])->required()->default(0);
        $form->number('memberEventUsers', '累積註冊用戶')->attribute(['style' => 'width:40vw;'])->required()->default(0);
        $form->number('totalLoanAmount', '累積放款金額')->attribute(['style' => 'width:40vw;'])->required()->default(0);
        return $form;
    }
}
