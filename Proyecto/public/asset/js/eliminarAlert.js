window.addEventListener('load',inicio);

function inicio(){
    //si tenemos elemento con id alert
    if(document.getElementById('alert')){
        let alertElement = document.getElementById('alert');
        setTimeout(() => {
            //añadimos clase para desaparecer alert
            alertElement.classList.add('deleteAlert');
        }, 7000);
        //establecemos listener transition end
        alertElement.addEventListener('transitionend',()=>{
            //eliminamos alert element
            alertElement.remove();
        })
    }
}