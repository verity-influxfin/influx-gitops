<?php

namespace App\Admin\Controllers;

use App\Models\Advertisement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AdvertisementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Advertisement';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Advertisement());

        $grid->column('id', '編號');
        $grid->column('img_url', '圖片路徑');
        $grid->column('type', '類別')->using(['student' => '學生貸', 'office' => '上班族貸', 'enterprise' => '企業貸', 'invest' => '投資', 'house' => '房貸']);
        $grid->column('created_at', '創建時間');
        $grid->column('updated_at', '更新時間');

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
        $show = new Show(Advertisement::findOrFail($id));

        $show->field('id', '編號');
        $show->field('img_url', '圖片路徑');
        $show->field('type', '類別');
        $show->field('created_at', '創建時間');
        $show->field('updated_at', '更新時間');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Advertisement());

        $form->select('type', '文章類型')->options(['student' => '學生貸', 'office' => '上班族貸', 'enterprise' => '企業貸', 'invest' => '投資', 'house' => '房貸'])->required()->default('student');
        $form->image('img_url', '圖片')->required()->move('/upload/advertisement');

        return $form;
    }
}
