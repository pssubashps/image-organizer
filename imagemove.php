<?php
$dir = "myim";
$movDir = "myphoto";
echo "\n Read all the Image from DIR\n";
echo "*********************************\n\n";

$dir = readline("Input Folder Name : ");
$movDir = readline("Output Folder Name : ");


$files1 = scandir($dir);
array_shift($files1);
array_shift($files1);
$totalImages = count($files1);
$completedItems = 0;
echo "Total Items Found ". $totalImages;
echo "\n\n";
if ($totalImages > 0) {
    foreach ($files1 as $file) {
        
        
        $fileDetails = @exif_read_data($dir . "/" . $file);
        if(!$fileDetails) {
            echo "Not able to read image property\n\n";
            $completedItems++;
            show_status($completedItems,$totalImages);
            continue;
        }
        // print_r($fileDetails);exit;
        $dt = new DateTime();
        $dt->setTimestamp($fileDetails['FileDateTime']);
        $ext = pathinfo($dir . "/" . $file, PATHINFO_EXTENSION);
        $Yeardir = $dt->format('Y');
        $monthdir = $dt->format('M');
        $daydir = $dt->format('d');
        
        if (! file_exists("$movDir/" . $Yeardir)) {
            mkdir("$movDir/" . $Yeardir, 0777);
        }
        if (! file_exists("$movDir/" . $Yeardir . "/$monthdir")) {
            mkdir("$movDir/" . $Yeardir . "/$monthdir", 0777);
        }
        if (! file_exists("$movDir/" . $Yeardir . "/$monthdir/$daydir")) {
            mkdir("$movDir/" . $Yeardir . "/$monthdir/$daydir", 0777);
        }
        $photoDir = $Yeardir . "/$monthdir/$daydir";
        If (copy("$dir/" . $fileDetails['FileName'], "$movDir/" . $photoDir . "/" . $dt->format('d_F_H_i_s') . "_" . $fileDetails['FileName'])) {
           // unlink($dir . "/" . $file);
        }
        //print "\n\n" . $dt->format('j_M_Y_H_i_s');
        $completedItems++;
        show_status($completedItems,$totalImages);
    }
}


function show_status($done, $total, $size=30) {
 
    static $start_time;
 
    // if we go over our bound, just ignore it
    if($done > $total) return;
 
    if(empty($start_time)) $start_time=time();
    $now = time();
 
    $perc=(double)($done/$total);
 
    $bar=floor($perc*$size);
 
    $status_bar="\r[";
    $status_bar.=str_repeat("=", $bar);
    if($bar<$size){
        $status_bar.=">";
        $status_bar.=str_repeat(" ", $size-$bar);
    } else {
        $status_bar.="=";
    }
 
    $disp=number_format($perc*100, 0);
 
    $status_bar.="] $disp%  $done/$total";
 
    $rate = ($now-$start_time)/$done;
    $left = $total - $done;
    $eta = round($rate * $left, 2);
 
    $elapsed = $now - $start_time;
 
    $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";
 
    echo "$status_bar  ";
 
    flush();
 
    // when done, send a newline
    if($done == $total) {
        echo "\n";
    }
 
}