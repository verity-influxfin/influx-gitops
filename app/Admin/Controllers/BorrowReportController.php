<?php

namespace App\Admin\Controllers;

use App\Models\BorrowReport;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BorrowReportController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '學生貸 試算你的普匯額度';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BorrowReport());
        $grid->model()->orderBy('id', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('identity',__('身份'))->using(['1' => '學生', '2' => '上班族']);
        $grid->column('name', __('Name'));
        // $grid->column('educational_level', __('Educational level'));
        // $grid->column('is_top_enterprises', __('Is top enterprises'));
        // $grid->column('insurance_salary', __('Insurance salary'));
        // $grid->column('debt_amount', __('Debt amount'));
        // $grid->column('monthly_repayment', __('Monthly repayment'));
        // $grid->column('creditcard_quota', __('Creditcard quota'));
        // $grid->column('creditcard_bill', __('Creditcard bill'));
        // $grid->column('school_name', __('School name'));
        // $grid->column('department', __('Department'));
        // $grid->column('is_student_loan', __('Is student loan'));
        // $grid->column('is_part_time_job', __('Is part time job'));
        // $grid->column('monthly_economy', __('Monthly economy'));
        $grid->column('phone', __('Phone'));
        $grid->column('line', __('Line'));
        $grid->column('reason', __('Reason'));
        $grid->column('is_contact', __('Is contact'));
        $grid->column('contact_time', __('Contact time'));
        // $grid->column('amount', __('Amount'));
        // $grid->column('rate', __('Rate'));
        // $grid->column('platform_fee', __('Platform fee'));
        // $grid->column('repayment', __('Repayment'));
        // $grid->column('total_point', __('Total point'));
        $grid->column('created_at', __('Created at'));
        $grid->column('email', __('Email'));
        // $grid->column('job', __('Job'));
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
        $show = new Show(BorrowReport::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('identity', __('Identity'));
        $show->field('name', __('Name'));
        $show->field('educational_level', __('Educational level'));
        $show->field('is_top_enterprises', __('Is top enterprises'));
        $show->field('insurance_salary', __('Insurance salary'));
        $show->field('debt_amount', __('Debt amount'));
        $show->field('monthly_repayment', __('Monthly repayment'));
        $show->field('creditcard_quota', __('Creditcard quota'));
        $show->field('creditcard_bill', __('Creditcard bill'));
        $show->field('school_name', __('School name'));
        $show->field('department', __('Department'));
        $show->field('is_student_loan', __('Is student loan'));
        $show->field('is_part_time_job', __('Is part time job'));
        $show->field('monthly_economy', __('Monthly economy'));
        $show->field('phone', __('Phone'));
        $show->field('line', __('Line'));
        $show->field('reason', __('Reason'));
        $show->field('is_contact', __('Is contact'));
        $show->field('contact_time', __('Contact time'));
        $show->field('amount', __('Amount'));
        $show->field('rate', __('Rate'));
        $show->field('platform_fee', __('Platform fee'));
        $show->field('repayment', __('Repayment'));
        $show->field('total_point', __('Total point'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('email', __('Email'));
        $show->field('job', __('Job'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BorrowReport());

        $form->number('identity', __('Identity'));
        $form->text('name', __('Name'));
        $form->text('educational_level', __('Educational level'));
        $form->text('is_top_enterprises', __('Is top enterprises'));
        $form->text('insurance_salary', __('Insurance salary'));
        $form->text('debt_amount', __('Debt amount'));
        $form->text('monthly_repayment', __('Monthly repayment'));
        $form->text('creditcard_quota', __('Creditcard quota'));
        $form->text('creditcard_bill', __('Creditcard bill'));
        $form->text('school_name', __('School name'));
        $form->text('department', __('Department'));
        $form->text('is_student_loan', __('Is student loan'));
        $form->text('is_part_time_job', __('Is part time job'));
        $form->text('monthly_economy', __('Monthly economy'));
        $form->mobile('phone', __('Phone'));
        $form->text('line', __('Line'));
        $form->textarea('reason', __('Reason'));
        $form->text('is_contact', __('Is contact'));
        $form->text('contact_time', __('Contact time'));
        $form->text('amount', __('Amount'));
        $form->text('rate', __('Rate'));
        $form->text('platform_fee', __('Platform fee'));
        $form->text('repayment', __('Repayment'));
        $form->text('total_point', __('Total point'));
        $form->email('email', __('Email'));
        $form->text('job', __('Job'));

        return $form;
    }
}
