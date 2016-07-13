<?php

/* 
 * this helper class contains usefull functions
 */

class Helpers
{   
    public function arrayChecker($keyname, $defaultreturn = false)
    {
        if ($_SERVER["REQUEST_METHOD"]==="POST")
        {
            if (isset($_POST[$keyname]))
            {
                return $_POST[$keyname];
            }
            else
            {
                return $defaultreturn;
            }
        }
        
        if ($_SERVER["REQUEST_METHOD"]==="GET")
        {
            if (isset($_GET[$keyname]))
            {
                return $_GET[$keyname];
            }
            else
            {
                return $defaultreturn;
            }
        }
        return $defaultreturn;
    }
      
//==================================================================    
    
    public function specChars($string)
    {
        htmlspecialchars($string ,ENT_QUOTES, "UTF-8");
        return $string;
    }
}
