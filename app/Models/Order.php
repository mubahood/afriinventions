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
    {
        try {
            //
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
