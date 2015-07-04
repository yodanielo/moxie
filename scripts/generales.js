var inter_izq;
var inter_der;
var l;
var wcar;
window.onload=function(){
    if(document.getElementById("videosninas")){
        chicas_moxie();
    }
    if(document.getElementById("losnuestros")){
        los_nuestros();
    }
    //cargar_destacados
    if(document.getElementById("panelcarousel_c")){
        inicio_carousel();
    }
}
function cargaparticipa(){
    $("#dlgparticipa").jqm();
    $("#dlgparticipa").jqmShow();
    CSBfleXcroll('contenido');
}
function cargaconcurso(){
    $("#dlgconcurso").jqm();
    $("#dlgconcurso").jqmShow();
    CSBfleXcroll('conconcurso');
}
function validacodigo(){
    x=document.frminserta;
    var r;
    r=false;
    $.ajax({
        type:"POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=5&id="+x.codigo.value,
        success: function(datos){
            if(datos.indexOf("bueno")>=0){
                r=true;
                x.submit();
            }
            else{
                alert('Tu código no es correcto, inténtalo nuevamente.');
                r=false;
            }
            x.codigo.value="";
        }
    });
    return r;
}
function sacar_comparte(){
    video=document.getElementById("vidcontenedor").innerHTML;
    if(video!=null && video!=""){
        document.getElementById("cuayoutube").innerHTML=video;
        $("#vidcontenedor").empty();
        $("#dlgcomparte").jqm();
        $("#dlgcomparte").jqmShow();
    }
}
function inicio_carousel(){
    x=document.getElementById("panelcarousel_c");
    m=x.getElementsByTagName("li");
    wcar=m.length*108;
    x.style.width=wcar+"px";
    document.getElementById("flecizq").onmousedown=function(){
        inter_izq=1;
        if(l==null || l==0){
            l=-1;
        }
        car_derecha();
    }
    document.getElementById("flecizq").onmouseup=function(){
        inter_izq=0;
    }
    document.getElementById("flecder").onmousedown=function(){
        inter_izq=1;
        if(l==null || l==0){
            l=-1;
        }
        car_izquierda();
    }
    document.getElementById("flecder").onmouseup=function(){
        inter_izq=0;
    }
}
function car_izquierda(){
    document.getElementById("panelcarousel_c").style.marginLeft=l+"px";
    if(inter_izq==1){
        if(wcar+l>=330){
            l--;
            setTimeout("car_izquierda()",20);
        }
    }
}
function car_derecha(){
    document.getElementById("panelcarousel_c").style.marginLeft=l+"px";
    if(inter_izq==1){
        if(l<=0){
            l++;
            setTimeout("car_derecha()",20);
        }
    }
}
function chicas_moxie(pag,cuadros){
    if(pag==null){
        pag=1;
    }
    if(cuadros==null){
        cuadros=4;
    }
    $("#videosninas").css("background","url(images/loading.gif) no-repeat center");
    $("#videosninas").empty();
    $.ajax({
        type: "POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=1&pag="+pag+"&cuadros="+cuadros,
        success: function(datos){
            document.getElementById("videosninas").innerHTML=datos;
            $("#videosninas").css("background","none");
        }
    });
}
function los_nuestros(pag,cuadros){
    if(pag==null){
        pag=1;
    }
    if(cuadros==null){
        cuadros=8;
    }
    $("#losnuestros").css("background","url(images/loading.gif) no-repeat center");
    $("#detlosnuestros").empty();
    $.ajax({
        type: "POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=1&pag="+pag+"&cuadros="+cuadros+"&aquien=los_nuestros",
        success: function(datos){
            document.getElementById("detlosnuestros").innerHTML=datos;
            $("#losnuestros").css("background","none");
        }
    });
}
function los_nuestrossuper(superpag){
    if(superpag!=0){
        if(superpag==null){
            superpag=1;
        }
        $.ajax({
            type: "POST",
            url: "includes/contenido_ajax.php",
            data: "content=home&orden=10&superpag="+superpag+"&opc=los_nuestros",
            success: function(datos){
                document.getElementById("navfotolos_nuestros").innerHTML=datos;
            }
        });
    }
}
function chicas_moxiesuper(superpag){
    if(superpag!=0){
        if(superpag==null){
            superpag=1;
        }
        $.ajax({
            type: "POST",
            url: "includes/contenido_ajax.php",
            data: "content=home&orden=10&superpag="+superpag+"&opc=chicas_moxie",
            success: function(datos){
                document.getElementById("navfotochicas_moxie").innerHTML=datos;
            }
        });
    }
}
function carga_video(id){
    $.ajax({
        type:"POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=4&id="+id,
        success: function(datos){
            document.getElementById("vidtitulo").innerHTML=datos;
        }
    });
    $.ajax({
        type:"POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=2&id="+id,
        success: function(datos){
            //document.getElementById("vidcontenedor").innerHTML=datos;
            $("#vidcontenedor").html(datos);
        }
    });
    $.ajax({
        type:"POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=6&id="+id,
        success: function(datos){
            x=document.frmcomparte;
            x.video.value=datos;
            document.getElementById("comtwitter").href="http://twitter.com/home?status=Echa%20un%20vistazo%20a%20%C3%A9ste%20video%20de%20una%20Chica%20Moxie%3A%20"+datos;
        }
    });
    $.ajax({
        type:"POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=8&id="+id,
        success: function(datos){
            document.getElementById("comtuenti").href=datos;
        }
    });
    $.ajax({
        type:"POST",
        url: "includes/contenido_ajax.php",
        data: "content=home&orden=9&id="+id,
        success: function(datos){
            document.getElementById("comfacebook").href=datos;
        }
    });
    }
function compartir(){
    s=document.frmcomparte;
    denom=s.txtdenombre.value;
    demail=s.txtdemail.value;
    panom=s.txtparnombre.value;
    pamail=s.txtparcorreo.value;
    //s.video.value=document.getElementById("cuayoutube").innerHTML;
    video=s.video.value;
    if(!denom){
        alert("Debes escribir tu nombre correctamente.");
        s.txtdenombre.focus();
    }else{
        if(!demail || demail.indexOf("@")<0){
            alert("Debes escribir tu correo correctamente.");
            s.txtdemail.focus();
        }else{
            if(!panom){
                alert("Debes escribir el nombre de tu amig@ correctamente.");
                s.txtparnombre.focus();
            }else{
                if(!pamail || pamail.indexOf("@")<0){
                    alert("Debes escribir el correo de tu amig@ correctamente.");
                    s.txtparmail.focus();
                }
                else{
                    $.ajax({
                        type:"POST",
                        url: "includes/contenido_ajax.php",
                        data: "content=home&orden=3&denom="+denom+"&demail="+demail+"&panom="+panom+"&pamail="+pamail+"&video="+video,
                        success: function(datos){
                            //alert(datos);
                            //document.getElementById("vidcontenedor").innerHTML=datos;
                            //document.getElementById("vidcontenedor").innerHTML=document.getElementById("cuayoutube").innerHTML
                            //$('#dlgcomparte').jqmHide();
                            alert("Tu e-mail fue enviado correctamente.")
                            s=document.frmcomparte;
                            s.txtdenombre.value="";
                            s.txtdemail.value="";
                            s.txtparnombre.value="";
                            s.txtparcorreo.value="";
                        }
                    });
                }
            }
        }
    }
}