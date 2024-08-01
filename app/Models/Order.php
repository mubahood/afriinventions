<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    //boot
    public static function boot()
    {
        parent::boot();
        //created
        self::created(function ($m) {
            //send email to admin
            try {
                self::send_mail_to_admin($m);
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
        self::deleting(function ($m) {
            try {
                $items = OrderedItem::where('order', $m->id)->get();
                foreach ($items as $item) {
                    $item->delete();
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
    }

    //static send mail to admin
    public static function send_mail_to_admin($order)
    {/* 
        sample mail formart

                $u = $this;
        $u->intro = rand(100000, 999999);
        $u->save();
        $data['email'] = $u->email;
        if ($u->email == null || $u->email == "") {
            $data['email'] = $u->username;
        }
        $data['name'] = $u->name;
        $data['subject'] = env('APP_NAME') . " - Password Reset";
        $data['body'] = "<br>Dear " . $u->name . ",<br>";
        $data['body'] .= "<br>Please use the code below to reset your password.<br><br>";
        $data['body'] .= "CODE: <b>" . $u->intro . "</b><br>";
        $data['body'] .= "<br>Thank you.<br><br>";
        $data['body'] .= "<br><small>This is an automated message, please do not reply.</small><br>";
        $data['view'] = 'mail-1';
        $data['data'] = $data['body'];
        try {
            Utils::mail_sender($data);
        } catch (\Throwable $th) {
            throw $th;
        } 
        */
        $ORDER_LINK = admin_url('orders');
        try {
            $admin_mails = [
                'mubahood360@gmail.com',
                'morakeneo271@gmail.com'
            ];
            $data['email'] = $admin_mails;
            $data['name'] = "Admin";
            $data['subject'] = "New Order - #" . $order->id;
            $data['body'] = "<br>Dear Admin,<br>";
            $data['body'] .= "<br>A new order has been placed.<br><br>";
            $data['body'] .= "Order ID: <b>#" . $order->id . "</b><br>";
            $data['body'] .= "Customer: <b>" . $order->customer->name . "</b><br>";
            $data['body'] .= "Review Order: <a href='$ORDER_LINK'>Click here</a><br>";
            $data['body'] .= "<br>Thank you.<br><br>";
            $data['body'] .= "<br><small>This is an automated message, please do not reply.</small><br>";
            $data['view'] = 'mail-1';
            $data['data'] = $data['body'];
            Utils::mail_sender($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function get_items()
    {
        $items = [];
        foreach (OrderedItem::where([
            'order' => $this->id
        ])->get() as $_item) {
            $pro = Product::find($_item->product);
            if ($pro == null) {
                continue;
            }
            if ($_item->pro == null) {
                continue;
            }
            $_item->product_name = $_item->pro->name;
            $_item->product_feature_photo = $_item->pro->feature_photo;
            $_item->product_price_1 = $_item->pro->price_1;
            $_item->product_quantity = $_item->qty;
            $_item->product_id = $_item->pro->id;
            $items[] = $_item;
        }
        return $items;
    }

    //belongs to customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'user');
    }

    //getter for items
    public function getItemsAttribute()
    {
        return json_encode($this->get_items());
    }

    //appends for items
    protected $appends = ['items'];
}
