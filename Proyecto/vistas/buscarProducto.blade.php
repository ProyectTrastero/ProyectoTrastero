{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
<form action="{{ $_SERVER["PHP_SELF"] }}">
  <div class="nav-item dropdown">
    <div class="nav-link dropdown-toggle " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      {{$usuario->getNombre()}} <i class="fa-solid fa-user fa-2xl"></i>
    </div>
    <ul class="dropdown-menu">
      <li><button class="dropdown" type="submit" name="perfilUsuario">Perfil usuario</button></li>
      <li><button class="dropdown" type="submit" name="cerrarSesion">Cerrar sesión</button></li>
    </ul>
  </div>
</form>
@endsection

{{-- Sección mensaje --}}
@section('content')
<div  class='container'>
    <div>
        <h3>{{$miTrastero->getNombre()  }}</h3>
    </div>    
    <form method="POST" action="" name="formBusqueda">
        <input type="text" name="palabraBuscar" placeholder="producto">
        <button type="submit" name="buscarProducto">Buscar</button>
    </form>
    <br/>
    <br/>
    <br/>
    <div>     
        <form method="POST" action="" name="formEtiquetas">

           <!--me falta el  select-->
            <button type="submit" name="añadirEtiquetas">Seleccionar Etiqueta</button>
        </form> 
    </div>
    
    <div>
        @if (isset ($etiquetas))
            @if ($etiquetas != "")
                    @foreach ($etiquetas as $valor)
                    <!--Dentro de un div row-->
                    <div class="col-3"> {{$valor->getNombre()  }}</div>
                    @endforeach
            @else
                <div>           
                    <h4>Aun no tiene ninguna etiqueta</h4>    
                </div>
            @endif
        @endif
    </div>
    
        @if (isset ($productos))
            @if ($productos != "")
            <div>             
                <h3>Mis productos</h3>
                <table class="row">
                    <tr>
                    <th class="col-3">Producto</th>
                    <th class="col-3">Descripción</th>
                    <th class="col-3">Ubicación</th>
                    <th class="col-3">   </th>
                    </tr>
                @foreach ($productos as $valor)    
                <tr>
                    <td class="col-3"> {{$valor->getNombre()  }}</td><br/> 
                    <td class="col-3"> {{$valor->getDescripcion()  }}</td><br/> 
                    <td class="col-3"> Estanteria: {{$valor->getEstanteria()  }}, Balda: {{$valor->getBalda()  }}, Estanteria: {{$valor->getCaja()  }} </td><br/> 
                    <td  class="col-3">
                        <form method="POST" action="" id='produModificar'>
                            <input type='hidden' name='id' value='{{$valor->getId()}}'>
                            <button type="submit" name="modificarProducto" id='modificarProducto'<span>Modificar</span></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </table>
                <br/><br/><br/>
               
            </div>
            @else
            <div>           
                <h2>No existen productos con esa busqueda</h2>    
            </div>
            @endif
        @endif
    
<div class="container">
    <form method="POST" action="" name="formVolver">
        <button class ="volver" name="volverTrasteros">Volver</button>
    </form>
</div>
@endsection

