<?php

function compressImage($source, $destination, $quality) {

  $info = getimagesize($source);
  if ($info['mime'] == 'image/jpeg') 
    $image = imagecreatefromjpeg($source);

  elseif ($info['mime'] == 'image/gif') 
    $image = imagecreatefromgif($source);

  elseif ($info['mime'] == 'image/png') 
    $image = imagecreatefrompng($source);

// Save the image
  if ($info['mime'] == 'image/jpeg')    { imagejpeg($image, $destination, $quality); }
  elseif ($info['mime'] == 'image/gif') { imagegif($image, $destination); }
  elseif ($info['mime'] == 'image/png') {
      // For PNG, convert quality to compression level: 0 (no compression) to 9
      $compressionLevel = round((100 - $quality) / 10);
      imagepng($image, $destination, $compressionLevel); }

  imagedestroy($image);
 
}

function resizeImage($source, $destination, $width, $height) {
    // Get image info
    $imageInfo = getimagesize($source);
    $mime = $imageInfo['mime'];

    // Create a new image from file
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            return false; // Unsupported image type
    }

    // Create a new true color image
    $newImage = imagecreatetruecolor($width, $height);

    // Copy and resize part of an image
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $imageInfo[0], $imageInfo[1]);

    // Save the resized image
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($newImage, $destination, 90); // Quality: 0 (worst) to 100 (best)
            break;
        case 'image/png':
            imagepng($newImage, $destination, 9); // Compression level: 0 (no compression) to 9
            break;
        case 'image/gif':
            imagegif($newImage, $destination);
            break;
    }

    // Free up memory
    imagedestroy($image);
    imagedestroy($newImage);
    return $destination;
}

function getImageExtensionFromBlob($imageData) {
    // Create a new finfo instance to determine the MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($imageData);
    // Determine the file extension based on the MIME type
    switch ($mimeType) {
        case 'image/jpeg': return 'jpg';
        case 'image/png': return 'png';
        case 'image/gif': return 'gif';
        case 'image/bmp': return 'bmp';
        default: return null; // Unknown or unsupported image type
    }}




?>