window.addEventListener("load", iniciar);
  var idEstanteria="";


  function iniciar(){
    
    idEstanteria= document.getElementById('selectEstanterias').value;
    //enviamos el id de la estanteria seleccionada por default
    loadDoc('añadirProducto.php?idEstanteria=' + idEstanteria, setBaldas);
    //cuando seleccionamos una estanteria enviamos el id que hemos seleccionado al server
    document.getElementById('selectEstanterias').addEventListener('change',function(){
        console.log(this.value);
        loadDoc('añadirProducto.php?idEstanteria=' + this.value , setBaldas)
    })

}

function loadDoc(url,cFunction){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){cFunction(JSON.parse(this));}
    xhttp.open("GET",url );
    xhttp.send();
}

  function setBaldas(xhttp){
    console.log(xhttp);
    document.getElementById('selectBaldas').innerHTML='daniel';
  }


//   loadDoc("añadirProducto.php?idEstanteria=" + idEstanteria, setBaldas());