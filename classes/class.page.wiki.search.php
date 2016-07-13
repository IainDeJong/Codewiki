<?php

class SearchPage extends Wiki
{
    protected $tags = array();
    protected $search;
    protected $title;
    var $user;
    var $db;
        
//=========================================
    
    public function __construct($db, $user)
    {
        $this->db = $db;
        $this->user = $user;
        $this->search = new Search();
    }
    
//=============================================
    
    public function searchBox($title = false, $tags = false)
    {
        ob_start();
        $this->tags = $this->search->getTags();
        echo '<div id="menusearch"><form method="GET">
            <fieldset>
            <input type="hidden" name="page" value="searchresult">';
            
        if ($title == true)
        {
            echo'<input type="text" name="title" placeholder="page title"><br /><br />';
        }
        
        if ($tags == true)
        {
            foreach ($this->tags as $value)
            {
                echo '<input type="checkbox" name="tagid[]" value="'.$value["id"].'">'.$value["name"].'</input><br>';
            }
        }

        echo '</fieldset>
            <input type="submit" name="submit" value="Commit">
            </form>';
        
        if(!($title == true && $tags == true))
        {
            echo '<button id="advanced">Advanced Search</button></div>';
        }
        ob_end_flush();
    }
    
//=============================================
        
    public function bodyContent()
    {
        $this->searchBox(true);
    }
}