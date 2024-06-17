<?php

namespace App\Admin\Controllers;

use App\Models\Image;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ImageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Image';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Image());
        $grid->quickSearch('src')->placeholder('Search by src');
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return Utils::my_date_time($created_at);
            })->hide();
        $grid->column('updated_at', __('Updated at'))->display(function ($updated_at) {
            return Utils::my_date_time($updated_at);
        })->sortable();
        $grid->column('administrator_id', __('User'))
            ->display(function ($administrator_id) {
                $u = \App\Models\User::find($administrator_id);
                if ($u == null) {
                    return 'N/A';
                }
                return $u->name;
            })->sortable();
        $grid->column('src', __('Src'))->sortable();
        $grid->column('thumbnail', __('Thumbnail'))->sortable();
        $grid->column('parent_id', __('Parent'))->sortable();
        $grid->column('type', __('Type'))->sortable()->hide();
        $grid->column('product_id', __('Product'))->sortable()
            ->display(function ($product_id) {
                $p = \App\Models\Product::find($product_id);
                if ($p == null) {
                    return 'N/A';
                }
                return $p->name;
            });

        $grid->column('local_id', __('Local id'))->sortable()->hide();
        $grid->column('parent_local_id', __('Parent local id'))->sortable()->hide();

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
        $show = new Show(Image::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('administrator_id', __('Administrator id'));
        $show->field('src', __('Src'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('parent_id', __('Parent id'));
        $show->field('size', __('Size'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('type', __('Type'));
        $show->field('product_id', __('Product id'));
        $show->field('parent_endpoint', __('Parent endpoint'));
        $show->field('note', __('Note'));
        $show->field('local_id', __('Local id'));
        $show->field('parent_local_id', __('Parent local id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Image());

        $form->number('administrator_id', __('Administrator id'));
        $form->textarea('src', __('Src'));
        $form->textarea('thumbnail', __('Thumbnail'));
        $form->number('parent_id', __('Parent id'));
        $form->number('size', __('Size'));
        $form->text('type', __('Type'));
        $form->number('product_id', __('Product id'));
        $form->textarea('parent_endpoint', __('Parent endpoint'));
        $form->textarea('note', __('Note'));
        $form->text('local_id', __('Local id'));
        $form->text('parent_local_id', __('Parent local id'));

        return $form;
    }
}
