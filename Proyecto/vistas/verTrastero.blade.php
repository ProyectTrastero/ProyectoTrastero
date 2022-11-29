{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Ver Trastero')

{{-- Sección mensaje --}}
@section('content')
<link rel="stylesheet" href="asset/css/verTrastero.css">
<div class="container">
    <div>
        <ul id="menu">
            @foreach($datosTrastero['estanterias'] as $estanteria)
           <li><input type="checkbox" name="list" id="{{$estanteria->getId()}}"><label for="{{$estanteria->getId()}}">{{$estanteria->getNombre()}}</label>
           <ul class="interior">
               @php 
               $baldasRecuperadas = array()
               @endphp
               @foreach($datosTrastero['baldas'] as $balda)
               @if($balda->getIdEstanteria() == $estanteria->getId())
                   @php
                   $baldasRecuperadas[]=$balda
                   @endphp
                   @endif
               @endforeach
               @foreach($baldasRecuperadas as $balda)
                   @php
                   $cajasRecuperadas = array()
                   @endphp
                   @foreach($datosTrastero['cajas'] as $caja)
                       @if($caja->getIdBalda()==$balda->getId())
                       @php
                       $cajasRecuperadas[]=$caja
                       @endphp
                       @endif
                   @endforeach
                   @if(empty($cajasRecuperadas))
                    <li><a href="#r">{{$balda->getNombre()}}</a></li>
                   @else
                    <li><input type="checkbox" name="list" id="{{$balda->getId()}}"><label for="{{$balda->getId()}}">{{$balda->getNombre()}}</label>

                        <ul class="interior">
                            <li>
                                <ul>
                                    @foreach ($cajasRecuperadas as $caja)
                                    <li>{{$caja->getNombre()}}</li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @endforeach
              </ul>
           </li>
           @endforeach
        </ul>
    </div>
    <div>
        
    </div>

</div>

<a href="accederTrastero.php"><input type="button" value="VOLVER"></a>
@endsection