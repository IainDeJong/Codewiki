<?php

class Searchresult extends Wiki
{
    protected $tag = array();
    protected $title;
    protected $search;
    var $user;
    var $db;
            
//================================================         
    
    public function __construct($db, $user, $tag, $title) 
    {
        if (true)//(is_array($tag))
        {
            $this->tag = $tag;
            $this->title = $title;
            $this->db = $db;
            $this->user = $user;
            $this->search = new Search();
        }
        else
        {
            throw new Exception('Page not found.');
        }
    }

//================================================

    public function seekTitleTag($tags, $title)
    {
        $pages = array();
        $foundpages = $this->search->searchPagesOnNameAndTags($this->db, $title, $tags);
                    
        foreach($foundpages as $key => $value)
        {
            $pages[] = $value["name"];
        }
                
        if (isset($_SESSION["searchcache"]))
        {
            unset($_SESSION["searchcache"]);
        }
            
        $_SESSION["searchcache"] = array_merge(array_flip(array_flip($pages)));
                
        ob_start();
        echo '<div class="more">';
        
        for($i = 0; $i < 5; $i++)
        {
            echo '<a href="?page=wikipage&id='.$_SESSION['searchcache'][$i].'">'.$_SESSION['searchcache'][$i]."</a><br />";

            if (($i + 1) == count($_SESSION['searchcache']))
            {
                break;
            }
        }

        if (count($_SESSION['searchcache']) >= 5)
        {
             echo '<br /><button id="more">Next</button></div>';
        }
        
        $_SESSION['searchresults'] = 0;
        
        echo '</div>';       
        ob_end_flush(); 
    }
    
//================================================ 
        
    function bodyContent()
    {
        $this->seekTitleTag($this->tag, $this->title);
    }
}