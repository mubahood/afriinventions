<?php

namespace App\Models;

use Dflydev\DotAccessData\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        //created
        self::created(function ($m) {
            Utils::sync_products();
        });

        //updating
        self::updating(function ($m) {
            //old price_1
            $old_price_1 = $m->getOriginal('price_1');
            $new_price_1 = $m->price_1;
            if ($old_price_1 != ($new_price_1)) {
                try {
                    $stripe_price = $m->update_stripe_price($new_price_1);
                    $m->stripe_price = $stripe_price;
                } catch (\Throwable $th) {
                    throw $th->getMessage();
                }
            }
            return $m;
        });
        //updated
        self::updated(function ($m) {
            $m->sync(Utils::get_stripe());
        });

        self::deleting(function ($m) {
            try {
                $imgs = Image::where('parent_id', $m->id)->orwhere('product_id', $m->id)->get();
                foreach ($imgs as $img) {
                    $img->delete();
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
    }

    //getter for feature_photo
    public function getFeaturePhotoAttribute($value)
    {

        //check if value contains images/
        if (str_contains($value, 'images/')) {
            return $value;
        }
        $value = 'images/' . $value;
        return $value;
    }

    //getter for price_2
    public function getPrice2Attribute($value)
    {
        if ($value == null || $value == 0 || strlen($value) < 1) {
            $p1 = ((int)($this->price_1));
            //10 of p1
            $discount = $p1 * 0.1;
            $value = $p1 + $discount;
        }
        return $value;
    }


    public function update_stripe_price($new_price)
    {

        $new_price = null;
        $stripe = Utils::get_stripe();
        set_time_limit(-1);
        try {
            $new_price = $stripe->prices->create([
                'currency' => 'cad',
                'unit_amount' => $this->price_1 * 100,
                'product' => $this->stripe_id,
            ]);
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
        if ($new_price == null) {
            throw new \Exception("Error Processing Request", 1);
        }

        $resp = null;
        try {
            $resp = $stripe->products->update(
                $this->stripe_id,
                [
                    'default_price' => $this->stripe_price,
                    'name' => 'Muhindo mubaraka test',
                ]
            );
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
        if ($resp == null) {
            throw new \Exception("Error Processing Request", 1);
        }


        if ($resp->default_price != null) {
            return $resp->default_price;
        } else {
            throw new \Exception("Error Processing Request", 1);
        }
    }

    public function sync($stripe)
    {
        $stripe = Utils::get_stripe();
        set_time_limit(-1);
        $original_images = json_decode($this->rates);
        $imgs = [];
        $i = 0;
        if (is_array($original_images))
            foreach ($original_images as $key => $v) {
                $imgs[] = 'https://app.hambren.com/storage/images/' . $v->src;
                if ($i > 5) {
                    break;
                }
                $i++;
            }

        if ($this->stripe_price != null && $this->stripe_id != null && $this->stripe_price != '' && strlen($this->stripe_id) > 5) {
            try {
                $resp = $stripe->products->update(
                    $this->stripe_id,
                    [
                        'images' => $imgs,
                        'name' => $this->name,
                    ]
                );
            } catch (\Throwable $th) {
            }
        } else {
            $resp = $stripe->products->create([
                'name' => $this->name,
                'default_price_data' => [
                    'currency' => 'cad',
                    'unit_amount' => $this->price_1 * 100,
                ],
            ]);
            if ($resp != null) {
                $this->stripe_id = $resp->id;
                $this->stripe_price = $resp->default_price;
                $this->save();
            }
        }
    }
    public function getRatesAttribute()
    {
        $imgs = Image::where('parent_id', $this->id)->orwhere('product_id', $this->id)->get();
        return json_encode($imgs);
    }


    protected $appends = ['category_text'];
    public function getCategoryTextAttribute()
    {
        $d = ProductCategory::find($this->category);
        if ($d == null) {
            return 'Not Category.';
        }
        return $d->category;
    }

    //getter for colors from json
    public function getColorsAttribute($value)
    {
        $value = json_decode($value);
        return $value;
    }

    //setter for colors to json
    public function setColorsAttribute($value)
    {
        if ($value != null) {
            if (strlen($value) > 2) {
                $value = json_encode($value);
                $this->attributes['colors'] = $value;
            }
        }
    }

    

    protected $casts = [
        'summary' => 'json',
    ];
}
