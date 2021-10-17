use tramite_documentario;
#table PUESTOS
create table PUESTOS(
id_puesto int auto_increment primary key,
nombre varchar(255)
)ENGINE=INNODB;


#table PERSONAS
create table PERSONAS(
id_persona int auto_increment primary key,
dni_ruc varchar(11) unique key,
nombre varchar(255),
apellido varchar(255),
correo varchar(255) unique key,
cod_estudiante char(10),
id_puesto int not null,
constraint fk_persona_puesto foreign key (id_puesto) references PUESTOS(id_puesto)
)ENGINE = INNODB;


#table USUARIOS
create table USUARIOS(
id_usuario int auto_increment primary key,
username varchar(255),
password varchar(255),
enabled int,
reset_password_token varchar(255),
dni_ruc varchar(11) not null,
constraint fk_usuario_persona foreign key (dni_ruc) references PERSONAS(dni_ruc)
)ENGINE = INNODB;


#table ROLES
create table ROLES(
id_rol int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table USUARIOS_ROLES
create table USUARIOS_ROLES(
id_usuario int not null,
id_rol int not null,
unique key (id_usuario,id_rol),
constraint fk_usuario_rol_usuario foreign key (id_usuario) references USUARIOS(id_usuario),
constraint fk_usuario_rol_rol foreign key (id_rol) references ROLES(id_rol)
)ENGINE = INNODB;


#table TIPO_SOLICITUDES
create table TIPO_SOLICITUDES(
id_tipo_solicitud int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table SOLICITUDES
create table SOLICITUDES(
id_solicitud int auto_increment primary key,
descripcion varchar(255),
id_tipo_solicitud int not null,
constraint fk_solicitud_tipo_solicitud foreign key (id_tipo_solicitud) references TIPO_SOLICITUDES(id_tipo_solicitud) 
)ENGINE = INNODB;


#table ESTADOS
create table ESTADOS(
id_estado int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table ESTADOS_SOLICITUDES
create table ESTADOS_SOLICITUDES(
id_estado_solicitud int auto_increment primary key,
id_solicitud int not null,
id_estado int not null,
fecha date,
descripcion varchar(255),
unique key (id_solicitud, id_estado),
constraint fk_estado_solicitud_solicitud foreign key (id_solicitud) references SOLICITUDES(id_solicitud),
constraint fk_estado_solicitud_estado foreign key (id_estado) references ESTADOS(id_estado)
)ENGINE = INNODB;


#table ROL_SOLICITUDES
create table ROL_SOLICITUDES(
id_rol_solicitud int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table PERSONAS_SOLICITUDES
create table PERSONAS_SOLICITUDES(
id_persona_solicitud int auto_increment primary key,
id_persona int not null,
id_solicitud int not null,
id_rol_solicitud int not null,
unique key (id_persona, id_solicitud, id_rol_solicitud),
constraint fk_persona_solicitud_persona foreign key (id_persona) references PERSONAS(id_persona),
constraint fk_persona_solicitud_solicitud foreign key (id_solicitud) references SOLICITUDES(id_solicitud),
constraint fk_persona_solicitud_rol_solicitud foreign key (id_rol_solicitud) references ROL_SOLICITUDES(id_rol_solicitud)
)ENGINE = INNODB;


#table TIPO_ARCHIVOS
create table TIPO_ARCHIVOS(
id_tipo_archivo int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table ARCHIVOS
create table ARCHIVOS(
id_archivo int auto_increment primary key,
descripcion varchar(255),
file varchar(255),
id_tipo_archivo int not null,
id_solicitud int,
constraint fk_archivo_tipo_archivo foreign key (id_tipo_archivo) references TIPO_ARCHIVOS(id_tipo_archivo),
constraint fk_archivo_solicitud foreign key (id_solicitud) references SOLICITUDES(id_solicitud)
)ENGINE = INNODB;

insert into PUESTOS(nombre) values ('Estudiante');
insert into PUESTOS(nombre) values ('Empresa');
insert into PUESTOS(nombre) values ('Secretaria/o');
insert into PUESTOS(nombre) values ('Rector');
insert into PUESTOS(nombre) values ('Vicerrector');
insert into PUESTOS(nombre) values ('Docente');

insert into PERSONAS(correo, dni_ruc, nombre, apellido, id_puesto) values ('gino.francisco@hotmail.com', '73360326', 'Gino', 'Ascencio Gomez', 1);

insert into ROLES (nombre) values ('ROLE_USER');
insert into ROLES (nombre) values ('ROLE_ADMIN');

insert into ROL_SOLICITUDES(nombre) values ('Emisor');
insert into ROL_SOLICITUDES(nombre) values ('Receptor');

insert into TIPO_SOLICITUDES(nombre) values ('Asesor Tesis');
insert into TIPO_SOLICITUDES(nombre) values ('Asignacion Jurado Tesis');
insert into TIPO_SOLICITUDES(nombre) values ('Evaluacion Tesis');

insert into ESTADOS(nombre) values ('Proceso');
insert into ESTADOS(nombre) values ('Archivado');
insert into ESTADOS(nombre) values ('Aprobado');
insert into ESTADOS(nombre) values ('Rechazado');

insert into TIPO_ARCHIVOS(nombre) values ("RESPUESTA");
insert into TIPO_ARCHIVOS(nombre) values ("FUT");
insert into TIPO_ARCHIVOS(nombre) values ("Constancia de Notas");
insert into TIPO_ARCHIVOS(nombre) values ("Certificado de Estudios");
insert into TIPO_ARCHIVOS(nombre) values ("Constancia de Matricula");