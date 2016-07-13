/* 
 * 
 * 
 * 
 */


$(document).ready(function()
{

    $(".menubutton").mouseenter(function()
    {
        $(this).css("background-color", "#BAEAF8");
    });
    $(".menubutton").mouseleave(function()
    {
        $(this).css("background-color", "#85D5EC");
    });
    
    $("#searchbutton").mouseenter(function()
    {
        $("#menusearch").show();
    });
    
    $("#searchtotal").mouseleave(function()
    { 
        $("#menusearch").hide();
    });
        
    $("#regbutton").mouseenter(function()
    {
        $("#menulogindiv").show();
    });
    
    $("#logintotal").mouseleave(function()
    { 
        $("#menulogindiv").hide();
    });
    
});

