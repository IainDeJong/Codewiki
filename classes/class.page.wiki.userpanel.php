<?php
	     
class Userpanel extends Wiki
{
    protected $users = array();
    protected $admins = array();
    protected $newadmin;
    var $db;
    var $user;
    
    
    //=====================================================
    
    public function __construct($db, $user, $newadmin = false) 
    {
        $this->db = $db;
        $this->user = $user;
        $this->newadmin = $newadmin;
    }
        
    //=====================================================

    protected function displayUsers()
    {
        if ($this->newadmin !== false)
        {
            $this->user->makeAdmin($this->newadmin);
        }
        
        ob_start();
        echo "<b>Users:</b><br />";
        echo "<br />";
        
        $this->users = $this->user->getRegularUsers();
        foreach ($this->users as $key => $value)
        {
            
            
            if ($this->user->getPermission() == 2)
            {
                $reg = '<form name="promote" action="" method="POST">';
                $reg .= ' <input type="hidden" name="page" value="promote"><input type="hidden" name="id" value="'.$value['id'].'"><input type="submit" name="register" value="Make Admin" /> '.$value['name'].'<br />';
		$reg .= '</form>';
                echo $reg;
            }
            else
            {
                echo $value['name'];
            }
            echo "";
        }
        
        echo "<br />";
        echo "<b>Administrators:</b><br />";
        echo "<br />";
        
        $this->admins = $this->user->getAdminUsers();
        foreach ($this->admins as $key => $value)
        {
            echo $value['name'];
            echo "<br />";
        }
        ob_end_flush();
    }
    
    //=====================================================

    public function bodyContent() 
    { 
        if ($this->user->loggedUser() === true)
        {
            $this->displayUsers();
        }
        else
        {
            echo 'please log in to make use of this functionality'; 
        }
    }
}
