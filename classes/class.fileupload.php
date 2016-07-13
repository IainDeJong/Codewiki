<?php

/* 
This class contains the load()function that uploads a file to the server after resizing it and convering it to a jpg.
 */

class Fileuploader
{
    function load()
    {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) 
        {
            echo "File is an image - " . $check["mime"] . ".<br/>";
            $uploadOk = 1;
        }
        else 
        {
            echo "File is not an image.<br/>";
            $uploadOk = 0;
        }
        
        // Check if file already exists
        if (file_exists($target_file)) 
        {
            echo "Sorry, file already exists.<br/>";
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) 
        {
            echo "Sorry, your file is too large.<br/>";
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        if(
            $imageFileType != "jpg"  && 
            $imageFileType != "png"  && 
            $imageFileType != "jpeg" && 
            $imageFileType != "gif" ) 
        {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br/>";
            $uploadOk = 0;
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) 
        {
            echo "Sorry, your file was not uploaded.<br/>";
        } 
        // if everything is ok, try to upload file
        else 
        {
            //we will try to re-create the image
            $img_quality = 70;
            $im = imagecreatefromstring(file_get_contents($_FILES["fileToUpload"]["tmp_name"]));
            $im_w = imagesx($im);
            $im_h = imagesy($im);
            $tn = imagecreatetruecolor($im_w, $im_h);
            imagecopyresampled ( $tn , $im, 0, 0, 0, 0, $im_w, $im_h, $im_w, $im_h );
            $result = imagejpeg($tn,$target_file,$img_quality);
            $result;
            //if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
        }

        if ($result)
        {
                echo "<h3>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h3>";
                echo '<img src="'.$target_file.'" /><br/>';
                echo '<a href="'.$target_file.'" target="_blank">Show in new tab</a><br/>';
                echo '<a href="'.$target_file.'" download="'.basename( $_FILES["fileToUpload"]["name"]).'">Download</a><br/>';
        } 
        else 
        {
                echo "Sorry, there was an error uploading your file.";
        }
    }   
}
