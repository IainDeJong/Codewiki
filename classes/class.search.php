<?php

class Search
{
    var $tags = array();
    var $pages;
    
//================================================

    public function searchPagesOnNameAndTags($db, $name, $tagarray) 
    {                               
        if ($tagarray !== '')
        {
            $sql = 'SELECT * FROM pages JOIN pages_tags ON pages.id = pages_tags.pages_id WHERE name LIKE (:name) AND (';

            foreach ($tagarray as $key => $value)
            {
                $sql .= 'pages_tags.tags_id = (:'.$key.') OR ';
            }        
            $sql .= '(:bool))';
        }
        else
        {
            $sql = 'SELECT * FROM pages JOIN pages_tags ON pages.id = pages_tags.pages_id WHERE name LIKE (:name)';
        }

        $stm = $db->prepareStatement($sql);

        $nm = '%'.$name.'%';

        if ($tagarray !== '')
        {
            foreach ($tagarray as $key => $value)
            {
                ${$key} = (int)$value;

            }

            if ($tagarray !== '')
            {
                $bl = false;

            }
        }

        $result = $stm->bindParam(':name', $nm, PDO::PARAM_STR);

        if ($tagarray !== '')
        {
            foreach ($tagarray as $key => $value)
            {
                $fiche = ':'.$key;
                $result = $stm->bindParam($fiche, ${$key}, PDO::PARAM_INT);
            }
        }

        if ($tagarray !== '')
        {
            $result = $stm->bindParam(':bool', $bl, PDO::PARAM_BOOL);
        }

        $this->pages = $db->executeGetArrays($stm);

        return $this->pages;
        
    }
    
//================================================
    
    public function showLess()
    {
        $_SESSION['searchresults'] -= 5;

        ob_start();
        if ($_SESSION['searchresults'] < count($_SESSION['searchcache']))
        {
            for($i = 0; $i < 5; $i++)
            {
                $a = $i + $_SESSION['searchresults'];
                echo '<a href="?page=wikipage&id='.$_SESSION['searchcache'][$a].'">'.$_SESSION['searchcache'][$a]."</a><br />";
            }

            echo '<br />';
            if ($_SESSION['searchresults'] >= 1)
            {
                echo '<button id="less">Previous</button></div>';
            }


                echo '<button id="more">Next</button></div>';
        }
        else
        {
            echo 'Error:out of bounds';
        }
        ob_end_flush();
    }
    
//================================================

    public function showMore()
    {
        $_SESSION['searchresults'] += 5;

        ob_start();
        if (($_SESSION['searchresults'] + 5) < count($_SESSION['searchcache']))
        {
             for($i = 0; $i < 5; $i++)
             {
                 $a = $i + $_SESSION['searchresults'];
                 echo '<a href="?page=wikipage&id='.$_SESSION['searchcache'][$a].'">'.$_SESSION['searchcache'][$a]."</a><br />";
             }

             echo '<br /><button id="less">Previous</button></div>';
             if ($_SESSION['searchresults'] >= 5)
             {
                 echo '<button id="more">Next</button></div>';
             }

        }
        else
        {
             for($i = 0; $i < 5; $i++)
             {
                 $a = $i + $_SESSION['searchresults'];
                 echo '<a href="?page=wikipage&id='.$_SESSION['searchcache'][$a].'">'.$_SESSION['searchcache'][$a]."</a><br />";

                 if (($a + 1) == count($_SESSION['searchcache']))
                 {
                     break;
                 }
             }
             echo '<br /><button id="less">Previous</button></div>';
        }
        ob_end_flush();
    }
    
//==================================================

    public function getTags()
    {
        $sql = 'SELECT * FROM tags';
        return PDODAO::getDataArrays($sql);
    }
    
}