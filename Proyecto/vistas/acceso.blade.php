{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
 
<form action="{{ $_SERVER["PHP_SELF"] }}">
  <div class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
    </a>
    <ul class="dropdown-menu">
      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
      <li><a class="dropdown-item" href="<?php __DIR__ ?>./../public/editarPerfil.php">Editar perfil</a></li>
      <li><button type="submit" name="cerrarSesion">Cerrar sesión</button></li>
    </ul>
  </div>
</form>
@endsection

{{-- Sección content --}}
@section('content')

<div class="container">
        <div>
            <h3>Diseña tu trastero</h3>
            <button name="añadirTrastero" id="añadirTrastero"><span>Diseñar</span></button>
        </div>
        <br/><br/><br/><br/>
        @if (isset($_SESSION ['trasteros']))
        <div>
            <form method="POST" action="" id='formtrasteros'>
                <h3>Mis trasteros</h3>
                @foreach ($trasteros as $valor)    
                <table class="row">
                    <td class="col-4"> {{$valor->getNombre()  }}</td>
                    <br/><br/>   
                    <td  class="col-1"><button type="submit" name="acceder" id='acceder'> Acceder</button></td>
                    <td class="col-1"><button type="submit" name="modificar" id='modificar'> Modificar</button></td>
                </table>
                @endforeach    
                <br/><br/><br/>
            </form>     
        </div>
        @endif
</div>

@endsection
