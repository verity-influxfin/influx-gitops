<?php

namespace App\Admin\Controllers;

use App\Models\JobApplication;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

// use App\Admin\Actions\Post\SendJobApplicationEmail;

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

        $grid->column('id', '編號');
        $grid->column('job_position', '應徵職位');
        $grid->column('name', '姓名');
        $grid->column('blood_type', '血型');
        $grid->column('height', '身高');
        $grid->column('weight', '體重');
        $grid->column('birthday', '生日');
        $grid->column('marriage', '婚姻狀況');
        $grid->column('id_number', '身份證字號');
        $grid->column('hobby', '興趣、嗜好');
        $grid->column('address', '戶籍地址');
        $grid->column('mailing_address', '通訊地址');
        $grid->column('phone', '住家電話');
        $grid->column('mobile_phone', '行動電話');
        $grid->column('email', '電子信箱');
        $grid->column('education', '最高學歷');
        $grid->column('expertise', '專長');
        $grid->column('work_experiences', '工作經歷')->display(function($work_experiences) {
            $we_string = '';
            $work_experiences = json_decode($work_experiences);
            foreach ($work_experiences as $experience) {
                foreach($experience as $key => $val) {
                    if ($val == '' || $val == null) {
                        continue;
                    }
                    
                    if ($key == 'companyName') {
                        $we_string = $we_string . '公司名稱 : ' . $val . '<br>';
                    }
                    if ($key == 'jobDescription') {
                        $we_string = $we_string . '工作內容 : ' . $val . '<br>';
                    }
                    if ($key == 'yearsOfExperience') {
                        $we_string = $we_string . '經歷年資 : ' . $val . '<br>----------<br>';
                    }
                }
            }
            return $we_string;
        });
        $grid->column('wrote_person', '填表人');
        $grid->column('wrote_date', '填表日期');
        $grid->column('created_at', '創建時間');
        $grid->column('updated_at', '更新時間');
        $grid->column('img_url', '圖片路徑');

        // $grid->actions(function ($actions) {
        //     $actions->add(new SendJobApplicationEmail);
        // });

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

        $show->field('id', '編號');
        $show->field('job_position', '應徵職位');
        $show->field('name', '姓名');
        $show->field('blood_type', '血型');
        $show->field('height', '身高');
        $show->field('weight', '體重');
        $show->field('birthday', '生日');
        $show->field('marriage', '婚姻狀況');
        $show->field('id_number', '身份證字號');
        $show->field('hobby', '興趣、嗜好');
        $show->field('address', '戶籍地址');
        $show->field('mailing_address', '通訊地址');
        $show->field('phone', '住家電話');
        $show->field('mobile_phone', '行動電話');
        $show->field('email', '電子信箱');
        $show->field('education', '最高學歷');
        $show->field('expertise', '專長');
        $show->field('work_experiences', '工作經歷');
        $show->field('wrote_person', '填表人');
        $show->field('wrote_date', '填表日期');
        $show->field('created_at', '創建時間');
        $show->field('updated_at', '更新時間');
        $show->field('img_url', '圖片路徑');

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

        $form->text('job_position', '應徵職位');
        $form->text('name', '姓名');
        $form->text('blood_type', '血型');
        $form->number('height', '身高');
        $form->number('weight', '體重');
        $form->datetime('birthday', '生日')->default(date('Y-m-d H:i:s'));
        $form->text('marriage', '婚姻狀況');
        $form->text('id_number', '身份證字號');
        $form->text('hobby', '興趣、嗜好');
        $form->text('address', '戶籍地址');
        $form->text('mailing_address', '通訊地址');
        $form->mobile('phone', '住家電話');
        $form->text('mobile_phone', '行動電話');
        $form->email('email', '電子信箱');
        $form->text('education', '最高學歷');
        $form->text('expertise', '專長');
        $form->text('work_experiences', '工作經歷');
        $form->text('wrote_person', '填表人');
        $form->datetime('wrote_date', '填表日期')->default(date('Y-m-d H:i:s'));
        $form->text('img_url', '圖片路徑');

        return $form;
    }
}
