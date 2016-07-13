<?php

class Captcha
{
    var $image;
    
//=========================================
        
    public function make()
    {
        //this is not very good at all
        $code=rand(10000,99999);
        $_SESSION["code"]=$code;
        $im = imagecreatetruecolor(60, 24);
        $bg = imagecolorallocate($im, 22, 86, 165); //background color blue
        $fg = imagecolorallocate($im, 255, 255, 255);//text color white
        imagefill($im, 0, 0, $bg);
        imagestring($im, 5, 5, 5,  $code, $fg);
        //header("Cache-Control: no-cache, must-revalidate");
        //header('Content-type: image/png');
        $targetfile = "captcha.png";
        $this->image = imagepng($im,$targetfile);
        //imagedestroy($im); //will you end up destroying the image file?
    }
    
    //if you don't end up doing this via index.php you could write a destroyer fuction here that
    //destroys the image that was created
}