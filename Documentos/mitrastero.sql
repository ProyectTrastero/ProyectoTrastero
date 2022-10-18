-- 1.- Creamos la Base de Datos
-- creamos la base de datos
create database bdtrasteros DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Seleccionamos la base de datos "bdtrasteros"
use bdtrasteros;
-- 2.- Creamos las tablas
-- 2.1.1.- Tabla usuarios
create table if not exists usuarios(
    id int auto_increment primary key,
    usuario varchar(100) not null,
    contraseña varchar(100) not null, 
    nombre varchar(100) not null,
    apellidos varchar(100) not null,
    correo varchar(100) unique not null,
    telefono varchar(9) null
);
-- 2.1.2 .- Tabla trasteros
create table if not exists trasteros(
    id int auto_increment primary key,
    usuario int not null,
    nombre varchar(100) not null, 
    num_estanterias int, 
    num_cajas int,
    constraint fk_trastero_usuario foreign key(usuario) references usuarios(id) on update cascade on delete cascade 
);
-- 2.1.3.- Tabla productos
create table if not exists productos(
    id int auto_increment primary key,
    trastero int, 
    nombre varchar(200) not null,
    descripcion text, 
    estanteria int, 
    balda int, 
    caja int,
    constraint fk_producto_trastero foreign key(trastero) references trasteros(id) on update cascade on delete cascade,
    constraint fk_producto_estanteria foreign key(estanteria) references estanterias(id) on update cascade on delete cascade, 
    constraint fk_producto_balda foreign key(balda) references baldas(id) on update cascade on delete cascade,
    constraint fk_producto_caja foreign key(caja) references cajas(id) on update cascade on delete cascade
);
-- 2.1.4 Tabla estanterias
create table if not exists estanterias(
    id int auto_increment primary key,
    numero int,
    trastero int,
    num_baldas int, 
    constraint fk_estanteria_trastero foreign key(trastero) references trasteros(id) on update cascade on delete cascade

);
-- 2.1.5 Tabla etiquetas
create table if not exists etiquetas(
    id int primary key,
    usuario int not null,
    nombre varchar(20) not null,
    producto int(20) not null,
    constraint fk_etiqueta_producto foreign key(producto) references productos(id) on update cascade on delete cascade,
    constraint fk_etiqueta_usuario foreign key(usuario) references usuarios(id) on update cascade on delete cascade
);
-- 2.1.6 Tabla baldas
create table if not exists baldas(
    id int primary key,
    numero int not null,
    estanteria int not null,
    constraint fk_balda_estanteria foreign key(estanteria) references estanterias(id) on update cascade on delete cascade 
);
-- 2.1.7 Tabla cajas
create table if not exists cajas(
    id int primary key,
    numero varchar(20) not null,
    trastero int not null,
    estanteria int,
    balda int,
    constraint fk_caja_trastero foreign key(trastero) references trasteros(id) on update cascade on delete cascade,
    constraint fk_caja_estanteria foreign key(estanteria) references estanterias(id) on update cascade on delete cascade,
    constraint fk_caja_balda foreign key(balda) references baldas(id) on update cascade on delete cascade 
);



