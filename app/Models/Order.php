<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\Customer;

class Order extends Model
{
    use HasFactory;
    //boot
    public static function boot()
    {
        parent::boot();
        //created
        self::created(function ($m) {
            //Utils::sync_orders();
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

    public function create_payment_link($stripe)
    {
        if (($this->stripe_id != null) && (strlen($this->stripe_id) > 0)) {
            return;
        }


        $itmes = $this->get_items();
        $line_items = [];
        foreach ($itmes as $key => $item) {
            $pro = Product::find($item->product);
            if ($pro == null) {
                continue;
            }
            if ($pro->stripe_price == null || strlen($pro->stripe_price) < 3) {
                continue;
            } 
            $line_items[] = [
                'price' => $pro->stripe_price,
                'quantity' => $item->qty,
            ];
        }

        $isSuccess = false;
        $resp = "";
        try {
            $resp = $stripe->paymentLinks->create([
                'currency' => 'cad',
                'line_items' => $line_items,
            ]);
            $isSuccess = true;
        } catch (\Throwable $th) {
            $isSuccess = false;
            $resp = $th->getMessage();
        }

        if ($isSuccess) {
            $this->stripe_id = $resp->id;
            $this->stripe_url = $resp->url;
            $this->stripe_paid = 'No';
            $this->save(); 
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
}
