<?php

namespace App\Admin\Controllers;

use App\Model\VoiceModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VoiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\VoiceModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VoiceModel);

        $grid->column('vid', __('Vid'));
        $grid->column('voice_time', __('Voice time'));
        $grid->column('voice', __('Voice'));
        $grid->column('uid', __('Uid'));
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
        $show = new Show(VoiceModel::findOrFail($id));

        $show->field('vid', __('Vid'));
        $show->field('voice_time', __('Voice time'));
        $show->field('voice', __('Voice'));
        $show->field('uid', __('Uid'));
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
        $form = new Form(new VoiceModel);

        $form->number('vid', __('Vid'));
        $form->number('voice_time', __('Voice time'));
        $form->text('voice', __('Voice'));
        $form->number('uid', __('Uid'));

        return $form;
    }
}
