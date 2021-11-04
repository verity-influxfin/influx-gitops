<?php

namespace App\Admin\Controllers;

use App\Models\EventCampusMember;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use App\Helpers\MappingData;

class EventCampusMemberController extends Controller
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
            ->header('校園大使隊伍成員')
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
            ->header('校園大使隊伍成員')
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
            ->header('校園大使隊伍成員')
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
            ->header('校園大使隊伍成員')
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
        $grid = new Grid(new EventCampusMember);

        $grid->model()->orderBy('updated_at', 'desc');

		// 關閉選擇器
		$grid->disableRowSelector();
		$grid->disableExport();
		$grid->disableColumnSelector();
		// $grid->disableFilter();
		$grid->disableCreateButton();
		// $grid->disableActions();
		//自訂
		$grid->filter(function($filter){
			$filter->disableIdFilter();
				// 在这里添加字段过滤器
				$filter->like('school', '學校名稱');
                $filter->like('name', '隊伍名稱');
		});
		// 關閉搜尋
		//$grid->disableFilter();
		$grid->actions(function ($actions) {

			$actions->disableEdit();

			// $actions->disableView();

			$actions->disableDelete();
		});
		/*
		$grid->tools(function ($tools) {
			$switch = $this->nowSwitch();
			$tools->append(new \App\Admin\Extensions\Tools\UserGender(admin_base_path('notice/check_switch'),$switch));
		});*/
        $MappingData = new MappingData();
        $team_list = $MappingData->getEventTeams();
        // print_r($team_list);exit;
        $grid->column('team_id', '團隊名稱')->using($team_list);
        $grid->column('name', '姓名');
        $grid->column('school', '學校');
        $grid->column('dept', '科系');
		$grid->column('grade', '學位年級');
        $grid->column('created_at', '創建時間');
        $grid->column('updated_at', '最後更新時間');

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
        $show = new Show(EventCampusMember::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            // $tools->disableList();
            $tools->disableDelete();
        });

        $MappingData = new MappingData();
        $team_list = $MappingData->getEventTeams();

        $show->field('team_id', '團隊名稱')->using($team_list);
        $show->field('name', '姓名');
        $show->field('school', '學校');
        $show->field('dept', '科系');
		$show->field('grade', '學位年級');
        $show->field('mobile', '手機');
        $show->field('email', 'Email');
        $show->field('fb_link', '個人臉書連結')->link();
        $show->field('ig_link', '個人IG連結')->link();
        $show->field('self_intro', '自我介紹');
        $show->field('resume', '個人履歷')->file('/',true);
        $show->field('motivation', '報名動機');
        $show->field('bonus', '其他加分項');
        $show->field('portfolio', '個人作品集')->file('/',true);
        $show->field('created_at', '創建時間');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new EventCampusMember);

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
        $form->text('school', '學校名稱')->required();
        $form->text('name', '團隊名稱')->required();
        $form->textarea('intro', '團隊介紹');
        return $form;
    }
}
