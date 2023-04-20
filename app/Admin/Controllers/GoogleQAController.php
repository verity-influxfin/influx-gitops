<?php

namespace App\Admin\Controllers;

use App\Models\GoogleQA;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Post\SendQAEmail;

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

        $grid->column('id', '編號');
        $grid->column('job_position', '應徵職位');
        $grid->column('name', '姓名');
        $grid->column('age', '年齡');
        $grid->column('question', 'QA')->display(function($questions) {
            $q_string = '';
            $questions = json_decode($questions);
            foreach ($questions as $question) {
                $q_string = $q_string . $question->{'q'} . ' => ' . $question->{'a'} . '<br>';
            }

            return $q_string;
        });
        $grid->column('created_at', '創建時間');
        $grid->column('updated_at', '更新時間');

        $grid->actions(function ($actions) {
            $actions->add(new SendQAEmail);
        });

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

        $show->field('id', '編號');
        $show->field('job_position', '應徵職位');
        $show->field('name', '姓名');
        $show->field('age', '年齡');
        $show->field('question', 'QA');
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
        $form = new Form(new GoogleQA());

        $form->text('job_position', '應徵職位');
        $form->text('name', '姓名');
        $form->text('age', '年齡');
        $form->text('question', 'QA');

        return $form;
    }
}
