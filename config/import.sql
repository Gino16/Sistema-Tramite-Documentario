use tramite_documentario;
#table puestos
create table puestos(
id_puesto int auto_increment primary key,
nombre varchar(255)
)ENGINE=INNODB;


#table personas
create table personas(
id_persona int auto_increment primary key,
dni_ruc varchar(11) unique key,
nombre varchar(255),
apellido varchar(255),
correo varchar(255),
cod_estudiante char(10),
id_puesto int not null,
constraint fk_persona_puesto foreign key (id_puesto) references puestos(id_puesto)
)ENGINE = INNODB;


#table usuarios
create table usuarios(
id_usuario int auto_increment primary key,
username varchar(255),
password varchar(255),
enabled int,
reset_password_token varchar(255),
dni_ruc varchar(11) not null,
constraint fk_usuario_persona foreign key (dni_ruc) references personas(dni_ruc)
)ENGINE = INNODB;


#table roles
create table roles(
id_rol int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table usuarios_roles
create table usuarios_roles(
id_usuario int not null,
id_rol int not null,
unique key (id_usuario,id_rol),
constraint fk_usuario_rol_usuario foreign key (id_usuario) references usuarios(id_usuario),
constraint fk_usuario_rol_rol foreign key (id_rol) references roles(id_rol)
)ENGINE = INNODB;


#table tipo_solicitudes
create table tipo_solicitudes(
id_tipo_solicitud int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table solicitudes
create table solicitudes(
id_solicitud int auto_increment primary key,
descripcion varchar(255),
id_tipo_solicitud int not null,
constraint fk_solicitud_tipo_solicitud foreign key (id_tipo_solicitud) references tipo_solicitudes(id_tipo_solicitud) 
)ENGINE = INNODB;


#table estados
create table estados(
id_estado int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table estados_solicitudes
create table estados_solicitudes(
id_estado_solicitud int auto_increment primary key,
id_solicitud int not null,
id_estado int not null,
fecha date,
descripcion varchar(255),
unique key (id_solicitud, id_estado),
constraint fk_estado_solicitud_solicitud foreign key (id_solicitud) references solicitudes(id_solicitud),
constraint fk_estado_solicitud_estado foreign key (id_estado) references estados(id_estado)
)ENGINE = INNODB;


#table roles_solicitudes
create table roles_solicitudes(
id_rol_solicitud int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table personas_solicitudes
create table personas_solicitudes(
id_persona_solicitud int auto_increment primary key,
id_persona int not null,
id_solicitud int not null,
id_rol_solicitud int not null,
unique key (id_persona, id_solicitud, id_rol_solicitud),
constraint fk_persona_solicitud_persona foreign key (id_persona) references personas(id_persona),
constraint fk_persona_solicitud_solicitud foreign key (id_solicitud) references solicitudes(id_solicitud),
constraint fk_persona_solicitud_rol_solicitud foreign key (id_rol_solicitud) references roles_solicitudes(id_rol_solicitud)
)ENGINE = INNODB;


#table tipo_archivos
create table tipo_archivos(
id_tipo_archivo int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table archivos
create table archivos(
id_archivo int auto_increment primary key,
descripcion varchar(255),
file varchar(255),
id_tipo_archivo int not null,
id_solicitud int,
constraint fk_archivo_tipo_archivo foreign key (id_tipo_archivo) references tipo_archivos(id_tipo_archivo),
constraint fk_archivo_solicitud foreign key (id_solicitud) references solicitudes(id_solicitud)
)ENGINE = INNODB;

insert into puestos(nombre) values ('Estudiante');
insert into puestos(nombre) values ('Empresa');
insert into puestos(nombre) values ('Secretaria/o');
insert into puestos(nombre) values ('Rector');
insert into puestos(nombre) values ('Vicerrector');
insert into puestos(nombre) values ('Docente');

insert into personas(correo, dni_ruc, nombre, apellidos, id_puesto) values ('gino.francisco@hotmail.com', '73360326', 'Gino', 'Ascencio Gomez', 1);

insert into roles (nombre) values ('ROLE_USER');
insert into roles (nombre) values ('ROLE_ADMIN');

insert into rol_solicitudes(nombre) values ('Emisor');
insert into rol_solicitudes(nombre) values ('Receptor');

insert into tipo_solicitudes(nombre) values ('Asesor Tesis');
insert into tipo_solicitudes(nombre) values ('Asignacion Jurado Tesis');
insert into tipo_solicitudes(nombre) values ('Evaluacion Tesis');

insert into estados(nombre) values ('Proceso');
insert into estados(nombre) values ('Archivado');
insert into estados(nombre) values ('Aprobado');
insert into estados(nombre) values ('Rechazado');

insert into tipo_archivos(nombre) values ("RESPUESTA");
insert into tipo_archivos(nombre) values ("FUT");
insert into tipo_archivos(nombre) values ("Constancia de Notas");
insert into tipo_archivos(nombre) values ("Certificado de Estudios");
insert into tipo_archivos(nombre) values ("Constancia de Matricula");