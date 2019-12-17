<?php

namespace App\Admin\Controllers;

use App\Model\ModelImgModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ImgController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\ModelImgModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ModelImgModel);

        $grid->column('i_id', __('I id'));
        $grid->column('img_time', __('Img time'));
        $grid->column('imgs', __('Imgs'))->display(function($img){
            return '<img src="http"//weixin.1905.com/'.$img.'" width="100" height="100">';
        });;
//        $grid->column('headimgurl',__('Headimgurl'))->display(function($img){
//            return '<img src="'.$img.'">';
//        });
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
        $show = new Show(ModelImgModel::findOrFail($id));

        $show->field('i_id', __('I id'));
        $show->field('img_time', __('Img time'));
        $show->field('imgs', __('Imgs'));
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
        $form = new Form(new ModelImgModel);

        $form->number('i_id', __('I id'));
        $form->number('img_time', __('Img time'));
        $form->text('imgs', __('Imgs'));
        $form->text('uid', __('Uid'));

        return $form;
    }
}
