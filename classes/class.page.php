<?php

//this is the base html template for a page

//include the classes that extend this class
//include('classes/');

class Page
{
    var $page = "";
    var $post = "";

    function show()
    {
            $this->beginDoc();
            $this->beginHeader();
            $this->headerContent();
            $this->endHeader();
            $this->beginBody();
            $this->bodyContent();
            $this->endBody();
            $this->endDoc();
    }	

    function beginDoc() 
    { 
            echo "<!DOCTYPE html><html>"; 
    }

    function beginHeader() 
    { 
            echo "<head>"; 
    }

    function headerContent() 
    { 
            echo "<title></title>";
    }

    function endHeader()
    { 
            echo "</head> \r"; 
    }

    function beginBody() 
    { 
            echo "<body>"; 
    }

    function bodyContent() 
    { 
            echo ""; 
    }

    function endBody() 
    { 
            echo "</body>"; 
    }

    function endDoc() 
    { 
            echo "</html>"; 
    }
}