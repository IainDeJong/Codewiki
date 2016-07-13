

$(document).ready(function()
{
    
    $(window).on("beforeunload", windowBeforeUnload);
    
    $("#reeditbuttonjs").on("mouseenter", stopPopup)
            .on("mouseout", function() 
    {
        $(window).on("beforeunload", windowBeforeUnload);
    });
});


function stopPopup()
{
    $(window).off("beforeunload");
}


function windowBeforeUnload()
{
    return "You have unsaved data!";
}


