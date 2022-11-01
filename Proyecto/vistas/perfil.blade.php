
@extends('app')

@section('title', 'Perfil Usuario')

@section('content')
    <div class="container">
        <form action="">
            
            <div class="row mb-3">
                <label for="nombre" class="col-sm-2 col-lg-1 col-form-label form-text">Nombre: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="nombre" id="nombre" value="{{$usuario->getNombre()}}" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Nombre:" data-bs-value="{{$usuario->getNombre()}}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="apellido" class="col-sm-2 col-lg-1 col-form-label form-text">Apellido: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="apellido" id="apellido" value="{{$usuario->getApellidos()}}" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Apellidos:" data-bs-value="{{$usuario->getApellidos()}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="usuario" class="col-sm-2 col-lg-1 col-form-label form-text">Alias: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="usuario" id="usuario" value="{{$usuario->getAlias()}}" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Alias:" data-bs-value="{{$usuario->getAlias()}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-sm-2 col-lg-1 col-form-label form-text">Password: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="password" id="password" value="{{$usuario->getClave()}}" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Clave:" data-bs-value="{{$usuario->getClave()}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="correo" class="col-sm-2 col-lg-1 col-form-label form-text">Correo: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="email" name="correo" id="Correo" value="{{$usuario->getCorreo()}}" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#perfilModal" data-bs-campo="Correo:" data-bs-value="{{$usuario->getCorreo()}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
			
            <a href="<?php __DIR__ ?>./../public/acceso.php"><button type="button" class="btn btn-primary">Volver</button></a>

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