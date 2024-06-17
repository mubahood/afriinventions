<?php

namespace App\Admin\Controllers;

use App\Models\Image;
use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Products';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        $grid->disableExport();

        $grid->quickSearch('name')->placeholder('Search by name');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $cats = \App\Models\ProductCategory::all();
            $filter->equal('category', 'Category')->select(
                $cats->pluck('category', 'id')
            );

            $filter->between('price_1', 'Select Price');
            $filter->between('created_at', 'Created at')->datetime();
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable()
            ->editable();
        $grid->column('description', __('Description'))
            ->hide();

        $grid->column('price_2', __('Original Price'))
            ->sortable()
            ->editable();
        $grid->column('price_1', __('Selling Price'))
            ->sortable()
            ->editable();
        $grid->picture('feature_photo', __('Photo'))
            ->lightbox(['width' => 50, 'height' => 50])
            ->sortable();
        $grid->column('date_updated', __('Date updated'));
        $grid->column('user', __('User'))
            ->display(function ($user) {
                $u =  \App\Models\User::find($user);
                if ($u == null) {
                    return 'Deleted';
                }
                return $u->name;
            })
            ->sortable();
        $grid->column('category', __('Category'))
            ->display(function ($category) {
                $c =  \App\Models\ProductCategory::find($category);
                if ($c == null) {
                    return 'Deleted';
                }
                return $c->category;
            })
            ->sortable();

        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return date('Y-m-d H:i:s', strtotime($created_at));
            })->sortable();
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('metric', __('Metric'));
        $show->field('currency', __('Currency'));
        $show->field('description', __('Description'));
        $show->field('summary', __('Summary'));
        $show->field('price_1', __('Price 1'));
        $show->field('price_2', __('Price 2'));
        $show->field('feature_photo', __('Feature photo'));
        $show->field('rates', __('Rates'));
        $show->field('date_added', __('Date added'));
        $show->field('date_updated', __('Date updated'));
        $show->field('user', __('User'));
        $show->field('category', __('Category'));
        $show->field('sub_category', __('Sub category'));
        $show->field('supplier', __('Supplier'));
        $show->field('url', __('Url'));
        $show->field('status', __('Status'));
        $show->field('in_stock', __('In stock'));
        $show->field('keywords', __('Keywords'));
        $show->field('p_type', __('P type'));
        $show->field('local_id', __('Local id'));
        $show->field('updated_at', __('Updated at'));
        $show->field('created_at', __('Created at'));
        $show->field('data', __('Data'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        //$d = Image::find(2822);
        //dd($d->src); 
        $form = new Form(new Product());
        /*   $p = Product::find(921);
        $colors = $p->colors;
        dd($colors);
       
        $p->name .= '1';
        $p->save();
        die('done'); */


        if ($form->isCreating()) {
            $form->hidden('user', __('Product provider'))->default(Auth::user()->id)->readOnly()->rules('required');
        }

        $form->text('name', __('Name'))
            ->rules('required');

        $form->decimal('price_2', __('Original Price'))
            ->rules('required');
        $form->decimal('price_1', __('Selling Price'))
            ->rules('required');

        $cats = \App\Models\ProductCategory::all();
        $form->select('category', __('Category'))
            ->options(
                $cats->pluck('category', 'id')
            )
            ->rules('required');
        /*    $form->text('colors', __('colors'));
        return $form;  */

        $form->radio('has_colors', __('Has colors?'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No'
            ])->when('Yes', function (Form $form) {
                //list of primary colors
                $colors = [
                    'Red' => 'Red',
                    'Blue' => 'Blue',
                    'Yellow' => 'Yellow',
                    'Green' => 'Green',
                    'Orange' => 'Orange',
                    'Purple' => 'Purple',
                    'Brown' => 'Brown',
                    'Pink' => 'Pink',
                    'Black' => 'Black',
                    'White' => 'White',
                    'Gray' => 'Gray',
                    'Cyan' => 'Cyan',
                    'Magenta' => 'Magenta',
                    'Lime' => 'Lime',
                    'Teal' => 'Teal',
                    'Lavender' => 'Lavender',
                    'Maroon' => 'Maroon',
                    'Navy' => 'Navy',
                    'Olive' => 'Olive',
                    'Silver' => 'Silver',
                    'Dark' => 'Dark',
                    'DarkBlue' => 'DarkBlue',
                    'DarkCyan' => 'DarkCyan',
                    'DarkGray' => 'DarkGray',
                    'DarkGreen' => 'DarkGreen',
                ];
                $form->tags('colors', 'Select colors')
                    ->options($colors)
                    ->rules('required');
            })->default('No');

        //has_sizes
        $form->radio('has_sizes', __('Has sizes?'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No'
            ])->when('Yes', function (Form $form) {
                //list of primary colors
                $sizes = [
                    'XS' => 'XS',
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                    'XXL' => 'XXL',
                    'XXXL' => 'XXXL',
                    'XXXXL' => 'XXXXL',
                    'XXXXXL' => 'XXXXXL',
                ];
                $form->tags('sizes', 'Select sizes')
                    ->options($sizes)
                    ->rules('required');
            })->default('No');

        $form->quill('description', __('Description'))
            ->rules('required');

        $form->image('feature_photo', __('Feature photo'))
            ->rules('required');

        /* $form->url('url', __('Url')); 
                $form->decimal('rates', __('Rates'));
        */
        /*      $form->keyValue('summary', __('Data'));
 */
        //has many images
        $form->hasMany('images', 'Images', function (Form\NestedForm $form) {
            /*  
created_at	
updated_at	
administrator_id	
src	
thumbnail	
parent_id	
size	
deleted_at	
type	
product_id	
parent_endpoint	
note	
local_id	
parent_local_id	
	
 
            */
            $u = Auth::user();
            $form->hidden('administrator_id', 'Administrator')->default($u->id)->readOnly()->rules('required');
            $form->image('src', 'Image')->rules('required')->uniqueName();
            $form->text('product_id', 'product_id'); 
            $form->text('parent_id', 'parent_id'); 
        });


        return $form;
    }
}
