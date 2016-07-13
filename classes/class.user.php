<?php

class User
{
    protected $username;
    protected $password;
    protected $db;
    protected $helper;
    
//=====================================================
    
    public function __construct($db, $helper)
    {
        $this->db = $db;
        $this->helper = $helper;
    }
    
//=====================================================
 
    public function userCheck()
    {
        $username = $this->helper->specChars($_POST["usernamefield"]);
        
        if (isset ($_POST["passwordfield"]))
        {
            $password = $this->helper->specChars($_POST["passwordfield"]);
            $password .= $this->getSalt($username);
            $password = hash("sha256", $password);
        }
        
        else
        {
            $password = "";
        }
        
        $pass = $this->checkUserCredentials($username);
        
        if ($this->helper->specChars($password) === $pass)
        {
            $_SESSION["username"] = $username;
            return true;
        }
        else
        {
            return false;
        }
    }
    
//=====================================================
    
    public function loggedUser()
    {
        return (isset($_SESSION["username"]) != "");
    }
    
//=====================================================
      
    public function userLogout()
    {
        session_destroy();
        header("location: index.php?page=home");
    }
    
    //=====================================================
    
    public function makeAdmin($id)
    {
        $sql = 'UPDATE users SET permission = 2 WHERE id = (:id)';            
        $userdata = array(':id' => $id);
        return $this->db->doAction($sql, $userdata);
    }
    
    //=====================================================
    
    public function getAdminUsers()
    {
        $sql = 'SELECT * FROM users WHERE permission = 2';
        $stm = $this->db->prepareStatement($sql);
        return $this->db->executeGetArrays($stm);
    }
    
    //=====================================================
    
    public function getRegularUsers()
    {
        $sql = 'SELECT * FROM users WHERE permission = 1';
        $stm = $this->db->prepareStatement($sql);
        return $this->db->executeGetArrays($stm);
    }
    
    //=====================================================
    
    public function getPermission()
    {
        $username = $_SESSION["username"];

        if (isset($username) && $username !== "")
        {
            $sql = 'SELECT permission FROM users WHERE name=(:name)';
            $stm = $this->db->prepareStatement($sql);
            $stm->bindParam(':name', $username, PDO::PARAM_STR);
            $result = $this->db->executeGetArrays($stm);

            return $result[0]['permission'];
        }
        else 
        {
            return 0;
        }
    }
    
    //=====================================================
    
    protected function checkUserCredentials($username)
    {
        $sql = 'SELECT password FROM users WHERE name=(:username)';
        $stm = $this->db->prepareStatement($sql);
        $stm->bindParam(':username', $username, PDO::PARAM_STR);
        $result = $this->db->executeGetArrays($stm);
        
        return $result[0][0];            
    }
    
    //=====================================================
    
    public function getActiveUserId()
    {
        if (isset($_SESSION["username"]))
        {
            $username = $_SESSION["username"];
        
            if (isset($username) && $username !== "")
            {
                $sql = 'SELECT id FROM users WHERE name="'.$username.'"';
                $statement = PDODAO::prepareStatement($sql);
                $result = PDODAO::getArray($statement);

                return $result[0];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    //===================================================== 
    
    public function pageOwnerIsAdmin($pagename)
    {
        $sql = 'SELECT users_id FROM pages WHERE name = "'.$pagename.'"';
        $statement = PDODAO::prepareStatement($sql);
        $result = PDODAO::getArray($statement);

        $sql2 = 'SELECT permission FROM users WHERE id ='.$result[0].'';
        $statement2 = PDODAO::prepareStatement($sql2);
        $permission = PDODAO::getArray($statement2);

        if ($permission === 2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //=============================================
    
    public function getSalt($name)
    {
        $sql = 'SELECT salt FROM users WHERE name = "'.$name.'"';
        $result = PDODAO::getDataArray($sql);
        return $result['salt'];
    }
    
}