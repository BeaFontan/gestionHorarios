function menu(){
    var check = document.getElementById("checkMenu").value;
    if(check == "0"){
        document.getElementById("contIzq").style.display= "flex";
        document.getElementById("overlay").style.display= "block";
        document.getElementById("checkMenu").value = "1";
    }else{
        document.getElementById("contIzq").style.display= "none";
        document.getElementById("overlay").style.display= "none";
        document.getElementById("checkMenu").value = "0";
    }
}