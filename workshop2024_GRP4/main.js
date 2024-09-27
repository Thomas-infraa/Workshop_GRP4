function clicknewpost() {
    element = document.getElementById("newpostbox");
    if(element.style.display == "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}

function clicknewresponse() {
    element = document.getElementById("newresponsebox");
    if(element.style.display == "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}

function accessform(id){
    document.getElementById("accesspost"+id).submit()
}