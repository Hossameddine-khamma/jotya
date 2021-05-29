$(document).ready(()=>{
    
    $('#userData').css("display", "block");
    $('#tailleData').css("display", "none");
    $('#messagesData').css("display", "none");
    $('#favorisData').css("display", "none");
    $('#taille').click(function(){
        $('#favoris').removeClass("border-l-2 border-r-2");
        $('#user').removeClass("border-l-2 border-r-2");
        $('#taille').addClass("border-l-2 border-r-2");
        $('#userData').css("display", "none");
        $('#favorisData').css("display", "none");
        $('#tailleData').show("slow");
    })
    $('#user').click(function(){
        $('#favoris').removeClass("border-l-2 border-r-2");
        $('#taille').removeClass("border-l-2 border-r-2");
        $('#user').addClass("border-l-2 border-r-2");
        $('#tailleData').css("display", "none");
        $('#favorisData').css("display", "none");
        $('#userData').show("slow");
    })
    $('#favoris').click(function(){
        $('#user').removeClass("border-l-2 border-r-2");
        $('#taille').removeClass("border-l-2 border-r-2");
        $('#favoris').addClass("border-l-2 border-r-2");
        $('#tailleData').css("display", "none");
        $('#messagesData').css("display", "none");
        $('#userData').css("display", "none");
        $('#favorisData').show("slow");
    })
})