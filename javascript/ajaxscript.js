


    function ajaxRater(pagenumber, userids)
    {
        
        var newrating = $('#ratinginput').val();
        
        $.ajax({ url: 'index.php',     // url of the script to be called server side
         data: {ajaxaction:'rating', number:newrating, pageid:pagenumber, userid:userids},    // action that should be called in the controller      
            // type of data,
         type: 'post',              // is it a GET or a POST
         success: function(thing)    // Thing that should happen on succes with the data retrieved.
                   {
                       $("#ratingshowref").replaceWith(thing);
                       $("#ratingbuttonajax").replaceWith("");
                       $("#ratinginput").replaceWith("");
                   }
            });
    };