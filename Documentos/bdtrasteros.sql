-- 1.- Creamos la Base de Datos
-- creamos la base de datos
create database bdtrasteros DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
-- Seleccionamos la base de datos "bdtrasteros"
use bdtrasteros;
-- 2.- Creamos las tablas
-- 2.1.1.- Tabla usuarios
create table if not exists usuarios(
    id int auto_increment primary key,
    alias varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null,
    nombre varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null,
    apellidos varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null,
    clave varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null, 
    correo varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci unique not null
);

-- 2.1.2 .- Tabla trasteros
create table if not exists trasteros(
    id int auto_increment primary key,
    nombre varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null,
    idUsuario int not null,
    constraint fk_trastero_usuario foreign key(idUsuario) references usuarios(id) on update cascade on delete cascade 
);

-- 2.1.3.- Tabla estanterias
create table if not exists estanterias(
    id int auto_increment primary key,
    nombre varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null,
    numero int not null,
    idTrastero int, 
    constraint fk_estanteria_trastero foreign key(idTrastero) references trasteros(id) on update cascade on delete cascade

);

-- 2.1.4 Tabla baldas
create table if not exists baldas(
    id int auto_increment primary key,
    nombre varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci,
    numero int not null,
    idEstanteria int not null,
    constraint fk_balda_estanteria foreign key(idEstanteria) references estanterias(id) on update cascade on delete cascade 
);

-- 2.1.5 Tabla cajas
create table if not exists cajas(
    id int auto_increment primary key,
    nombre varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci,
    numero int not null,
    idTrastero int not null,
    idEstanteria int,
    idBalda int,
    constraint fk_caja_trastero foreign key(idTrastero) references trasteros(id) on update cascade on delete cascade,
    constraint fk_caja_estanteria foreign key(idEstanteria) references estanterias(id) on update cascade on delete cascade,
    constraint fk_caja_balda foreign key(idBalda) references baldas(id) on update cascade on delete cascade 
);

-- 2.1.6 Tabla productos
create table if not exists productos(
    id int auto_increment primary key,
    nombre varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null,
    descripcion text,
    fechaIngreso date not null,
    idTrastero int not null, 
    idEstanteria int, 
    idBalda int, 
    idCaja int,
    constraint fk_producto_trastero foreign key(idTrastero) references trasteros(id) on update cascade on delete cascade,
    constraint fk_producto_estanteria foreign key(idEstanteria) references estanterias(id) on update cascade on delete cascade, 
    constraint fk_producto_balda foreign key(idBalda) references baldas(id) on update cascade on delete cascade,
    constraint fk_producto_caja foreign key(idCaja) references cajas(id) on update cascade on delete cascade
);

-- 2.1.7 Tabla etiquetas
create table if not exists etiquetas(
    id int auto_increment primary key,
    nombre varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci not null,
    idUsuario int not null,
    constraint fk_etiqueta_usuario foreign key(idUsuario) references usuarios(id) on update cascade on delete cascade
);

-- 2.1.8 Tabla etiquetasproductos
create table if not exists etiquetasproductos(
    id int auto_increment primary key,
    idEtiqueta int not null,
    idProducto int not null,
    constraint fk_etiquetaproducto_producto foreign key(idProducto) references productos(id) on update cascade on delete cascade
);





