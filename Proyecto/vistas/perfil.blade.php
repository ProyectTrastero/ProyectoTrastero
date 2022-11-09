
@extends('app')

@section('title', 'Perfil Usuario')

@section('content')
    <div class="container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            
            <div class="row mb-3">
                <label for="nombre" class="col-sm-2 col-lg-1 col-form-label form-text">Nombre: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="nombre" id="nombre" value="{{$nombre}}">
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Nombre:" data-bs-value="{{$nombre}}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="apellido" class="col-sm-2 col-lg-1 col-form-label form-text">Apellido: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="apellido" id="apellido" value="{{$apellidos}}">
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Apellidos:" data-bs-value="{{$apellidos}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="alias" class="col-sm-2 col-lg-1 col-form-label form-text">Alias: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="alias" id="alias" value="{{$alias}}">
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Alias:" data-bs-value="{{$alias}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="clave" class="col-sm-2 col-lg-1 col-form-label form-text">Clave: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="clave" id="clave" value="{{$clave}}">
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Clave:" data-bs-value="{{$clave}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="correo" class="col-sm-2 col-lg-1 col-form-label form-text">Correo: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="email" name="correo" id="Correo" value="{{$correo}}">
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Correo:" data-bs-value="{{$correo}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
			
            <button type="submit" class="btn btn-primary" name="volver" value="volver">Volver</button>
            <button type="submit" class="btn btn-primary" name="guardar" value="guardar" >Guardar</button>

        </form>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="perfilModalLabel"></h1>
              
            </div>
            <div class="modal-body">
              <form>
                <div class="mb-3">
                  <label id="lblCampo" for="inputCampo" class="col-form-label"></label>
                  <input id="inputCampo" type="text" class="form-control" disabled>
                </div>
                <div class="mb-3">
                  <label id="lblNewCampo" for="inputNewCampo" class="col-form-label"></label>
                  <input id="inputNewCampo" class="form-control" ></input>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button id="btnClose" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Guardar</button>
            </div>
          </div>
        </div>
      </div>
@endsection