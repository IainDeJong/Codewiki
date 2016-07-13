<?php

class Wikipage extends Wiki
{
    protected $pagename = "";
    protected $wikipage = array();
    protected $editor;
    protected $rating;
    var $db;
    var $user;    

//===================================================    
    
    public function __construct($pagename, $db, $user) 
    {
        if(is_string($pagename))
        {
            $this->pagename = htmlspecialchars($pagename, ENT_QUOTES, "UTF-8");
            $this->db = $db;
            $this->user = $user;
            $this->editor = new Editor();
            $this->rating = new RatingSystem($db);
        }
        else
        {
            throw new Exeption('404');
        }
    }
    
//===================================================

    protected function wikiContent()
    {
        $this->wikipage = $this->editor->selectPagesName($this->pagename);

        if ($this->wikipage != null)
        {
            ob_start();
            $content = $this->editor->converterScrambledToShow($this->wikipage[0]["content"]);

            echo '<legend>
                <h3 id="wikigentitle">'.$this->wikipage[0][1].'</h3></legend>
                <p id="wikigencontent">'.$content.'
                </p>
                <form method="POST" action="index.php?page=editor&id='.$this->pagename.'">
                <input type="hidden" name="page" value="editor">';
            echo '<div class="pageend">_________________________________________________________________<br />';
            if ($this->user->loggedUser())    
            {
                echo '<br /><input type="submit" name="editbutton" value="Edit">';
            }

            echo '</form> 
                <p id="wikigentags"></p>';
            echo '<br />| ';

            foreach ($this->wikipage as $value)
            {
                echo '<b>'.$value[10].'</b> | ';
            }

            $pageid = $this->wikipage[0][0];

            echo '<br /><br /><div id="ratingshow"><p id=ratingshowref>Rating: <b>'.$this->rating->ratingShow($pageid).'/10</b></p></div><br />';

            if (is_array($this->rating->checkPageRated($pageid)))
            {
                if (in_array($this->user->getActiveUserId(), $this->rating->checkPageRated($pageid)))
                {
                    echo "";
                }
                else
                {
                    echo $this->rating->ratingFormShow($pageid, $this->user);
                }
            }
            else
            {
                echo $this->rating->ratingFormShow($pageid, $this->user);
            }
            echo '<br /><br /></div>';
            ob_end_flush();
        }
        else
        {
            echo "Error: 404";
        }
    }

//===================================================

    public function bodyContent() 
    { 
        $this->wikiContent();
    }
}