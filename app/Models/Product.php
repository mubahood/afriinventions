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
        });

        //updating
        self::updating(function ($m) {
            //old price_1
            $old_price_1 = $m->getOriginal('price_1');
            $new_price_1 = $m->price_1; 
            return $m;
        });
        //updated
        self::updated(function ($m) {
 
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
