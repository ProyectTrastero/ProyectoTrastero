{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ver Trastero')

{{-- Sección mensaje --}}
@section('content')
<link rel="stylesheet" href="asset/css/verTrastero.css">
<div>
    <div>
        <ul id="menu">          
            <li><input type="checkbox" name="list" id="nivel1-2" checked=""><label for="nivel1-2">Nivel 1</label>
                <ul class="interior">
                    <li><input type="checkbox" name="list" id="nivel2-4"><label for="nivel2-4">Nivel 2</label>
                        <ul class="interior">
                            <li><a href="#r">Nivel 3</a></li>
                            <li><a href="#r">Nivel 3</a></li>
                            <li><a href="#r">Nivel 3</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>


@endsection