<?php

abstract class Debug
{
    public static function writeToLogFile($logmsg)
    {
        $dateTime = new DateTime("now");
        $dow = $dateTime->format("l");
        $w     = $dateTime->format("W");
        $fn     = "log_".$dow.".txt";
        $file = (is_file($fn)&&$w == date('W',filemtime($fn)))? fopen($fn,"a") : $file = fopen($fn,"w");
        fprintf($file,"%s | %.50s | %s \n",  $dateTime->format("d-m-Y G:i:s"), $_SERVER["REMOTE_ADDR"], $logmsg);
        fclose($file);
    }
    
    
    
   // Debug::writeToLogFile("dit is een beetje code");
}

