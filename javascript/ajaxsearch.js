/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    $(document).ready(function(){
            $(document).on("click", "button#advanced", function() 
            {
                    $.ajax({ 
                        type    : 'GET',     
                        cache   : false,
                        url     : 'index.php',
                        data    : {ajaxaction: "advanced"},
                        dataType: 'html',
                        success : function(html) 
                        { 
                            $('div.seek').html(html);
                            $("#menusearch").toggle();
                        }
                        });
                });
    });
    
        $(document).ready(function(){
            $(document).on("click", "button#more", function() 
            {
                $.ajax({ 
                    type    : 'GET',     
                    cache   : false,
                    url     : 'index.php',
                    data    : {ajaxaction: "more"},
                    dataType: 'html',
                    success : function(html) 
                    {
                        $('div.more').html(html);
                    }
                    });
            });
            
            });    
            
                    $(document).ready(function(){
            $(document).on("click", "button#less", function() 
            {
                $.ajax({ 
                    type    : 'GET',     
                    cache   : false,
                    url     : 'index.php',
                    data    : {ajaxaction: "less"},
                    dataType: 'html',
                    success : function(html) 
                    {
                        $('div.more').html(html);
                    }
                    });
            });
    });
