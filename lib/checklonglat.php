<?php

/**
 * 
 * @checklonglat.php
 * 
 * Check whether the uploaded image, uploadImg, contains the longitute and latitude in the exif data.
 * 
 */

$file = $_FILES['uploadImg'];
$exif_data = exif_read_data($file['tmp_name']);

$img_lat = $exif_data['GPSLatitude'];
$img_long = $exif_data['GPSLongitude'];

if (empty($img_lat) && empty($img_long)) {
    echo "neither";
} else if (empty($img_long)) {
    echo "nolong";
} else if (empty($img_lat)) {
    echo "nolat";
} else {
    echo "both";
}
