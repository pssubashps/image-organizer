<?php
$dir = "myim";
$movDir = "myphoto";
echo "\n Read all the Image from DIR\n";
echo "*********************************\n\n";
$files1 = scandir($dir);
array_shift($files1);
array_shift($files1);
if (count($files1) > 0) {
    foreach ($files1 as $file) {
        echo "\n$file\n";
        
        $fileDetails = exif_read_data($dir . "/" . $file);
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
        If (copy("$dir/" . $fileDetails['FileName'], "$movDir/" . $photoDir . "/" . $dt->format('d_j_H_i_s') . "_" . $fileDetails['FileName'])) {
           // unlink($dir . "/" . $file);
        }
        print "\n\n" . $dt->format('j_M_Y_H_i_s');
    }
}
