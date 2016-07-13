<?php

class LoginPage extends Wiki
{
    public function showLogin() 
    {
        echo '
            <div id="menulogindiv"><form method="POST">
            <input type="hidden" name="page" value="login">
            <fieldset>            
            <input type="text" name="usernamefield" placeholder="Username" required title="Required field">
            <br>
            <input type="password" name="passwordfield" placeholder="Password" required title="Required field">
            <br>
            <input type="submit" name="loginsubmit" value="Login">
            </form>
            <form method="POST">
            <input type="hidden" name="page" value="register">
            <input type="submit" name="submit" value="Register"></fieldset>
            </form></div>';
    }
    
//=========================================
    
    public function bodyContent()
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET" && $this->user->loggedUser() === true)
        {
            // activates when a user visist the login page when already logged in
            echo '<meta http-equiv="refresh" content="0" URL="/index.php?page=home">';
        }
        
        elseif ($_SERVER["REQUEST_METHOD"] === "POST" && $this->user->userCheck() === true)
        {
            // Activates when a user logs in from logged out state.
            echo '<meta http-equiv="refresh" content="0" URL="/index.php?page=home">';
        }
        
        elseif ($_SERVER["REQUEST_METHOD"] === "POST" && $this->user->userCheck() !== true)
        {
            // activates when a user tries to log in from a logged out state but fails the
            // userCheck(), and thus, provided wrong login credentials.
            
            echo 'Login failed.';
        }
        
        else
        {
            $this-> showLogin();
        }
    }
}