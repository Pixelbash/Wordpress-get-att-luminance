<?php

// We will use this to get and set meta and figure out 
// if we need a light or dark header for each given image

function getImageAverage($att_id = false) {
    if($att_id) {
        $file_path = get_attached_file( $att_id );
        return getAvgLuminance($file_path);
    }
    return 0;
}

function getAvgLuminance($filename, $num_samples=10) {
    // needs a mimetype check
    $img = imagecreatefromjpeg($filename);

    $width = imagesx($img);
    $height = imagesy($img);

    $x_step = intval($width/$num_samples);
    $y_step = intval($height/$num_samples);

    $total_lum = 0;
    $sample_no = 1;

    for ($x=0; $x<$width; $x+=$x_step) {
        for ($y=0; $y<$height; $y+=$y_step) {

            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            // choose a simple luminance formula from here
            // http://stackoverflow.com/questions/596216/formula-to-determine-brightness-of-rgb-color
            $lum = ($r+$r+$b+$g+$g+$g)/6;

            $total_lum += $lum;
            $sample_no++;
        }
    }

    // work out the average
    $avg_lum  = $total_lum / $sample_no;

    return ($avg_lum / 255) * 100;
}