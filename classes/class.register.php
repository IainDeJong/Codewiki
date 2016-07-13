<?php

class Register
{
    var $db;
    var $user;
    
//=========================================    
    
    public function __Construct($db, $user)
    {
        $this->db = $db;
        $this->user = $user;
    }
    
//=========================================
    
    function makeSalt()
    {
        $salt = mcrypt_create_iv(32);
        if ($salt == true)
        {
            return $salt;
        }
        else
        {
            return false;
        }
    }
    
//=========================================
    
    function saveUserData()
    {
        $salt = $this->makeSalt();
                 
        if (is_string($salt))
        {
            $usern = htmlspecialchars($_POST["regusername"], ENT_QUOTES, "UTF-8");
            $pasw = htmlspecialchars($_POST["regpw"], ENT_QUOTES, "UTF-8");
            $pasw .= $salt;
            $pasw = hash("sha256",$pasw);
           
            $sql = 'INSERT INTO users(name, password, permission, salt) VALUES ((:user),(:pass),(:status), (:salt))';
            
            $st = (int)1;
            
            $userdata = array(':user' => $usern, ':pass' => $pasw, ':status' => $st, ':salt' => $salt);
            $result = $this->db->doAction($sql, $userdata);
            return $result;
        }
        else
        {
            return false;
        }
    }
    
//=============================================
        
}