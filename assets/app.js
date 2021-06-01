/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

$(document).ready(function(){
    $("#btnFilter").click(function(){
        if( $("#filter").css("display")==="none"){
            $("#articles").fadeOut()
            $("#filter").fadeIn('slow')
            rotateOptions("#SvgFiltre")
           
        }
        else if($("#filter").css("display")==="block"){
            $("#filter").fadeOut('slow')
            $("#articles").css("display","grid")
            rotateOptions("#SvgFiltre")
        }
    })

    $('input[type="checkbox"]').each(function(){
        if (this.checked) {
            // the checkbox is now checked
           $(this).parent().removeClass("bg-white")
           $(this).parent().addClass("bg-black")
        } else {
            // the checkbox is now no longer checked
            $(this).parent().removeClass("bg-black")
            $(this).parent().addClass("bg-white")
        }
    })
    
   $('input[type="checkbox"]').change(function() {
    // this will contain a reference to the checkbox   
    if (this.checked) {
        // the checkbox is now checked
       $(this).parent().removeClass("bg-white")
       $(this).parent().addClass("bg-black")
    } else {
        // the checkbox is now no longer checked
        $(this).parent().removeClass("bg-black")
        $(this).parent().addClass("bg-white")
    }
    })

    var lignes = new Array(".firstligne",".secondligne",".thirdligne",".fourthligne");
    for(let i=0; i<=lignes.length; i++){
        $(lignes[i]).on("change", function (e) {
            e.preventDefault();
            if ($(lignes[i]+":checked").length > 1) {
                $(this).prop("checked", false);
                $(this).parent().removeClass("bg-black");
                $(this).parent().addClass("bg-white");
                alert("vous ne pouvez choisir qu'une catégorie sur ce filtre");
              }
            });
    }
})

function rotateOptions(options){
    if($(options).css('webkitTransform')==='none'){
        
        $(options).css('webkitTransform','rotate(-90deg)');
    }
   else{
    $(options).css('webkitTransform','none');
    
    }
}

var slider = document.getElementById("slider");
var output = document.getElementById("sliderValue");
output.innerHTML = slider.value;

slider.oninput = function() {
  output.innerHTML = this.value;
}