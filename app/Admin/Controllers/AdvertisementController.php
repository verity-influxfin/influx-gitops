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

        $grid->column('id', __('Id'));
        $grid->column('img_url', __('Img url'));
        $grid->column('type', __('Type'));
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
        $show = new Show(Advertisement::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('img_url', __('Img url'));
        $show->field('type', __('Type'));
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
        $form = new Form(new Advertisement());

        $form->text('img_url', __('Img url'));
        $form->text('type', __('Type'));

        return $form;
    }
}
