<?php

class Input{
    public static function exists($type='post' || $type='get'){
        switch($type){
            case 'post':
                return(!empty($_POST)) ? true : false;
            case 'get':
                return(!empty($_GET))? true: false;
            default:
                return false;
        }
    }

    public static function get($item, $file=null){
        if(isset($_POST[$item])){
            return $_POST[$item];
        }else if(isset($_GET[$item])){
            return $_GET[$item];
        }else if(isset($_FILES[$item])){
            if ($file!=null){
                return $_FILES[$item][$file];
            }
        }
        return '';
    }

    public static function get_all() {
        if(!empty($_POST)){
            return $_POST;
        } else if(!empty($_GET)){
            return $_GET;
        }
    }

    public static function get_data($data) {
        if(isset($_POST[$data])){
            return $_POST[$data];
        } else if(!empty($_GET[$data])){
            return $_GET[$data];
        }
    }

    public static function image_resize($imgHeight, $imgWidth, $file, $filetype, $filesize)
    {
        $fileContent = file_get_contents($file);
        if ($filesize != 0 && !empty($fileContent) && !empty($filetype)) {
            $targetWidth = $imgHeight; // Set the width you want after resizing
            $targetHeight = $imgHeight; // Set the height you want after resizing
        
            // Create an image resource from the uploaded file
            $img = null;
            if ($filetype == "image/jpeg" || $filetype == "image/jpg") {
                $img = imagecreatefromstring($fileContent);
            } elseif ($filetype == "image/png") {
                $img = imagecreatefromstring($fileContent);
            } elseif ($filetype == "image/gif") {
                $img = imagecreatefromstring($fileContent);
            }

            if ($img !== false && $img !== null) {
                $imgWidth = imagesx($img);
                $imgHeight = imagesy($img);
        
                // Create a new true color image in the desired dimensions
                $resized = imagecreatetruecolor($targetWidth, $targetHeight);
        
                // Preserve transparency for PNG and GIF
                if ($filetype == "image/png" || $filetype == "image/gif") {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                    imagefilledrectangle($resized, 0, 0, $targetWidth, $targetHeight, $transparent);
                }
        
                // Resize the image
                imagecopyresampled($resized, $img, 0, 0, 0, 0, $targetWidth, $targetHeight, $imgWidth, $imgHeight);
        
                // Output the resized image to a buffer
                ob_start();
                if ($filetype == "image/jpeg" || $filetype == "image/jpg") {
                    imagejpeg($resized);
                } elseif ($filetype == "image/png") {
                    imagepng($resized);
                } elseif ($filetype == "image/gif") {
                    imagegif($resized);
                }
                $image_data = ob_get_clean();
        
                // Encode the resized image data to base64
                $base64 = base64_encode($image_data);
                imagedestroy($img);
                imagedestroy($resized);
                return $base64;
            } else {
                return "Error: Failed to create image resource or image data is invalid.";
            }
        } else {
            return "Error: File content, file type, or file size is invalid.";
        }
    }


}

