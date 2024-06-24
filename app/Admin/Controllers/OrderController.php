<?php

namespace App\Admin\Controllers;

use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\User;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Orders';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());
        $grid->model()->orderBy('id', 'desc');
        $grid->quickSearch('customer_name')->placeholder('Search by customer name');
        $grid->column('id', __('Id'))->sortable();
        $grid->disableBatchActions();
        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return Utils::my_date_time($created_at);
            })->sortable();
        $grid->column('user', __('User'))
            ->display(function ($user) {
                $u = User::find($user);
                if ($u == null) {
                    return "Unknown";
                }
                return $u->name;
            })->sortable()->hide();
        $grid->column('order_state', __('Order State'))
            ->using([
                0 => 'Pending',
                1 => 'Processing',
                2 => 'Completed',
                3 => 'Canceled',
                4 => 'Failed',
            ])->sortable()
            ->label([
                0 => 'default',
                1 => 'info',
                2 => 'success',
                3 => 'danger',
                4 => 'warning',
            ])->filter([
                0 => 'Pending',
                1 => 'Processing',
                2 => 'Completed',
                3 => 'Canceled',
                4 => 'Failed',
            ]);
        $grid->column('amount', __('Amount'))
            ->display(function ($amount) {
                return 'R ' . number_format($amount);
            })->sortable();
        $grid->column('payment_confirmation', __('Payment'))
            ->display(function ($payment_confirmation) {
                if ($payment_confirmation == null || $payment_confirmation == "") {
                    return "Not Paid";
                }
                return $payment_confirmation;
            })->sortable();
        $grid->column('mail', __('Mail'))->sortable()
            ->hide();
        $grid->column('delivery_district', __('Delivery'))
            ->display(function ($delivery_district) {
                $delivery_district = DeliveryAddress::find($delivery_district);
                if ($delivery_district == null) {
                    return "Unknown";
                }
                return $delivery_district->address;
            })->sortable();
        $grid->column('temporary_id', __('Temporary id'));
        $grid->column('description', __('Description'));
        $grid->column('customer_name', __('Customer name'));
        $grid->column('customer_phone_number_1', __('Customer phone number 1'));
        $grid->column('customer_phone_number_2', __('Customer phone number 2'));
        $grid->column('customer_address', __('Customer address'));
        $grid->column('order_total', __('Order total'));
        $grid->column('order_details', __('Order details'));
        $grid->column('stripe_id', __('Stripe id'));
        $grid->column('stripe_url', __('Stripe url'));
        $grid->column('stripe_paid', __('Stripe paid'));
        $grid->column('delivery_method', __('Delivery method'));
        $grid->column('delivery_address_id', __('Delivery address id'));
        $grid->column('delivery_address_details', __('Delivery address details'));
        $grid->column('delivery_amount', __('Delivery amount'));
        $grid->column('payable_amount', __('Payable amount'));

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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('user', __('User'));
        $show->field('order_state', __('Order state'));
        $show->field('amount', __('Amount'));
        $show->field('date_created', __('Date created'));
        $show->field('payment_confirmation', __('Payment confirmation'));
        $show->field('date_updated', __('Date updated'));
        $show->field('mail', __('Mail'));
        $show->field('delivery_district', __('Delivery district'));
        $show->field('temporary_id', __('Temporary id'));
        $show->field('description', __('Description'));
        $show->field('customer_name', __('Customer name'));
        $show->field('customer_phone_number_1', __('Customer phone number 1'));
        $show->field('customer_phone_number_2', __('Customer phone number 2'));
        $show->field('customer_address', __('Customer address'));
        $show->field('order_total', __('Order total'));
        $show->field('order_details', __('Order details'));
        $show->field('stripe_id', __('Stripe id'));
        $show->field('stripe_url', __('Stripe url'));
        $show->field('stripe_paid', __('Stripe paid'));
        $show->field('delivery_method', __('Delivery method'));
        $show->field('delivery_address_id', __('Delivery address id'));
        $show->field('delivery_address_details', __('Delivery address details'));
        $show->field('delivery_amount', __('Delivery amount'));
        $show->field('payable_amount', __('Payable amount'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->number('user', __('User'));
        $form->textarea('order_state', __('Order state'));
        $form->textarea('amount', __('Amount'));
        $form->textarea('date_created', __('Date created'));
        $form->textarea('payment_confirmation', __('Payment confirmation'));
        $form->textarea('date_updated', __('Date updated'));
        $form->textarea('mail', __('Mail'));
        $form->textarea('delivery_district', __('Delivery district'));
        $form->number('temporary_id', __('Temporary id'));
        $form->textarea('description', __('Description'));
        $form->textarea('customer_name', __('Customer name'));
        $form->textarea('customer_phone_number_1', __('Customer phone number 1'));
        $form->textarea('customer_phone_number_2', __('Customer phone number 2'));
        $form->textarea('customer_address', __('Customer address'));
        $form->textarea('order_total', __('Order total'));
        $form->textarea('order_details', __('Order details'));
        $form->text('stripe_id', __('Stripe id'));
        $form->textarea('stripe_url', __('Stripe url'));
        $form->text('stripe_paid', __('Stripe paid'))->default('No');
        $form->text('delivery_method', __('Delivery method'));
        $form->number('delivery_address_id', __('Delivery address id'));
        $form->textarea('delivery_address_details', __('Delivery address details'));
        $form->decimal('delivery_amount', __('Delivery amount'));
        $form->decimal('payable_amount', __('Payable amount'));

        return $form;
    }
}
