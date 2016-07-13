<?php

require_once("classes/class.interface.controller.php");

class IndexController implements Controller
{
    public function __construct() 
    {
        require_once("classes/class.user.php");
        require_once("classes/class.helpers.php");
        require_once("classes/class.page.php");
        require_once("classes/class.page.wiki.php");
        require_once("classes/class.db.php");
        require_once('classes/class.search.php');
    }
    
//============================================
    
    public function handleRequest()
    {
        try 
        {
            $request = $this->defineRequest();
        } 
        catch (Exception $e) 
        {
            echo 'Error: '.$e->getMessage();
        }        
        
        try
        {
            $subcontroller = $this->requestController($request);
        } 
        catch (Exception $e) 
        {
            echo 'Error: '.$e->getMessage();
        }
        
        $subcontroller->handleRequest();
    }
  
//============================================
    
    public function defineRequest()
    {
        $requestname = null;
        
        if (isset($_POST["ajaxaction"]) || isset($_GET["ajaxaction"]))
        {
            $requestname = 'ajax';
        }
        else if (false)
        {
            $requestname = 'image';
        }
        else
        {
            $requestname = 'page';
        }
        
        if ($requestname != null)
        {
            return $requestname;
        }
        else
        {
            throw new Exception('400');
        }
    }
    
//============================================
    
    public function requestController($req)
    {
        $controller = null;

        switch ($req) 
        {
            case 'ajax':
                require_once('classes/class.controller.ajax.php');
                $controller = new AjaxController();
                break;

            case 'page':
                require_once('classes/class.controller.page.php');
                require_once('classes/class.page.wiki.login.php');
                require_once('classes/class.page.wiki.search.php');
                $controller = new PageController();
                break;

            case 'image':
            default:
                throw new Exception('400');
                break;
        }        
        
        if ($controller != null)
        {
            return $controller;
        }
        else
        {
            throw new Exception('400');
        }        
    }    
}