
import './styles/menu.css';

/*functions*/ 
function displayOptions(options){
    let UlOptions=document.querySelector(options)
    if(options==="#planContactNouveau" || options==="#searchPhone" ){
        if(UlOptions.style.display==="flex"){
            UlOptions.style.display="none"
        }
        else{
            UlOptions.style.display="flex"
        }
    }
    else{
        if(UlOptions.style.display==="grid"){
            UlOptions.style.display="none"
        }
        else{
            UlOptions.style.display="grid"
        }
    }
}

function notdisplayOptions(options){
    let UlOptions=document.querySelector(options);
        UlOptions.style.display="none"
}

function rotateOptions(options){
    if(document.querySelector(options).style.webkitTransform=='rotate(-90deg)'){
        document.querySelector(options).style.webkitTransform='rotate(0deg)';
    }
   else{
        document.querySelector(options).style.webkitTransform='rotate(-90deg)';
    }
}


$(window).bind('resize', function(e)
{
  if (window.RT) clearTimeout(window.RT);
  window.RT = setTimeout(function()
  {
    this.location.reload(false); /* false to get page from cache */
  }, 100);
});

/*search button */
document.querySelector("#btnSearch").addEventListener("click",()=>{
    if(window.innerWidth<=1024){
        displayOptions("#searchPhone")
        notdisplayOptions("#searchComptePanier")
    }else{
       
        displayOptions("#searchDestop")
        notdisplayOptions("#searchComptePanier")
        notdisplayOptions("#planContactNouveau")
    }
    });

/*search close button */
document.querySelector("#svgCloseSearch").addEventListener("click",()=>{
    displayOptions("#searchComptePanier")
    notdisplayOptions("#searchPhone")
    });

/*search close button desktop */
document.querySelector("#svgCloseSearchDesktop").addEventListener("click",()=>{
    displayOptions("#searchComptePanier")
    displayOptions("#planContactNouveau")
    notdisplayOptions("#searchDestop")
    });

/*Toggler dropdown phone */
document.querySelector("#btnToggler").addEventListener("click",()=>{
    displayOptions("#planContactNouveau");
displayOptions("#phone");

rotateOptions("#svgHomme")
if(document.querySelector("#phone").style.display==="grid"){
    displayOptions("#hommePhoneOptions")
}else{
    notdisplayOptions("#hommePhoneOptions")
}
if(document.querySelector("#femmePhoneOptions").style.display==="grid"){
    notdisplayOptions("#femmePhoneOptions")
}
if(document.querySelector("#enfantsPhoneOptions").style.display==="grid"){
    notdisplayOptions("#enfantsPhoneOptions")
}

if(document.querySelector("#bodyContainer").style.display===""){
    notdisplayOptions("#bodyContainer")
}else{
    if(document.querySelector("#bodyContainer").style.display==="grid"){
        notdisplayOptions("#bodyContainer")
    }else{
    displayOptions("#bodyContainer")
}
}

});

/*Homme dropdown phone */
document.querySelector("#btnHomme").addEventListener("click",()=>{
    if(document.querySelector("#hommePhoneOptions").style.display==="grid"){

    }else{
        displayOptions("#hommePhoneOptions")
        notdisplayOptions("#femmePhoneOptions")
        notdisplayOptions("#enfantsPhoneOptions")
        rotateOptions("#svgHomme")
        if(document.querySelector("#svgFemme").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgFemme")
        }
        if(document.querySelector("#svgEnfants").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgEnfants")
        }
    }
    });

/*Femme dropdown phone */
document.querySelector("#btnFemme").addEventListener("click",()=>{
    if(document.querySelector("#femmePhoneOptions").style.display==="grid"){

    }else{
        displayOptions("#femmePhoneOptions")
        notdisplayOptions("#hommePhoneOptions")
        notdisplayOptions("#enfantsPhoneOptions")
        rotateOptions("#svgFemme")
        if(document.querySelector("#svgHomme").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgHomme")
        }
        if(document.querySelector("#svgEnfants").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgEnfants")
        }
    }
    });

/*Enfants dropdown phone */
document.querySelector("#btnEnfants").addEventListener("click",()=>{
    if(document.querySelector("#enfantsPhoneOptions").style.display==="grid"){

    }else{
        displayOptions("#enfantsPhoneOptions")
        notdisplayOptions("#hommePhoneOptions")
        notdisplayOptions("#femmePhoneOptions")
        rotateOptions("#svgEnfants")
        if(document.querySelector("#svgHomme").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgHomme")
        }
        if(document.querySelector("#svgFemme").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgFemme")
        }
    }
    });


/*Homme options dropdown phone */
document.querySelector("#btnHommePhoneStylesOptions").addEventListener("click",()=>{
    displayOptions("#hommePhoneStylesOptions");
    notdisplayOptions("#hommePhoneProduitsOptions")
    rotateOptions("#svgHommeStyles")
        if(document.querySelector("#svgHommeProduits").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgHommeProduits")
        }
});
document.querySelector("#btnHommePhoneProduitsOptions").addEventListener("click",()=>{
    displayOptions("#hommePhoneProduitsOptions");
    notdisplayOptions("#hommePhoneStylesOptions")
    rotateOptions("#svgHommeProduits")
        if(document.querySelector("#svgHommeStyles").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgHommeStyles")
        }
});

/*Femme options dropdown phone */
document.querySelector("#btnFemmePhoneStylesOptions").addEventListener("click",()=>{
    displayOptions("#femmePhoneStylesOptions");
    notdisplayOptions("#femmePhoneProduitsOptions")
    rotateOptions("#svgFemmeStyles")
        if(document.querySelector("#svgFemmeProduits").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgFemmeProduits")
        }
  
});
document.querySelector("#btnFemmePhoneProduitsOptions").addEventListener("click",()=>{
    displayOptions("#femmePhoneProduitsOptions");
    notdisplayOptions("#femmePhoneStylesOptions")
    rotateOptions("#svgFemmeProduits")
        if(document.querySelector("#svgFemmeStyles").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgFemmeStyles")
        }
});

/*Enfants options dropdown phone */
document.querySelector("#btnEnfantsPhoneStylesOptions").addEventListener("click",()=>{
    displayOptions("#enfantsPhoneStylesOptions");
    notdisplayOptions("#enfantsPhoneProduitsOptions")
    rotateOptions("#svgEnfantsStyles")
        if(document.querySelector("#svgEnfantsProduits").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgEnfantsProduits")
        }
  
});
document.querySelector("#btnEnfantsPhoneProduitsOptions").addEventListener("click",()=>{
    displayOptions("#enfantsPhoneProduitsOptions");
    notdisplayOptions("#enfantsPhoneStylesOptions")
    rotateOptions("#svgEnfantsProduits")
        if(document.querySelector("#svgEnfantsStyles").style.webkitTransform=='rotate(-90deg)'){
            rotateOptions("#svgEnfantsStyles")
        }
});