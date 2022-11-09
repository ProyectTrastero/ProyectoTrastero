{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Mi trastero Inicio')

{{-- Sección de la barra de navegación con el usuario identificado --}}
@section('navbar')
<div>
    <input type="button" class="btn btn-default dropdown-toggle"
           data-toggle="dropdown"> {{$usuario->getNombre()}} <span class="caret"></span><br/>
    <input type="button" name="perfilusuario" value="Perfil"><br/>       
    <input type="button" name="salir" value="Cerrar Sesion">
</div>
@endsection

{{-- Sección mensaje --}}
@section('content')

<div class="container">
        <div>
            <h3>Diseña tu trastero</h3>
            <button name="añadirTrastero" id="añadirTrastero"><span>Diseñar</span></button>
        </div>
        <br/><br/><br/><br/>
        
        @if ($trasteros != "")
        <div>             
                <h3>Mis trasteros</h3>
                @foreach ($trasteros as $valor)    
                <table class="row">
                    <td class="col-4"> {{$valor->getNombre()  }}</td> <br/>
                    <td  class="col-1">
                        <form method="POST" action="" id='formacceder'>
                            <input type='hidden' name='id' value='{{$valor->getId()}}'>
                            <button type="submit" name="acceder" id='acceder'><span>Acceder</span></button>
                        </form>
                    </td> <br/>
                    <td  class="col-1">
                        <form method="POST" action="" id='formmodificar'>
                            <input type='hidden' name='id' value='{{$valor->getId()}}'>
                            <button type="submit" name="modificar" id='modificar'<span>Modificar</span></button>
                        </form>
                    </td>
                </table>
                @endforeach    
                <br/><br/><br/>
               
        </div>
        @else
            <div>           
            <form method="POST" action="" id='formtrasteros'>
                <h3>Mis trasteros</h3><!-- comment -->
                <h2>Usted aun no  tiene trasteros</h2>
                <br/><br/><br/>
            </form>     
            </div>
        @endif
</div>

@endsection
