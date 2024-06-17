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

        if ($value != null && strlen($value) > 4) {

            //check if value contains images/
            if (str_contains($value, 'images/')) {
            } else {
                $value = 'images/' . $value;
            }
            $path = Utils::docs_root() . "/storage/" . $value;

            //check if file exists
            if (file_exists($path)) {
                return $value;
            } else {
                $pics = Image::where('product_id', $this->id)->get();
                if ($pics->count() > 0) {
                    $value = $pics[0]->src;
                    $this->feature_photo = $value;
                    $this->save();
                }
                $this->feature_photo = null;
                $this->save();
            }
            if (str_contains($value, 'images/')) {
            } else {
                $value = 'images/' . $value;
            }
            return $value;
        }
        $logo = 'images/logo.png';
        return $logo;
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

    //has many images
    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
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
        $data = [];
        if ($value == null) {
            return $data;
        }
        //check if is string
        if (is_string($value) && strlen($value) > 2) {
            //check if is json
            $isJson = false;
            try {
                if (json_decode($value) != null) {
                    $data = json_decode($value);
                    $isJson = true;
                }
            } catch (\Throwable $th) {
                $isJson = false;
            }
            if (!$isJson) {
                try {
                    if (explode(',', $value) != null) {
                        $data = explode(',', $value);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else if (is_array($value)) {
            $data = $value;
        }

        // replace data string ",],[ with empty and trim
        $data = str_replace('"', '', $data);
        $data = str_replace('[', '', $data);
        $data = str_replace(']', '', $data);

        return $data;
    }

    //setter for colors to json
    public function setColorsAttribute($value)
    {
        $data = [];
        if ($value == null) {
            return json_encode($data);
        }
        //check if is string
        if (is_string($value) && strlen($value) > 2) {
            //check if is json
            $isJson = false;
            try {
                if (json_decode($value) != null) {
                    $data = json_decode($value);
                    $isJson = true;
                }
            } catch (\Throwable $th) {
                $isJson = false;
            }
            if (!$isJson) {
                try {
                    if (explode(',', $value) != null) {
                        $data = explode(',', $value);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else if (is_array($value)) {
            $data = $value;
        }
        if ($value != null) {
            $this->attributes['colors'] = json_encode($data);
        }
    }

    //getter for sizes
    public function getSizesAttribute($value)
    {
        $data = [];
        if ($value == null) {
            return $data;
        }
        //check if is string
        if (is_string($value) && strlen($value) > 2) {
            //check if is json
            $isJson = false;
            try {
                if (json_decode($value) != null) {
                    $data = json_decode($value);
                    $isJson = true;
                }
            } catch (\Throwable $th) {
                $isJson = false;
            }
            if (!$isJson) {
                try {
                    if (explode(',', $value) != null) {
                        $data = explode(',', $value);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else if (is_array($value)) {
            $data = $value;
        }

        // replace data string ",],[ with empty and trim
        $data = str_replace('"', '', $data);
        $data = str_replace('[', '', $data);
        $data = str_replace(']', '', $data);

        return $data;
    }

    //setter for sizes to json
    public function setSizesAttribute($value)
    {
        $data = [];
        if ($value == null) {
            return json_encode($data);
        }
        //check if is string
        if (is_string($value) && strlen($value) > 2) {
            //check if is json
            $isJson = false;
            try {
                if (json_decode($value) != null) {
                    $data = json_decode($value);
                    $isJson = true;
                }
            } catch (\Throwable $th) {
                $isJson = false;
            }
            if (!$isJson) {
                try {
                    if (explode(',', $value) != null) {
                        $data = explode(',', $value);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else if (is_array($value)) {
            $data = $value;
        }
        if ($value != null) {
            $this->attributes['sizes'] = json_encode($data);
        }
    }



    protected $casts = [
        'summary' => 'json',
    ];
}
