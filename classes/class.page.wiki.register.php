<?php

class RegisterPage extends Wiki
{
    protected $register;
    protected $captcha;    
    var $db;
    var $user;
    
//=========================================
    
    public function __Construct($db, $user)
    {
        $this->register = new Register($db, $user);
        $this->captcha = new Captcha;
        $this->db = $db;
        $this->user = $user;
    }
    
//=========================================    

    protected function showRegisterForm() 
    { 
        ob_start();
        echo '<b>Register</b>';

        echo '<form name="register" action="" method="POST">'
                . '<input type="hidden" name="page" value="register">';

        echo '
                Username: 
                <input type="text" name="regusername" value="" input pattern=".{5,}" required title="Required field; 5 characters minimum"><br />
                ';

        echo '
                Wachtwoord: 
                <input type="password" name="regpw" value="" input pattern=".{5,}" required title="Required field; 5 characters minimum"><br /><br />
                CaptCha:
                <input name="captcha" type="text">';
        
        //TODO: you want to change this into a call to index.php GETS ROUTED in the controller
        
        $this->captcha->make();
        
        echo '<img src="captcha.png"/><br>';                
        echo '<input type="submit" name="registerbutt" value="Register Now" /><br />';
        echo '</form>';
        ob_end_flush();
    }
        
//=========================================
    
    protected function showRegFormFilled() 
    { 
        $success = $this->register->saveUserData();
        if ($success == true)
        {
            echo ' and thank you for registering!';
        }
        else
        {
            echo ' but registration failed!';
        }
    }
    
//=========================================
    
    public function bodyContent()
    {
        if ((isset($_POST["registerbutt"])) && $_SESSION['register'] === true)
        {
            $_SESSION['register'] = false;
            
            if(isset($_POST["captcha"]) && $_POST["captcha"]!="" && $_SESSION["code"]==$_POST["captcha"])
            {
                echo 'captcha correct!';
                $this->showRegFormFilled();
            }
            else
            {
                echo 'captcha invalid';
            }
        }
        else
        {
            $_SESSION['register'] = true;
            $this->showRegisterForm();
        }
    }
}