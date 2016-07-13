<?php

class Editor
{
    public function converterScrambledToShow($string)
    {
        
        /*
         * image [img] [/img]
         * link [link] [linkttext] [/link]
         * break [br]
         * bold [b] [/b]
         * h1   [h1] [/h1]
         * h2   [h2]  [/h2]
         * h3   [h3]  [/h3]
         * h4   [h4]  [/h4]
         */
        
        $find = array('[img]','[/img]','[link]','[linktext]','[/link]','[br]','[b]','[/b]','[h1]','[/h1]','[h2]','[/h2]','[h3]','[/h3]','[h4]','[/h4]','[youtube]','[/youtube]','https://www.youtube.com/watch?v=');
        
        $replace = array('<img src="','" id="wikiimage">','<a href="','">','</a>','<br>','<b>','</b>','<h1>','</h1>','<h2>','</h2>','<h3>','</h3>','<h4>','</h4>','<iframe width="420" height="315" src="http://www.youtube.com/embed/','"></iframe>','');
                
        $content = str_replace($find,$replace,$string);  
                                    
        return $content;           
    }
    
//======================================================
    
    public function arrayScrambler(&$tags)
    {
        foreach ($tags as &$value)
        { 
            $value = htmlspecialchars($value, ENT_QUOTES, "UTF-8"); 
        }
        
        return $tags;
    }

//======================================================
    
    public function saveExistingPageToDatabase($title, $content, $tags, $id, $db)
    {
        $sql= 'UPDATE pages SET name=(:title), content=(:content), lastedit="'.date('Y-m-d G:i:s').'" WHERE id=(:id)';            
        $userdata = array(':title' => $title, ':content' => $content, ':id' => $id);
        $result = $db->doAction($sql, $userdata);

        $sql= 'DELETE FROM pages_tags WHERE pages_id=(:id)';        
        $userdata = array(':id' => $id);
        $db->doAction($sql, $userdata);

        foreach ($tags as $value)
        {
            $sql= 'INSERT INTO pages_tags (pages_id, tags_id) VALUES ((:id), (:tags))';
            $userdata = array(':id' => $id, ':tags' => $value);
            $db->doAction($sql, $userdata);
        }
        
        if ($result !== false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //==============================================
    
    public function savePageToDatabase($title, $content, $tags, $db)
    {
        $sql= 'SELECT id FROM users WHERE name = (:name)';
        $stm = $db->prepareStatement($sql);
        $stm->bindParam(':name', $_SESSION["username"], PDO::PARAM_STR);
        $result = $db->executeGetArrays($stm);
                
        $user_id = (int)$result[0][0];
        
        $sql= 'INSERT INTO pages (name, content, users_id) VALUES
             ((:title), (:content), (:userid))';
        $userdata = array(':title' => $title, ':content' => $content, ':userid' => $user_id);
        
        $effect = $db->doAction($sql, $userdata);
        

        $sql= 'SELECT id FROM pages WHERE name= (:nama)';
        $stm = $db->prepareStatement($sql);
        
        $stm->bindParam(':nama', $title, PDO::PARAM_INT);
        $result2 = $db->executeGetArrays($stm);
        
        foreach ($tags as $value)
        {
            $sql= 'INSERT INTO pages_tags (pages_id, tags_id) VALUES ((:pageid), (:tagid))';
            $userdata = array(':pageid' => $result2[0][0], ':tagid' => $value);
            $db->doAction($sql, $userdata);
        }
    }
    
//==================================================
    
    public function selectPagesOnName($name, $db)
    {
       $sql = 'SELECT * FROM pages WHERE name=(:name)';
       $stm = $db->prepareStatement($sql);
       $stm->bindParam(':name', $name, PDO::PARAM_STR);
       $result = $db->executeGetArrays($stm);
       return $result[0];
    }    
    
    //=============================================        

    public function selectPagesName($name)
    {
        $sql = 'SELECT * FROM pages JOIN pages_tags ON pages.id = pages_tags.pages_id JOIN tags ON pages_tags.tags_id = tags.id WHERE pages.name = "'.$name.'"';
        return PDODAO::getDataArrays($sql);
    }
    
    //=============================================        

    public function getTagsOnPage($pageid)
    {
        $sql = 'SELECT tags_id FROM pages_tags WHERE pages_id="'.$pageid.'"';
        $statement = PDODAO::prepareStatement($sql);

        $result = PDODAO::getArrays($statement);

        foreach ($result as $value)
        {
            $results[]=$value[0];
        }
        return $results;    
    }
}