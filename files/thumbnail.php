<?php
include_once("connection.php");

function make_thumb($src, $dest, $desired_width, $desired_h) {
    /* read the source image */
    $imagetype = exif_imagetype($src);
    if ($imagetype == "2") {
        $source_image = imagecreatefromjpeg($src);
        $width        = imagesx($source_image);
        $height       = imagesy($source_image);

        $desired_height = $desired_h;

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
        header('Content-Type: image/jpeg');
        /* create the physical thumbnail image to its destination */
        imagejpeg($virtual_image);
    } else if ($imagetype == "3") {
        $source_image = imagecreatefrompng($src);
        $width        = imagesx($source_image);
        $height       = imagesy($source_image);

        $desired_height = $desired_h;

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        header('Content-Type: image/png');
        /* create the physical thumbnail image to its destination */
        imagepng($virtual_image);
    }
}

if (isset($_GET['src']) && $_GET['src'] != "") {
    if (file_exists($_GET['src'])) {
        make_thumb( baseurl . $_GET['src'], "", $_GET["w"], $_GET["h"]);
    } else {
        make_thumb( baseurl ."images/pexels-photo-355988.jpeg", "", $_GET["w"], $_GET["h"]);
    }
}
?>
