<?php

class AjaxController implements Controller
{
    public function __construct() 
    {
        $this->db = new Database();
        $this->helper = new Helpers();
        $this->user = new User($this->db, $this->helper);
    }
    
//===============================================================
    
    public function handleRequest()
    {
        try
        {
            $ajaxvar = $this->defineRequest();
        } 
        catch (Exception $e) 
        {
            echo 'Error: '.$e->getMessage();
        }
        
        $ajax = $this->requestController(htmlspecialchars($ajaxvar, ENT_QUOTES, "UTF-8"));
        
        //$ajaxaction = htmlspecialchars($pagevar, ENT_QUOTES, "UTF-8");
    }
        
//===============================================================    
    
    public function defineRequest()
    {
        $key = "ajaxaction";        
        $result = $this->helper->arrayChecker($key);
        
        if (is_string($result))
        {
            return $result;
        }
        else
        {
            throw new Exception('400one');
        }
    }
    
//===============================================================    

    public function requestController($req)
    {
        $ajax = null;
        
        switch($req)
        {
            case 'rating':
               require_once("classes/class.rating.php");
               $rater = new RatingSystem($this->db);
               $score = htmlspecialchars($_POST["number"], ENT_QUOTES, "UTF-8");
               $id = htmlspecialchars($_POST["pageid"], ENT_QUOTES, "UTF-8");
               $userid = htmlspecialchars($_POST["userid"], ENT_QUOTES, "UTF-8");
               $rater->ratingCalc($id, $score, $userid);
               break;
               
            case 'advanced':
               require_once("classes/class.page.php");
               require_once("classes/class.page.wiki.php");
               require_once("classes/class.page.wiki.search.php");
               $searcher = new SearchPage($this->db, $this->user);
               $searcher->searchBox($this->db, true, true);
               break;
           
            case 'more':                              
               require_once("classes/class.page.wiki.search.php");
               $search = new Search();
               $search->showMore();
               break;
           
            case 'less':
               require_once("classes/class.page.wiki.search.php");
               $search = new Search();
               $search->showLess();
               break;
        }      
    }
}