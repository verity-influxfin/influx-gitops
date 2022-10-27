<?php

namespace App\Admin\Controllers;

use App\Models\WorkLoanShare;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WorkLoanShareController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '上班族貸 上傳分享';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WorkLoanShare());
        $grid->model()->orderBy('id', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('experience', __('Experience'));
        $grid->column('created_at', __('Created at'));

        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableActions();

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
        $show = new Show(WorkLoanShare::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('experience', __('Experience'));
        $show->field('created_ip', __('Created ip'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WorkLoanShare());

        $form->number('user_id', __('User id'));
        $form->textarea('experience', __('Experience'));
        $form->text('created_ip', __('Created ip'));

        return $form;
    }
}
