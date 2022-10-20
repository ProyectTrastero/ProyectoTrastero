{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Registro Usuario')

{{-- Sección mensaje --}}
@section('mensaje')
        <div class="container">
                <h2>Registro usuario</h2>
                        <form method="POST" action="index.php" id='formregistro'>
                                <input id="inputUsuario" type="text" placeholder="Usuario" class="usuario">
                                <br/>
                                <input id="inputNombre" type="text" placeholder="Nombre" class="nombre">
                                <br/>
                                <input id="inputApellidos" type="text" placeholder="Apellidos" class="apellidos">
                                <br/>
                                <input id="inputEmail" type="text" placeholder="Email" class="email">
                                <br/>
                                <input id="inputPassword" type="password" placeholder="Contraseña" class="pwd">
                        </form>
                        </br></br>                
                <button class="registro" href="index.php?botonproregistro" id="proregistro">
                        <span>Confirmar</span>
                </button>
                @if (isset($error))
                        <div class="alert alert-danger" role="alert">Error al crear Usuario</div>
                @endif
        </div>                
@endsection