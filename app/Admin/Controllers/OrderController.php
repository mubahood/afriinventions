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
        $grid->disableBatchActions();
        $grid->quickSearch('customer_name')->placeholder('Search by customer name');
        $grid->column('id', __('Id'))->sortable();
        //$grid->disableBatchActions();
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
            ->sortable()
            ->display(function ($x) {
                $badge_color = "primary";
                if ($x == 1) {
                    $badge_color = "warning";
                } else if ($x == 2) {
                    $badge_color = "info";
                } else if ($x == 3) {
                    $badge_color = "success";
                } else if ($x == 4) {
                    $badge_color = "danger";
                } else if ($x == 5) {
                    $badge_color = "danger";
                }
                $text = 'Pending';
                if ($x == 0) {
                    $text = 'Pending';
                } else if ($x == 1) {
                    $text = 'Processing';
                } else if ($x == 2) {
                    $text = 'Completed';
                } else  if ($x == 3) {
                    $text = 'Canceled';
                } else {
                    $text = 'Failed';
                }
                return "<span class='badge bg-$badge_color'>$text</span>";
            });
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
        $grid->column('description', __('Description'))->hide();
        $grid->column('customer_name', __('Customer'))->sortable();
        $grid->column('customer_phone_number_1', __('Customer Contact'));
        $grid->column('customer_phone_number_2', __('Customer phone number 2'))->hide();
        $grid->column('customer_address', __('Customer address'));
        $grid->column('order_total', __('Total'))
            ->display(function ($order_total) {
                return 'R ' . number_format($order_total);
            })->sortable()->hide();
        /* actions, view order details and update order */
        $grid->column('view', __('View'))
            ->display(function () {
                $order = Order::find($this->id);
                $link = admin_url('orders/' . $order->id);
                $link = "<a href='$link' class='btn btn-info btn-sm'>View</a>";
                return $link;
            });
        $grid->column('update', __('Update'))
            ->display(function () {
                $order = Order::find($this->id);
                $link = admin_url('orders/' . $order->id . '/edit');
                $link = "<a href='$link' class='btn btn-warning btn-sm'>Update</a>";
                return $link;
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
        $show = new Show(Order::findOrFail($id));
        $o = Order::find($id);
        return view('order', ['order' => $o]);
        return;

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
        //display
        $form->display('id', __('Id'));
        /*  $order_state = "Pending";
        if ($this->status == 1) {
            $status = "Processing";
        } else if ($this->status == 2) {
            $status = "Completed";
        } else if ($this->status == 3) {
            $status = "Canceled";
        } else if ($this->status == 4) {
            $status = "Failed";
        } */
        $form->radio('order_state', __('Order state'))
            ->options([
                0 => 'Pending',
                1 => 'Processing',
                2 => 'Completed',
                3 => 'Canceled',
                4 => 'Failed',
            ]);

        $form->disableEditingCheck();
        $form->disableReset();
        $form->disableViewCheck();

        return $form;
    }
}
