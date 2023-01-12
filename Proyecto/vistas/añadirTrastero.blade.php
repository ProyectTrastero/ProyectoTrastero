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
<div>
        @if($datosTrastero['guardado'])
        <span id="guardadoModificado" value="true" ></span>
        @else
        <span id="guardadoModificado" value="false" ></span>
        @endif
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
        
</div>
<div class="avisos">
    @if($mensaje!="")
    <div class="alert alert-{{$tipoMensaje}} alert-dismissible fade show" role="alert">
        {{$mensaje}}
        <span type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></span>
    </div>
    @endif
</div>
<div class="container">
    <div class="row" id="atDiv1">
        <form action="" method="POST">
            <div class="divVolver">
                <button class="volver btn1Volver" type="submit" name="volverAcceso">Volver</button>
            </div>
            <div id="atDiv2">
                @if($datosTrastero['tipo']=="guardar")
                <label for="nombre">NOMBRE:</label>
                <input type="text" name="nombre" id="nombre">
                <button type="submit" name="guardar">Guardar</button>
                @else
                <h3><b>{{$datosTrastero['trastero']->getNombre()}}</b></h3>
                @endif
            </div>
            <div id="atDiv3">
                <div id="atDiv4">
                    <button type="submit" name="añadirEstanteria">Añadir Estanteria</button>
                    <!-- Button trigger modal -->
                    <button type="button"data-bs-toggle="modal" data-bs-target="#staticBackdrop">Añadir Caja</button>
                </div>
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
                        <span class="editable" contenteditable="false">{{$estanteria->getNombre()}}</span>
                        <button type="submit" class="papeleraOculta fa-sharp fa-solid fa-trash-can" name="eliminarEstanteria" style="color: rgb(255,255,255,0)"></button>   
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
                            <span class="editable"  contenteditable="false">{{$balda->getNombre()}}</span>
                            @if(count($baldasRecuperadas)==1)
                            <button type="submit" class="papeleraOculta primerabalda fa-sharp fa-solid fa-trash-can" name="eliminarBalda" style="color: rgb(255,255,255,0)"></button>
                            @else 
                            <button type="submit" class="papeleraOculta fa-sharp fa-solid fa-trash-can" name="eliminarBalda" style="color: rgb(255,255,255,0)"></button>
                            @endif
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
                                    <span class="editable" contenteditable="false">{{$caja->getNombre()}}</span>
                                    <button type="submit" class="papeleraOculta fa-sharp fa-solid fa-trash-can" name="eliminarCaja" style="color: rgb(255,255,255,0)"></button>
                                </form>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </ul>
            @endforeach
            <form action="" method="POST">
                <button id="atboton1" type="submit" name="añadirBalda">Añadir Balda</button>
                <input type="hidden" name="idEstanteria" value="{{$estanteria->getId()}}">

                <input type="hidden" name="nombreEstanteria" value="{{$estanteria->getNombre()}}">
            </form>
            </ul>
            
        </div>
        @endforeach
    </div>
   
    @if(!empty($datosTrastero['almacenCajas']))
        @php
            $cajasSinUbicar=$datosTrastero['almacenCajas'][0]->obtenerCajasSinUbicar($bd);
        @endphp
        @if(!empty($cajasSinUbicar))
        <div class="col-5 atCajas">
            <ul class="row">
                @foreach ($datosTrastero['almacenCajas'] as $caja)
                    @if(($caja->getIdBalda()==null)&&($caja->getIdEstanteria()==null))
                    <li class="col-4">
                        <form action="" method="POST">
                            <input type="hidden" name="idCaja" value="{{$caja->getId()}}">
                            <span class="editable" contenteditable="false">{{$caja->getNombre()}}</span>
                            <button type="submit" class="papeleraOculta fa-sharp fa-solid fa-trash-can" name="eliminarCaja" style="color: rgb(255,255,255,0)"></button>
                        </form>
                    </li> 
                    @endif
                @endforeach   
            </ul>
        </div>
        @endif
    @endif
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
                    <button class="volver" type="button" data-bs-dismiss="modal">Volver</button>
                    <button type="submit" name="añadirCaja"  id="botonAñadir">Añadir</button>
                   
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
                    <button class="volver" type="submit" name="cancelar" data-bs-dismiss="modal">NO</button>
                    <button type="submit" name="aceptar" data-bs-dismiss="modal">SI</button>
                </div>
            </div>
        </div>
    </div>
</form>


@endsection