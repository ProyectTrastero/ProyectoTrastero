{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@if($datosTrastero['tipo']=="guardar")
@section('title', 'Añadir Trastero')
@else
@section('title', 'Modificar Trastero')
@endif
{{-- Sección mensaje --}}
@section('content')

<script src="asset/js/añadirTrastero.js"></script>
<script src="asset/js/ubicacionCaja.js"></script>
<div class="container">
    <div class="row">
        <p>{{$mensaje}}</p>
        @if($datosTrastero['guardado'])
        <span id="guardadoModificado" value="true" ></span>
        @else
        <span id="guardadoModificado" value="false" ></span>
        @endif
    </div>
    <div class="row">
        <form action="" method="POST">
            <div>
                @if($datosTrastero['tipo']=="guardar")
                <label for="nombre">NOMBRE:</label>
                <input type="text" name="nombre" id="nombre">
                @endif
               
                <input type="submit" name="añadirEstanteria" value="AÑADIR ESTANTERÍA">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">AÑADIR CAJA</button>

            </div>
            <div>
                @if($datosTrastero['tipo']=="guardar")
                <input type="submit" name="guardar" value="GUARDAR">
                @endif
                <input type="submit" name="volverAcceso" value="VOLVER">
            </div>
        </form>
    </div>
    
    <div class="row">
        @foreach ($datosTrastero['almacenEstanterias'] as $estanteria)
        <div class="col-4">
            <ul>
                <li>
                    <form action="" method="POST">
                        <input type="hidden" name="nombreEstanteria" value="{{$estanteria->getNombre()}}">
                        <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">
                        <span class="papeleraOculta" contenteditable="false">{{$estanteria->getNombre()}}</span>
                    </form>
                </li>

            @php
                $baldasRecuperadas=array();
            @endphp

            @foreach ($datosTrastero['almacenBaldas'] as $balda)
                @php
                    $baldasRecuperadas= $balda->recuperarBaldasPorIdEstanteria($bd, $estanteria->getId());
                @endphp
            @endforeach
            @foreach ($baldasRecuperadas as $balda)
                <ul> 
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">
                            <input type="hidden" name="idBalda" value="{{$balda->getId()}}">
                            <span class="papeleraOculta" contenteditable="false">{{$balda->getNombre()}}</span>
                        </form>
                    </li> 
                    <ul>

                        @foreach($datosTrastero['almacenCajas'] as $caja)
                            @if(($caja->getIdEstanteria()==$estanteria->getId())&&($caja->getIdBalda()==$balda->getId()))
                            <li>
                                <form action="" method="POST">
                                    <input type="hidden" name="idBalda" value="{{$balda->getId()}}"> 
                                    <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">
                                    <input type="hidden" name="idCaja" value="{{$caja->getId()}}">
                                    <span class="papeleraOculta" contenteditable="false">{{$caja->getNombre()}}</span>
                                </form>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </ul>
            @endforeach
            </ul>
            <form action="" method="POST">
                <input type="submit" name="añadirBalda" value="AÑADIR BALDA">
                <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}"

                <input type="hidden" name="nombreEstanteria" value="{{$estanteria->getNombre()}}">
            </form>
        </div>
        @endforeach
    </div>

    <div>
        <ul>
            @foreach ($datosTrastero['almacenCajas'] as $caja)
                @if(($caja->getIdBalda()==null)&&($caja->getIdEstanteria()==null))
                    <li>
                        <form action="" method="POST">
                            <input type="hidden" name="idCaja" value="{{$caja->getId()}}">
                            <span class="papeleraOculta" contenteditable="false">{{$caja->getNombre()}}</span>
                        </form>
                    </li> 
                @endif
            @endforeach   
        </ul>
    </div>
</div>


<!-- Modal -->
<form action="" method="POST">
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Seleccionar Ubicación: </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 
        <div>{{$mensaje}}</div>
        <div>
            <label for="estanteria">Estantería</label>
            <select id="seleccionEstanteria" name="estanteria">
                @foreach($datosTrastero['almacenEstanterias'] as $clave=>$valor)
                <option>{{$datosTrastero['almacenEstanterias'][$clave]->getNombre()}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="balda">Balda</label>
            <select id="seleccionBalda" name="balda">
                
            </select>  
        </div>
        <div>
            <label><input id="sinAsignar" type="checkbox" name="sinAsignar">Sin asignar</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">VOLVER</button>
        <input class="btn btn-info" type="submit" name="añadirUbicacion" value="Añadir">
      </div>
    </div>
  </div>
</div>
</form>


@endsection