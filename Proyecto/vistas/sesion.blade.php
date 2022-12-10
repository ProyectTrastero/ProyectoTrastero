{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Inicio de Sesion')

{{-- Sección mensaje --}}
@section('content')

    <div class="container">
        <h2>Iniciar sesion</h2>
        @if (isset($error))
        <div class="alert alert-danger" role="alert">Error Credenciales</div>
        @endif 
        <form method="POST" action="" id='formsesion'>
            <label>Nombre de usuario: </label><br/>
                        <input id="inputAlias" type="text" placeholder="Nombre" name="alias" class="alias"><br/>
            <label>Contraseña: </label><br/>
                        <input id="inputPassword" type="password" placeholder="Contraseña" name="clave" class="clave">
                         <!-- Button trigger modal -->
                         <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">AÑADIR CAJA</button>-->
                         <a href="#href" id="solicitarContraseña" class="link" data-bs-toggle="modal" data-bs-target="#staticBackdrop" > ¿Has olvidado tu contraseña?</a>
                </br>
                    <button type="submit" id="procsesion" name="procsesion"><span> Login </span> </button>
                <br/><br/>


                <a>¿Aún no te has regitrado?</a><br/>
                <div>   
                    <button type="submit" id="registro" name="botonregistro"><span> Registrarse </span></button>
                </div>
         </form>        

    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <script src="asset/js/contraseña.js"></script>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Recuperar Credenciales:</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="container">
                        <div>
                            <label for="correo">Correo electrónico</label>
                            <input id="correo" type="text" name="correo" placeholder="Correo">
                        </div>
                        <div>
                            <p id="mensaje"></p>
                        </div>
                        <div>

                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" id="volver" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                    <button type="button" id="comprobarContraseña" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
