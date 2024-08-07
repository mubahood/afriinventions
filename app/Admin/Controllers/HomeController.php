<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Association;
use App\Models\Candidate;
use App\Models\Garden;
use App\Models\Group;
use App\Models\Image;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Location;
use App\Models\Order;
use App\Models\Person;
use App\Models\Product;
use App\Models\Utils;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;
use SplFileObject;

class HomeController extends Controller
{
    public function index(Content $content)
    {

        //get last product
        $product = Product::orderBy('id', 'desc')->first();


       
        $order = Order::orderBy('id', 'desc')->first();
    
        /* 
        $names = [
            "Abdul Rahman Mulinde",
            "Abdullah Kituku Abdullah",
            "Abdul Rahman Faisal",
            "Abdulrashid Uthman Buzimwa",
            "Ahmad Muslim Kayondo",
            "Ahmed Muhammad Kayondo",
            "Ahsan Taib Ssali",
            "Aryan Sulaiman",
            "Asma Zainab Mayanja",
            "Ayan Rashid Zalwango",
            "Bahaa Ehab Sserwadda",
            "Ilmah Nagadya Buyondo",
            "Harry Elsheikh Chol Ajeing",
            "Hatim Jamal Dhakaba",
            "Hayan Mumanzi Ramadhan",
            "Heyzern Sufi Jad",
            "Hibatullah Kirabo",
            "Huzaifa Farouk Kitaka",
            "Huzayl Tareeq Kasigwa",
            "Israh Idris Mubiru",
            "Istarlin Maryam Buga",
            "Jibran Uwais Muguzi",
            "Abdul Wahab Juuko",
            "Imran Yusuf Kabenge",
            "Osman Ramathan Kambo"
        ];

        $faker = Faker::create();
        for ($i = 0; $i < 100; $i++) {
            $inv = new Invoice();
            $inv->customer_name = $names[rand(0, (count($names) - 1))];
            $inv->invoice_date =  $faker->dateTimeBetween('-2 month');
            $inv->customer_address =  $faker->address();
            $inv->customer_address =  $faker->address();
            $inv->customer_contact =  $faker->phoneNumber();
            $inv->save();
            $max = rand(4, 20);

            for ($j = 0; $j < $max; $j++) {
                $item = new InvoiceItem();
                $item->invoice_id = $inv->id;
                $item->product_id = rand(1, 49);
                $item->quantity = rand(1, 20);
                $item->save();
            }
        }

 
    



        */
        /*         $medical_supplies = array(
            'Adhesive bandages',
            'Gauze pads',
            'Medical gloves',
            'Alcohol swabs',
            'Thermometers',
            'Blood glucose meters',
            'Blood pressure monitors',
            'Nebulizers',
            'Inhalers',
            'Stethoscopes',
            'Tongue depressors',
            'Suture kits',
            'Scalpels',
            'Surgical masks',
            'Face shields',
            'Eye shields',
            'Protective gowns',
            'Isolation gowns',
            'Sterile drapes',
            'Surgical sponges',
            'Surgical towels',
            'Surgical blades',
            'Sterile syringes',
            'Sterile needles',
            'Intravenous catheters',
            'Intravenous fluid bags',
            'Urine collection bags',
            'Foley catheters',
            'Ostomy bags',
            'Wound dressings',
            'Surgical tape',
            'Adhesive remover',
            'Splints',
            'Casts',
            'Crutches',
            'Walkers',
            'Wheelchairs',
            'Oxygen tanks',
            'Nasal cannulas',
            'Tracheostomy tubes',
            'Feeding tubes',
            'Nasogastric tubes',
            'Urinary catheterization kits',
            'Electrocardiogram machines',
            'Ultrasound machines',
            'X-ray machines',
            'CT scanners',
            'MRI machines',
            'Defibrillators',
            'Pacemakers'
        );

        foreach ($medical_supplies as $key => $v) {
            $p = new Product();
            $p->quantity = rand(1, 100);
            $p->administrator_id = 1;
            $p->name = $v;
            $p->details = 'Some details';
            $p->photo = rand(1, 10) . ".jpg";
            $p->price = [
                500,
                1000,
                2000,
                5000,
                10000,
                50000,
                15000,
                36000,
                3600,
                8600,
                1800,
                12900,
                29900,
                80000,
                28000,
                76000,
                77000,
                80000,
                28000,
                76000,
                77000,
                8700,
                1200,
                3200,
            ][rand(0, 23)];
            $p->save();
        }

        $p->save();
        die('onde');
 */
     /*    $img = Image::where([])
            ->orderBy('id', 'desc')
            ->first(); 
        $path = env('APP_URL') . "/storage/images/";
        echo '<img width=500" src="' . $path . $img->src . '" >';
        echo '<img width=500" src="' . $path . $img->thumbnail . '" ><br>';

        $src_size = filesize(Utils::docs_root() . "/storage/images/" . $img->src);
        $src_size = $src_size / (1024 * 1024);
        $src_size = round($src_size, 2);
        $src_size = $src_size . " MB";

        $thumb_size = filesize(Utils::docs_root() . "/storage/images/" . $img->thumbnail);
        $thumb_size = $thumb_size / (1024 * 1024);
        $thumb_size = round($thumb_size, 2);
        $thumb_size = $thumb_size . " MB";

        echo " MAIN " . $src_size . "<br>";
        echo " THUMB " . $thumb_size . "<br>";

        die('done'); */

        $u = Auth::user();
        $content
            ->title(env('APP_NAME').' - Dashboard')
            ->description('Hello ' . $u->name . "!");
        return $content;

        $content->row(function (Row $row) {
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'This month Sales',
                    'sub_title' => NULL,
                    'number' => "UGX " . number_format(rand(10000, 10000000)),
                    'link' => 'sales'
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Last month Sales',
                    'sub_title' => NULL,
                    'number' => "UGX " . number_format(rand(10000, 10000000)),
                    'link' => 'invoices'
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'This week Sales',
                    'sub_title' => NULL,
                    'number' => "UGX " . number_format(rand(1000, 100000)),
                    'link' => 'invoices'
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'style' => 'danger',
                    'title' => 'Debtors',
                    'sub_title' => NULL,
                    'number' => "UGX " . number_format(rand(1000, 100000)),
                    'link' => 'invoices'
                ]));
            });
        });

        return $content;

        /*        $content->row(function (Row $row) {

            $row->column(2, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Enjaz',
                    'sub_title' => NULL,
                    'number' => number_format(Candidate::where(['stage' => 'enjaz'])->count()),
                    'link' => 'enjaz'
                ]));
            });

            $row->column(2, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Embasy',
                    'sub_title' => NULL,
                    'number' => number_format(Candidate::where(['stage' => 'Embasy'])->count()),
                    'link' => 'embasy'
                ]));
            });

            $row->column(2, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Departure',
                    'sub_title' => NULL,
                    'number' => number_format(Candidate::where(['stage' => 'Departure'])->count()),
                    'link' => 'ready-for-departure'
                ]));
            });

            $row->column(2, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Traveled',
                    'sub_title' => NULL,
                    'number' => number_format(Candidate::where(['stage' => 'Traveled'])->count()),
                    'link' => 'traveled'
                ]));
            });

            $row->column(2, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'style' => 'danger',
                    'title' => 'Failed',
                    'sub_title' => NULL,
                    'number' => number_format(Candidate::where(['stage' => 'Failed'])->count()),
                    'link' => 'failed'
                ]));
            });

            $row->column(2, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => true,
                    'title' => 'All candidates',
                    'sub_title' => NULL,
                    'number' => number_format(Candidate::where([])->count()),
                    'link' => 'candidates'
                ]));
            });
        });
 */

        $content->row(function (Row $row) {
            $row->column(6, function (Column $column) {
                $column->append(view('widgets.by-categories', []));
            });
            $row->column(6, function (Column $column) {

                $column->append(view('widgets.by-districts', []));
                // $column->append(Dashboard::dashboard_events());
            });
        });


        return $content;

        $u = Admin::user();


        $content->row(function (Row $row) {
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'New members',
                    'sub_title' => 'Joined 30 days ago.',
                    'number' => number_format(rand(100, 600)),
                    'link' => 'javascript:;'
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Products & Services',
                    'sub_title' => 'All time.',
                    'number' => number_format(rand(1000, 6000)),
                    'link' => 'javascript:;'
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Job oppotunities',
                    'sub_title' => rand(100, 400) . ' jobs posted 7 days ago.',
                    'number' => number_format(rand(1000, 6000)),
                    'link' => 'javascript:;'
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => true,
                    'title' => 'System traffic',
                    'sub_title' => rand(100, 400) . ' mobile app, ' . rand(100, 300) . ' web browser.',
                    'number' => number_format(rand(100, 6000)),
                    'link' => 'javascript:;'
                ]));
            });
        });




        $content->row(function (Row $row) {
            $row->column(6, function (Column $column) {
                $column->append(view('widgets.by-categories', []));
            });
            $row->column(6, function (Column $column) {
                $column->append(view('widgets.by-districts', []));
            });
        });



        $content->row(function (Row $row) {
            $row->column(6, function (Column $column) {
                $column->append(Dashboard::dashboard_members());
            });
            $row->column(3, function (Column $column) {
                $column->append(Dashboard::dashboard_events());
            });
            $row->column(3, function (Column $column) {
                $column->append(Dashboard::dashboard_news());
            });
        });




        return $content;
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }
}
