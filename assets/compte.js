$(document).ready(()=>{
    
    $('#userData').css("display", "block");
    $('#tailleData').css("display", "none");
    $('#messagesData').css("display", "none");
    $('#taille').click(function(){
        $('#messagesData').css("display", "none");
        $('#userData').css("display", "none");
        $('#tailleData').css("display", "block");
    })
    $('#messages').click(function(){
        $('#tailleData').css("display", "none");
        $('#userData').css("display", "none");
        $('#messagesData').css("display", "block");
    })
    $('#user').click(function(){
        $('#tailleData').css("display", "none");
        $('#messagesData').css("display", "none");
        $('#userData').css("display", "block");
    })
})