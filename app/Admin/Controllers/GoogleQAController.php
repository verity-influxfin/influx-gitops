<?php

namespace App\Admin\Controllers;

use App\Models\GoogleQA;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoogleQAController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'GoogleQA';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoogleQA());

        $grid->column('id', __('Id'));
        $grid->column('job_position', __('Job position'));
        $grid->column('name', __('Name'));
        $grid->column('age', __('Age'));
        $grid->column('question', __('Question'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(GoogleQA::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('job_position', __('Job position'));
        $show->field('name', __('Name'));
        $show->field('age', __('Age'));
        $show->field('question', __('Question'));
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
        $form = new Form(new GoogleQA());

        $form->text('job_position', __('Job position'));
        $form->text('name', __('Name'));
        $form->text('age', __('Age'));
        $form->text('question', __('Question'));

        return $form;
    }
}
