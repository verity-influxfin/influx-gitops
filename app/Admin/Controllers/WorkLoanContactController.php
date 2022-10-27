<?php

namespace App\Admin\Controllers;

use App\Models\WorkLoanContact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WorkLoanContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '上班族貸 專人溫馨服務';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WorkLoanContact());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('phone', __('Phone'));
        $grid->column('gender', __('Gender'))->using(['1' => '男', '2' => '女']);
        $grid->column('line', __('Line'));
        $grid->column('email', __('Email'));
        $grid->column('reason', __('Reason'));
        $grid->column('contact_time', __('Contact time'))->using(['1' => '隨時', '2' => '上午', '3' => '下午']);
        $grid->column('created_at', __('Created at'));

        $grid->disableCreateButton();

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
        $show = new Show(WorkLoanContact::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('gender', __('Gender'));
        $show->field('line', __('Line'));
        $show->field('email', __('Email'));
        $show->field('reason', __('Reason'));
        $show->field('contact_time', __('Contact time'));
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
        $form = new Form(new WorkLoanContact());

        $form->text('name', __('Name'));
        $form->mobile('phone', __('Phone'));
        $form->switch('gender', __('Gender'));
        $form->text('line', __('Line'));
        $form->email('email', __('Email'));
        $form->textarea('reason', __('Reason'));
        $form->switch('contact_time', __('Contact time'));
        $form->text('created_ip', __('Created ip'));

        return $form;
    }
}
