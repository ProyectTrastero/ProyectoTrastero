{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Añadir Trastero')

{{-- Sección mensaje --}}
@section('content')

<script src="https://kit.fontawesome.com/12104efb99.js" crossorigin="anonymous"></script>
<script src="asset/js/añadirTrastero.js"></script>

<div class="container">
    <form action="" method="POST">
        <div>
        <label for="nombre">NOMBRE:</label>
        <input type="text" name="nombre" id="nombre">
    </div>


    <div>
        <input type="submit" name="añadirEstanteria" value="AÑADIR ESTANTERÍA">
        <input type="submit" name="añadirCaja" value="AÑADIR CAJA">
    </div>
    <div>lugar de las estanterías 
        <table>
            <tr>
                <th class="papeleraOculta">
                    Estanteria 1
                </th>
            </tr>
        </table>
        <table>
            <tr>
                <th class="papeleraOculta">
                    Estanteria 2
                </th>
            </tr>
        </table>
        <table>
            <tr>
                <th class="papeleraOculta">
                    Estanteria 3
                </th>
            </tr>
        </table>

    </div>

    <div>
        <input type="submit" name="volverAcceso" value="VOLVER">
        <input type="submit" name="guardar" value="GUARDAR">
    </div>

    </form>
    
</div>

@endsection

