<?php

use function PHPUnit\Framework\throwException;

/**
 * reduce the size of the image
 */
function resizeImage($imgSrc, $finalWidth, $imgDest){
    list($width, $height, $imageType) = getimagesize($imgSrc);

    $ratio = $width / $height;
    $newHeight = intval($finalWidth/$ratio);

    //log_message('debug','[image_helper.php] resizeImage(): newHeight: ' . $newHeight);
    //log_message('debug','[image_helper.php] resizeImage(): imageType: ' . $imageType);

    if($imageType == IMAGETYPE_JPEG){
        $src = imagecreatefromjpeg($imgSrc);
    }elseif( $imageType == IMAGETYPE_GIF ) {
        $src = imagecreatefromgif($imgSrc);
    } elseif( $imageType == IMAGETYPE_PNG ) {
        $src = imagecreatefrompng($imgSrc);
    }else{
        throwException(new Exception("Image type is unknown."));
    }
    //log_message('debug','[image_helper.php] resizeImage(): image loaded.');

    $dst = imagecreatetruecolor($finalWidth, $newHeight);
    
    $result = imagecopyresampled($dst, $src, 0, 0, 0, 0, $finalWidth, $newHeight, $width, $height);
    if(! $result){
        //log_message('debug','[image_helper.php] resizeImage(): imagecopyresampled failed.');
        return false;
    }
    //log_message('debug','[image_helper.php] resizeImage(): imagecopyresampled done.');

    if($imageType == IMAGETYPE_JPEG){
        imagejpeg($dst, $imgDest);
    }elseif( $imageType == IMAGETYPE_GIF ) {
        imagegif($dst, $imgDest);
    } elseif( $imageType == IMAGETYPE_PNG ) {
        imagepng($dst, $imgDest);
    }
    //log_message('debug','[image_helper.php] resizeImage(): dest image saved as ' . $imgDest);

    return true;
}

?>