<?php

class PageController implements Controller
{
    var $db;
    var $user;
    var $ispostrequest;
    
//=============================================================== 
      
    public function __construct()
    {
        $this->db = new Database();
        $this->helper = new Helpers();
        $this->user = new User($this->db, $this->helper);

        if ($_SERVER["REQUEST_METHOD"]==="POST")
        {
            $this->ispostrequest = true;
        }
        else
        {
            $this->ispostrequest = false;
        }
    }    
    
//===============================================================    
    
    public function handleRequest() 
    {
        $pagevar = $this->defineRequest();
        
        try
        {
            $page = $this->requestController(htmlspecialchars($pagevar, ENT_QUOTES, "UTF-8"));
        } 
        catch (Exception $e) 
        {
            echo 'Error: '.$e->getMessage();
        }
               
        $page->show();
    }
    
//==============================================================

    public function defineRequest() 
    {
        $key = "page";
        $result = $this->helper->arrayChecker($key);
        if (is_string($result))
        {
            return $result;
        }
        else
        {
            return 'home';
        }        
    } 
   
//==============================================================
    
    public function requestController($req)
    {
        $page = null;        
        
        switch ($req)
        { 
            case "wikipage":
                require_once("classes/class.page.wiki.wikipage.php");
                require_once("classes/class.editor.php");
                require_once("classes/class.rating.php");
                $id = $this->helper->specChars($this->helper->arrayChecker("id"));
                $page = new Wikipage($id, $this->db, $this->user);  
                break;
            
            case "promote":
                require_once("classes/class.page.wiki.userpanel.php");
                $newadmin = $this->helper->specChars($this->helper->arrayChecker("id"));
                $page = new Userpanel($this->db, $this->user, $newadmin);
                break;
                
            case "loadfile":
                require_once("classes/class.fileupload.php");
                require_once("classes/class.page.wiki.fileupload.php");
                $page = new FileUpload($this->db, $this->user);
                break;
            
            case "searchresult":
                require_once("classes/class.page.wiki.searchresult.php");
                $title = $this->helper->arrayChecker("title", "");
                $array = $this->helper->arrayChecker("tagid", "");
                $page = new Searchresult($this->db, $this->user, $array, $title);
                break;
                
            case "search":
                require_once("classes/class.page.wiki.search.php");
                $page = new SearchPage($this->db, $this->user);
                break;
            
            case "userpanel":
                require_once("classes/class.page.wiki.userpanel.php");
                $page = new Userpanel($this->db, $this->user);
                break;
            
            case "login":
                require_once("classes/class.page.wiki.login.php");
                $page = new LoginPage($this->db, $this->user);
                break;
            
            case "editor":
                require_once("classes/class.page.wiki.editor.php");
                require_once("classes/class.editor.php");
                $page = new EditorPage($this->db, $this->user);
                break;
            
            case "register":
                require_once("classes/class.page.wiki.register.php");
                require_once('classes/class.captcha.php');
                require_once('classes/class.register.php');
                $page = new RegisterPage($this->db, $this->user);
                break;
            
            case "logout":
                $this->user->userLogout();
                break;
            
            case "home":                
            default:
                include_once("classes/class.page.wiki.home.php");
                $page = new Home($this->db, $this->user);
        }        
        
        if ($page != null)
        {
            return $page;
        }
        else
        {
            throw new Exception('400');
        }
    }
}