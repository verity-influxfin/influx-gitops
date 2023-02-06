<?php

namespace App\Admin\Controllers;

use App\Models\Cooperation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CooperationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '合作洽談';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Cooperation());
        $grid->model()->orderBy('ID', 'desc');

        $grid->column('ID', __('Id'));
        $grid->column('name', __('name'));
        $grid->column('email', __('email'));
        $grid->column('phone', __('phone'));
        $grid->column('message', __('message'));
        $grid->column('type', __('type'));
        $grid->column('datetime', __('datetime'));

        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableActions();

        return $grid;
    }
}
