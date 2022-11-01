{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav">
    <li class="nav-item dropdown">
      <form action="">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
        </a>
        <ul class="dropdown-menu">
          <li><button type="submit" name="perfilUsuario">Perfil usuario</button></li>
          <li><a class="dropdown-item" href="<?php __DIR__ ?>./../public/editarPerfil.php">Editar perfil</a></li>
          <li><button type="submit" name="cerrarSesion">Cerrar sesión</button></li>
      </form>
        
      </ul>
    </li>
  </ul>
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
