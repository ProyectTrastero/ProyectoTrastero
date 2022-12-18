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
<div class="container contenido">
    <div class="row">
        <p>{{$mensaje}}</p>
        @if($datosTrastero['guardado'])
        <span id="guardadoModificado" value="true" ></span>
        @else
        <span id="guardadoModificado" value="false" ></span>
        @endif
    </div>
    @if(!empty($datosTrastero['listadoEliminar']))
    <input type="hidden" id="mostrarModal" value="si">
    @else
    <input type="hidden" id="mostrarModal" value="no">
    @endif
    
    @if(!empty($datosTrastero['mensaje2']))
    <input type="hidden" id="mostrarModal2" value="si">
    @else
    <input type="hidden" id="mostrarModal2" value="no">
    @endif
    
    <div class="row">
        <form action="" method="POST">
            <div>
                @if($datosTrastero['tipo']=="guardar")
                <label for="nombre">NOMBRE:</label>
                <input type="text" name="nombre" id="nombre">
                @endif
                <button type="submit" name="añadirEstanteria">Añadir Estanteria</button>
                <!-- Button trigger modal -->
                <button type="button"data-bs-toggle="modal" data-bs-target="#staticBackdrop">Añadir Caja</button>
            </div>
            <div>
                @if($datosTrastero['tipo']=="guardar")
                <button type="submit" name="guardar">Guardar</button>
                @endif
                <button type="submit" name="volverAcceso">Volver</button>
            </div>
        </form>
    </div>
    
    <div class="row">
        @foreach ($datosTrastero['almacenEstanterias'] as $estanteria)
        <div class="col-4">
            <ul class="estanteria">
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
            <form action="" method="POST">
                <button type="submit" name="añadirBalda">Añadir Balda</button>
                <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">

                <input type="hidden" name="nombreEstanteria" value="{{$estanteria->getNombre()}}">
            </form>
            </ul>
            
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


<!-- Modal añadir Caja-->
<form action="" method="POST">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="false" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Añadir Ubicación:</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>{{$datosTrastero['mensaje2']}}</div>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                    <!--<button type="submit" name="añadirUbicacion" id="botonAñadir" class="btn btn-secondary" data-bs-dismiss="modal">AÑADIR</button>-->
                    <input type="submit" name="añadirUbicacion"  id="botonAñadir" value="AÑADIR">
                   
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Modal confirmar eliminacion-->
<form action="" method="POST">
    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="false" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmar selección:</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Los elementos seleccionados contienen productos en su interior</p>
                    <p>Si los elimina se perderá su ubicación.</p>
                    <p>¿Desea continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="cancelar" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
                    <button type="submit" name="aceptar" class="btn btn-secondary" data-bs-dismiss="modal">SI</button>
                </div>
            </div>
        </div>
    </div>
</form>



@endsection