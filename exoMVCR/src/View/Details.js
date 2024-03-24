"use strict";


function init(){
    let listeli = document.getElementById("listePage").querySelectorAll("li");
    listeli.forEach(ajouterBoutonDetail);
}

function ajouterBoutonDetail(item){
    let bouton = document.createElement("button");
    bouton.textContent="Détails";
    bouton.id="bouton_"+item.id;
    bouton.addEventListener("click", afficherDetails);
    item.append(bouton);
}

function afficherDetails(event){
    //console.log(event.currentTarget);
    event.preventDefault();
    let idbouton = event.currentTarget.id;
    let spt=idbouton.split("_");
    if(spt.length==2 && spt[0]=="bouton"){
        let id=spt[1];
        if(id!=""){
            let requete = new XMLHttpRequest();
            requete.open("GET", "./json/"+id);
            requete.addEventListener("load", afficheReponse);
            requete.responseType = "json";
            requete.send();
        }
    }
}

function cacherDetails(event){
    //console.log(event.currentTarget);
    event.preventDefault();
    let idbouton = event.currentTarget.id;
    let spt=idbouton.split("_");
    if(spt.length==2 && spt[0]=="bouton"){
        let id=spt[1];
        if(id!=""){
            let details = document.getElementById("details_"+id);
            details.remove();

            event.currentTarget.textContent="Détails";
            event.currentTarget.removeEventListener("click", cacherDetails);
            event.currentTarget.addEventListener("click", afficherDetails);
        }
    }
}

function afficheReponse(event){
    //console.log(event.currentTarget);
    let id = event.currentTarget.response.id;
    let espece = event.currentTarget.response.espece;
    let age = event.currentTarget.response.age;
    let li = document.getElementById(id);
    let bouton = document.getElementById("bouton_"+id);
    let details = document.createElement("ul");

    details.id="details_"+id;
    details.style.listStyle = "none";

    let liespece = document.createElement("li");
    liespece.textContent= "Espèce : "+espece;
    details.append(liespece);

    let liage = document.createElement("li");
    liage.textContent= "Age : "+age;
    details.append(liage);

    li.append(details);

    bouton.textContent="Cacher les détails";
    bouton.removeEventListener("click", afficherDetails);
    bouton.addEventListener("click", cacherDetails);
}