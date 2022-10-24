
@extends('app')

@section('title', 'Perfil Usuario')

@section('content')
    <div class="container">
        <form action="">
            
            <div class="row mb-3">
                <label for="nombre" class="col-sm-2 col-lg-1 col-form-label form-text">Nombre: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="nombre" id="nombre" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
            <div class="row mb-3">
                <label for="apellido" class="col-sm-2 col-lg-1 col-form-label form-text">Apellido: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="apellido" id="apellido" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></button>
            </div>
            <div class="row mb-3">
                <label for="usuario" class="col-sm-2 col-lg-1 col-form-label form-text">Usuario: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="usuario" id="usuario" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></button>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-sm-2 col-lg-1 col-form-label form-text">Password: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="text" name="password" id="password" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></button>
            </div>
            <div class="row mb-3">
                <label for="correo" class="col-sm-2 col-lg-1 col-form-label form-text">Correo: </label>
                <div class="col-10 col-sm-8 col-md-5">
                    <input class="form-control" type="email" name="correo" id="Correo" disabled>
                </div>
                <button class="col col-sm-1 btn btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></button>
            </div>
			
            <button type="submit" class="btn btn-primary">Volver</button>

        </form>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">New message to @mdo</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form>
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Recipient:</label>
                  <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Message:</label>
                  <textarea class="form-control" id="message-text"></textarea>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Send message</button>
            </div>
          </div>
        </div>
      </div>
@endsection