<?php

class Wiki extends Page
 {
    var $db;
    var $user;

//=========================================    
    
    public function __construct($db, $user) 
    {
        $this->db = $db;
        $this->user = $user;
    }

//=========================================    
    
    function beginHeader() 
    { 
        echo '<head>
            <meta charset=UTF-8 />
            <meta name="codepedia" content="Netbeans" />
            <link rel="stylesheet" href="stylesheet.css" type="text/css" media="all" />
	    <script type="text/javascript" src="javascript/jquery-1.12.4.min.js"></script>
            <script type="text/javascript" src="javascript/editorbuttons.js"></script>
            <script type="text/javascript" src="javascript/ajaxscript.js"></script>
            <script type="text/javascript" src="javascript/menubuttonchange.js"></script>
            <script type="text/javascript" src="javascript/ajaxsearch.js"></script>';
    }
    
//=========================================

    function headerContent() 
    { 
        echo "<title>The Codepedia</title>";
    }
    
//=========================================

    function beginBody() 
    {
        ob_start();
        echo '<body><div id="wrapper">
                <div id="menubar">
                <div id=menutopwrapper></div>';

        echo '<a href="index.php?page=home">
            <div class="menubutton" id="homebutton">Home</div></a>';
            
        if ($this->user->loggedUser())
        {
            echo '<a href="index.php?page=userpanel">
            <div class="menubutton" id="usersbutton">Users</div></a>';
        }
            
        echo '<div id="searchtotal"><div class="menubutton" id="searchbutton"><p class="menutextcolor">Search</p></div>';
        
        echo '<div class="seek">';
          
        $thing = new SearchPage($this->db, $this->user);
        $thing->searchBox(true, false);
        echo '</div>';
        
        echo '</div><a href="index.php?page=wikipage&id=info">
            <div class="menubutton" id="infobutton">Info</div></a>';
        
        if ($this->user->loggedUser())
        {
            echo '<a href="index.php?page=logout">
                <div class= "menubutton" id="regbuttonoff">Logout</div></a>';
        }
        else
        {
            echo '<div id=logintotal>
            <div class="menubutton" id="regbutton"><p class="menutextcolor">Register/Login</p></div>';
            
            $loginthing = new LoginPage($this->db, $this->user);
            $loginthing->showLogin();
            
            echo '</div>';    
        }
        
        if ($this->user->loggeduser())
        {
            echo '<a href="index.php?page=editor">
               <div class="menubutton" id="editbutton">Editor</div></a>';
        }    

        echo '</div><div id=maincontent>';
        ob_end_flush(); 
    }
    
//=========================================
    
    function endBody() 
    { 
        echo "</div></div></body>"; 
    }
}
