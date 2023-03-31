<?php

namespace App\Admin\Controllers;

use App\Models\JobApplication;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class JobApplicationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'JobApplication';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new JobApplication());

        $grid->column('id', __('Id'));
        $grid->column('job_position', __('Job position'));
        $grid->column('name', __('Name'));
        $grid->column('blood_type', __('Blood type'));
        $grid->column('height', __('Height'));
        $grid->column('weight', __('Weight'));
        $grid->column('birthday', __('Birthday'));
        $grid->column('marriage', __('Marriage'));
        $grid->column('id_number', __('Id number'));
        $grid->column('hobby', __('Hobby'));
        $grid->column('address', __('Address'));
        $grid->column('mailing_address', __('Mailing address'));
        $grid->column('phone', __('Phone'));
        $grid->column('mobile_phone', __('Mobile phone'));
        $grid->column('email', __('Email'));
        $grid->column('education', __('Education'));
        $grid->column('expertise', __('Expertise'));
        $grid->column('work_experiences', __('Work experiences'));
        $grid->column('wrote_person', __('Wrote person'));
        $grid->column('wrote_date', __('Wrote date'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('img_url', __('Img url'));

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
        $show = new Show(JobApplication::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('job_position', __('Job position'));
        $show->field('name', __('Name'));
        $show->field('blood_type', __('Blood type'));
        $show->field('height', __('Height'));
        $show->field('weight', __('Weight'));
        $show->field('birthday', __('Birthday'));
        $show->field('marriage', __('Marriage'));
        $show->field('id_number', __('Id number'));
        $show->field('hobby', __('Hobby'));
        $show->field('address', __('Address'));
        $show->field('mailing_address', __('Mailing address'));
        $show->field('phone', __('Phone'));
        $show->field('mobile_phone', __('Mobile phone'));
        $show->field('email', __('Email'));
        $show->field('education', __('Education'));
        $show->field('expertise', __('Expertise'));
        $show->field('work_experiences', __('Work experiences'));
        $show->field('wrote_person', __('Wrote person'));
        $show->field('wrote_date', __('Wrote date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('img_url', __('Img url'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new JobApplication());

        $form->text('job_position', __('Job position'));
        $form->text('name', __('Name'));
        $form->text('blood_type', __('Blood type'));
        $form->number('height', __('Height'));
        $form->number('weight', __('Weight'));
        $form->datetime('birthday', __('Birthday'))->default(date('Y-m-d H:i:s'));
        $form->text('marriage', __('Marriage'));
        $form->text('id_number', __('Id number'));
        $form->text('hobby', __('Hobby'));
        $form->text('address', __('Address'));
        $form->text('mailing_address', __('Mailing address'));
        $form->mobile('phone', __('Phone'));
        $form->text('mobile_phone', __('Mobile phone'));
        $form->email('email', __('Email'));
        $form->text('education', __('Education'));
        $form->text('expertise', __('Expertise'));
        $form->text('work_experiences', __('Work experiences'));
        $form->text('wrote_person', __('Wrote person'));
        $form->datetime('wrote_date', __('Wrote date'))->default(date('Y-m-d H:i:s'));
        $form->text('img_url', __('Img url'));

        return $form;
    }
}
