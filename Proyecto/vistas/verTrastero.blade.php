{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ver Trastero')

{{-- Sección mensaje --}}
@section('content')
<link rel="stylesheet" href="asset/css/verTrastero.css">
<div class="container">
    <div class="row">
        <div class="col-12" id="menu">          
            <div class="li col-12"><input type="checkbox" name="list" id="nivel1-2" checked=""><label for="nivel1-2">Estanteria 1</label>
                <div class="interior ul col-6">
                    <div class="li"><input type="checkbox" name="list" id="nivel2-4"><label for="nivel2-4">Balda 1</label>
                        <div class="interior ul col-4">
                            <div><span>Caja 1</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="verTrastero.blade.php"></a>

@endsection