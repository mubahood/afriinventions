<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\Gen;
use App\Models\Utils;
use Dflydev\DotAccessData\Util;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
 
Route::get('/process', function () {

    //set_time_limit(0);
    set_time_limit(-1);
    //ini_set('memory_limit', '1024M');
    ini_set('memory_limit', '-1');

    $folderPath2 = base_path('public/temp/pics/final');
    $folderPath = base_path('public/temp/pics/');
    $biggest = 0;
    $tot = 0;

    // Check if the folder exists
    if (is_dir($folderPath)) {
        // Get the list of items in the folder
        $items = scandir($folderPath);
        $items_1 = scandir($folderPath2);

        $i = 0;


        // Loop through the items
        foreach ($items as $item) {

            // Exclude the current directory (.) and parent directory (..)
            if ($item != '.' && $item != '..') {


                $ext = pathinfo($item, PATHINFO_EXTENSION);
                if ($ext == null) {
                    continue;
                }
                $ext = strtolower($ext);


                if (!in_array($ext, [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                ])) {
                    continue;
                }

                $target = $folderPath . $item;
                $target_file_size = filesize($target);

                $target_file_size_to_mb = $target_file_size / (1024 * 1024);
                $target_file_size_to_mb = round($target_file_size_to_mb, 2);
                /* if($target_file_size_to_mb > 2){
                    $source = $target;
                    $dest = $folderPath . "final/" . $item;
                    Utils::create_thumbail([
                        'source' => $source,
                        'target' => $dest
                    ]);
                    unlink($source); 
                } */


                if ($target_file_size > $biggest) {
                    $biggest = $target_file_size;
                }
                $tot += $target_file_size;


                continue;
                //echo $i.". ".$item . "<br>";
                $i++;
                continue;

                $i++;
                print_r($i . "<br>");



                $fileSize = filesize($folderPath . "/" . $item);
                $fileSize = $fileSize / (1024 * 1024);
                $fileSize = round($fileSize, 2);
                $fileSize = $fileSize . " MB";
                $url = "http://localhost:8888/ham/public/temp/pics-1/" . $item;

                $source = $folderPath . "/" . $item;
                $target = $folderPath . "/thumb/" . $item;
                Utils::create_thumbail([
                    'source' => $source,
                    'target' => $target
                ]);

                echo "<img src='$url' alt='$item' width='550'/>";
                $target_file_size = filesize($target);
                $target_file_size = $target_file_size / (1024 * 1024);
                $target_file_size = round($target_file_size, 2);
                $target_file_size = $target_file_size . " MB";
                $url_2 = "http://localhost:8888/ham/public/temp/pics-1/thumb/" . $item;
                echo "<img src='$url_2' alt='$item' width='550' />";



                // Print the item's name
                echo "<b>" . $fileSize . "<==>" . $target_file_size . "<b><br>";
            }
        }
    } else {
        echo "The specified folder does not exist.";
    }

    $biggest = $biggest / (1024 * 1024);
    $biggest = round($biggest, 2);
    $biggest = $biggest . " MB";
    $tot = $tot / (1024 * 1024);
    $tot = round($tot, 2);
    $tot = $tot . " MB";
    echo "Biggest: " . $biggest . "<br>";
    echo "Total: " . $tot . "<br>";
    die("=>done<=");
});
Route::get('/sync', function () { 
})->name("gen");
Route::get('/gen', function () {
    die(Gen::find($_GET['id'])->do_get());
})->name("gen");
Route::get('/gen-form', function () {
    die(Gen::find($_GET['id'])->make_forms());
})->name("gen-form");
Route::get('generate-class', [MainController::class, 'generate_class']);
