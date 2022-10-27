<?php

namespace App\Admin\Controllers;

use App\Models\BusinessLoanContact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BusinessLoanContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '企業融資 需要專人服務嗎';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BusinessLoanContact());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('gender', __('Gender'));
        $grid->column('company_name', __('Company name'));
        $grid->column('email', __('Email'));
        $grid->column('contact_time', __('Contact time'));
        $grid->column('needs', __('Needs'));
        $grid->column('reason', __('Reason'));
        $grid->column('created_ip', __('Created ip'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
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
        $show = new Show(BusinessLoanContact::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('gender', __('Gender'))->using(['1' => '男', '2' => '女']);
        $show->field('company_name', __('Company name'));
        $show->field('email', __('Email'));
        $show->field('contact_time', __('Contact time'))->using(['1' => '隨時', '2' => '上午', '3' => '下午']);;
        $show->field('needs', __('Needs'));
        $show->field('reason', __('Reason'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BusinessLoanContact());

        $form->text('name', __('Name'));
        $form->switch('gender', __('Gender'));
        $form->text('company_name', __('Company name'));
        $form->email('email', __('Email'));
        $form->switch('contact_time', __('Contact time'));
        $form->text('needs', __('Needs'));
        $form->textarea('reason', __('Reason'));
        $form->text('created_ip', __('Created ip'));

        return $form;
    }
}
