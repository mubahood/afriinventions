<?php

namespace App\Models;

use Carbon\Carbon;
use Dflydev\DotAccessData\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'administrator_id',
        'src',
        'thumbnail',
        'parent_id',
        'size',
        'type',
        'product_id',
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($m) {


            if (str_contains($m->src, 'logo.png')) {
                return true;
            }

            try {
                $src = Utils::docs_root() . "/storage/images/" . $m->src;

                if ($m->thumbnail != null) {
                    if (strlen($m->thumbnail) > 2) {
                        $thumb = Utils::docs_root() . "/storage/images/" . $m->thumbnail;
                    }
                }
                if (!isset($thumb)) {
                    $thumb =  Utils::docs_root() . "/storage/images/thumb_" . $m->src;
                }

                if (file_exists($src)) {
                    unlink($src);
                }
                if (file_exists($thumb)) {
                    unlink($thumb);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
    }

    public function getSrcAttribute($src)
    {

        //CHECK IF $src == logo.png
        if (str_contains($src, 'logo.png')) {
            return $src;
        }

        if ($this->product_id != null && strlen($this->product_id) > 0) {
            if ($this->parent_id == null || strlen($this->parent_id) < 1) {
                $this->parent_id = $this->product_id;
                $this->save();
            }
        }
        if ($this->parent_id != null && strlen($this->parent_id) > 0) {
            if ($this->product_id == null || strlen($this->product_id) < 1) {
                $this->product_id = $this->parent_id;
                $this->save(); 
            }
        }


        if (!str_contains($src, 'images/')) {
            $source = Utils::docs_root() . "/storage/images/" . $src;
        } else {
            $source = Utils::docs_root() . "/storage/" . $src;
        }


        if (!file_exists($source)) {
            $sql = "DELETE FROM images WHERE id = " . $this->id;
            DB::delete($sql);
            return 'images/logo.png';
        }
        return $src;
    }
    /*  public function getThumbnailAttribute($src)
    {

        $source = Utils::docs_root() . "/storage/images/" . $src;
        if (!file_exists($source)) {
            return 'logo.png';
        }
        return $src;
    } */

    public function create_thumbail()
    {
        set_time_limit(-1);
        $src = $this->src;
        $source = Utils::docs_root() . "/storage/images/" . $this->src;
        if (!file_exists($source)) {
            $this->delete();
            return;
        }
        $this->thumbnail = $this->src;
        $this->save();
        return;

        $target = Utils::docs_root() . "/storage/images/thumb_" . $this->src;

        Utils::create_thumbail([
            'source' => $source,
            'target' => $target
        ]);

        if (file_exists($target)) {
            $this->thumbnail = "thumb_" . $this->src;
            $this->save();
        }
    }


    public function getUpdatedAtTextAttribute()
    {
        return Carbon::parse($this->updated_at)->timestamp;
    }


    protected $appends = ['updated_at_text'];
}
