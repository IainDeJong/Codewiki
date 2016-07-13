<?php

class EditorPage extends Wiki
{
    protected $editor;
    protected $search;
    var $db;
    var $user;
        
//======================================================
    
    public function __construct($db, $user) 
    {
        $this->editor = new Editor();
        $this->db = $db;
        $this->user = $user;
        $this->search = new Search();
    }
    
//======================================================
    
    protected function createPageForm()
    {
        ob_start();
        echo '<script type="text/javascript" src="javascript/popup.js"></script>
            <div><form method="POST">
            <div class="editorgenfield">
            <legend><b>Edit wiki page:</b></legend>
            <input type="hidden" name="page" value="editor">
            <input class="titlefield" type="text" name="wikititle" placeholder="Page Title" required>
            

            <br /><b>Page editor:</b><br />
            <textarea name="pageeditor" id="editorfield" required ></textarea>
            

            <form><div class="searchtagsfield">
            <legend><b>Search tags:</b></legend>';




        $this->tags = $this->search->getTags();
             
        foreach ($this->tags as $value)
        {
            echo '<input type="checkbox" name="tag[]" 
                value="'.$value["id"].'">'.$value["name"].'</input><br>';
        } 

        echo '</div>
            '.$this->inputButtonMenu().'
            <br /><br />
            <input class="commitbutton" id="reeditbuttonjs" type="submit" name="submitnewpage" value="Commit">
            </div></form></div>';
        ob_end_flush();
    }
    
//======================================================
       
    protected function createPageFormFilled()
    {
        if (isset($_POST["tag"]))
        {
            $tags = $_POST["tag"];
        }
        else
        {
            $tags[0] = 1;
        }
        
        $title = htmlspecialchars($_POST["wikititle"], ENT_QUOTES, "UTF-8"); 
        $content = htmlspecialchars($_POST["pageeditor"], ENT_QUOTES, "UTF-8");
        
        $this->editor->arrayScrambler($tags);
        $result = $this->editor->savePageToDatabase($title, $content, $tags, $this->db);
        
        if ($result = true)
        {
            echo "Page uploaded.";
        }
        else
        {
            echo "Error: upload failed.";
        }        
    }

//======================================================
    
    protected function InputButtonMenu()
    {  
        return '<div style="display:inline-block; float:right; margin-right:17%; margin-top:-27%; position:relative">
             <legend><b>Editor input:</b></legend>
            <script type="text/javascript" src="javascript/editorbuttons.js"></script>
            <input type="button" value="image" onclick="addImgToField();">
            <input type="button" value="link" onclick="addLinkToField();">
            <br>
            <input type="button" value="Break" onclick="addBreakToField();">
            <input type="button" value="bold" onclick="addBoldToField();">
            <br>
            <input type="button" value="h1" onclick="addH1ToField();">
            <input type="button" value="h2" onclick="addH2ToField();">
            <br>
            <input type="button" value="h3" onclick="addH3ToField();">
            <input type="button" value="h4" onclick="addH4ToField();">
            <br>
            <input type="button" value="youtube" onclick="addYoutubeToField();">
            </div>
            ';   
    }

//======================================================
    
    protected function editPageForm($pagename)
    {
        $page = $this->editor->selectPagesOnName(htmlspecialchars($pagename, ENT_QUOTES, "UTF-8"), $this->db);
        
        $title = $page[1];
        $content = $page[2];
        $id = $page[0];
        
        $tags = $this->search->getTags();
        $validtags = $this->editor->getTagsOnPage($id);
        
        ob_start();    
        echo '
            <script type="text/javascript" src="javascript/popup.js"></script>
            <div><form method="POST">
            <div class="editorgenfield">
            <legend><b>Edit wiki page:</b></legend>
            <input class="titlefield" type="text" name="wikititle" value="'.$title.'" required>
            <br><b>Page editor:</b><br>
            <textarea id="editorfield" name="pageeditor">'.$content.'</textarea>
            <div="searchtagsfield">
            <legend><b>Search tags:</b></legend>';
            

        foreach ($tags as $value)
        {
            
            if (in_array($value[0], $validtags))
            {
                echo '<input type="checkbox" name="tag[]" value='.$value[0].' checked> '.$value[1].'<br>';
            }
            else
            {
                echo '<input type="checkbox" name="tag[]" value='.$value[0].'>'.$value[1].'<br>';
            }
        }
     
        echo '</div>
            '.$this->inputButtonMenu().'
                <br />
                <input type="hidden" name="pageid" value='.$id.'>
                <input type="hidden" name="page" value="editor">
                <input class="commitbutton" id="reeditbuttonjs"  type="submit" name="submitexistingpage" value="Commit">
            </div></form>';
        ob_end_flush();
    }

//======================================================
    
    protected function editPageFormFilled()
    {
        if (isset($_POST["tag"]))
        {
            $tags = $_POST["tag"];
        }
        else
        {
            $tags[0] = 1;
        }
        
        $title = htmlspecialchars($_POST["wikititle"], ENT_QUOTES, "UTF-8"); 
        $content = htmlspecialchars($_POST["pageeditor"], ENT_QUOTES, "UTF-8");
        $id = htmlspecialchars($_POST["pageid"], ENT_QUOTES, "UTF-8");

        $this->editor->arrayScrambler($tags);

        $result = $this->editor->saveExistingPageToDatabase($title, $content, $tags, $id, $this->db);
        
        if ($result !== false)
        {
            echo "Page edit uploaded.";
        }
        else
        {
            echo "Error: page edit failed.";
        }
    }  

//======================================================
    
    public function bodyContent()
    {
        if (isset($_GET["id"]))
        {
            $getpage = htmlspecialchars($_GET["id"], ENT_QUOTES, "UTF-8");
            if ($this->user->loggedUser())
            {

                $opUser = $this->editor->selectPagesOnName($getpage, $this->db);

                if ($opUser[3] === $this->user->getActiveUserId())
                {
                    if (!isset($_POST["submitexistingpage"]))
                    {
                        $this->editPageForm($getpage);
                    }
                    else
                    {
                        $this->editPageFormFilled();
                    } 
                }
                
                elseif ($this->user->getPermission() == 2)
                {
                    if ($this->user->pageOwnerIsAdmin($getpage) === false)
                    {
                        if (!isset($_POST["submitexistingpage"]))
                        {
                            $this->editPageForm($getpage);
                        }
                        else
                        {
                            $this->editPageFormFilled();
                        }    
                    }
                    else
                    {
                        echo 'This page is controlled by another Admin';
                    }
                }
                else
                {
                    echo 'You can only edit your own pages';
                }
            }
            else
            {
                echo 'You need to be logged in to edit pages';
            }
        }
        else
        {
            if ($this->user->loggedUser())
            {
                if (!isset($_POST["submitnewpage"]))
                {
                    $this->createPageForm();
                }
                else
                {
                    $this->createPageFormFilled();
                }
            }
            else
            {
                echo 'You do not have sufficient permission to create a new page';
            }
        } 
    }    
}