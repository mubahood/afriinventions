<?php

namespace App\Models;

use Encore\Admin\Form\Field\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as RelationsBelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function send_vendor_verification_mail_sent()
    {
        if ($this->vendor_verification_mail_sent == "Yes") {
            return;
        }
        if ($this->user_type != "Vendor") {
            return;
        }
        $verification_link = admin_url('requests/' . $this->id . '/edit');
        //email to admin to verify vendor
        $data['email'] = [
            'mubahood360@gmail.com',
            'morakeneo271@gmail.com',
            'salesafriinventions@outlook.com'
        ];
        $data['name'] = $this->name;
        $data['subject'] =  $this->name . " - Vendor Verification - " . env('APP_NAME');
        $data['body'] = "<br>Dear Admin,<br>";
        $data['body'] .= "<br>Please verify the vendor below.<br><br>";
        $data['body'] .= "Name: <b>" . $this->name . "</b><br>";
        $data['body'] .= "Email: <b>" . $this->email . "</b><br>";
        $data['body'] .= "Phone: <b>" . $this->phone . "</b><br>";
        $data['body'] .= "Business Name: <b>" . $this->business_name . "</b><br>";
        $data['body'] .= "User Type: <b>" . $this->user_type . "</b><br>";
        $data['body'] .= "<br><a href='" . $verification_link . "'>Verify Vendor</a><br>";
        $data['body'] .= "<br>Thank you.<br><br>";
        $data['body'] .= "<br><small>This is an automated message, please do not reply.</small><br>";
        $data['view'] = 'mail-1';
        $data['data'] = $data['body'];
        try {
            Utils::mail_sender($data);
            $sql = "UPDATE users SET vendor_verification_mail_sent = 'Yes', vendor_verification_mail_sent_date = '" . date('Y-m-d H:i:s') . "' WHERE id = " . $this->id;
            DB::update($sql);
            //$this->vendor_verification_mail_sent = "Yes";
            //$this->vendor_verification_mail_sent_date = date('Y-m-d H:i:s');
            //$this->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function send_password_reset()
    {
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
    }

    //boot method to listen for events
    protected static function boot()
    {
        parent::boot();

        //updated
        static::updated(function ($model) {
            if ($model->vendor_status == "Vendor") {
                if ($model->vendor_verification_mail_sent != "Yes") {
                    $model->send_vendor_verification_mail_sent();
                }
            }
        });

        static::updating(function ($model) {
            if ($model->vendor_status == "Vendor") {
                $model->status = "Active";
                $model->user_type = "Vendor";
            }
        });
    }

    public function send_verification_code($email)
    {
        $u = $this;
        $u->intro = rand(100000, 999999);
        $u->save();
        $data['email'] = $email;
        if ($email == null || $email == "") {
            throw new \Exception("Email is required.");
        }

        $data['name'] = $u->name;
        $data['subject'] = env('APP_NAME') . " - Email Verification";
        $data['body'] = "<br>Dear " . $u->name . ",<br>";
        $data['body'] .= "<br>Please use the CODE below to verify your email address.<br><br>";
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
    }



    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function programs()
    {
        return $this->hasMany(UserHasProgram::class, 'user_id');
    }
}
