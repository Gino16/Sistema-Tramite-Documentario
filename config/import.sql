use tramite_documentario;
#table PUESTOS
create table PUESTOS(
puesto_id int auto_increment primary key,
nombre varchar(255)
)ENGINE=INNODB;


#table PERSONAS
create table PERSONAS(
persona_id int auto_increment primary key,
dni_ruc varchar(11) unique key,
nombre varchar(255),
apellido varchar(255),
correo varchar(255) unique key,
cod_estudiante char(10),
puesto_id int not null,
constraint fk_persona_puesto foreign key (puesto_id) references PUESTOS(puesto_id)
)ENGINE = INNODB;


#table USUARIOS
create table USUARIOS(
usuario_id int auto_increment primary key,
username varchar(255),
password varchar(255),
enabled int,
reset_password_token varchar(255),
dni_ruc varchar(11) not null,
constraint fk_usuario_persona foreign key (dni_ruc) references PERSONAS(dni_ruc)
)ENGINE = INNODB;


#table ROLES
create table ROLES(
rol_id int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table USUARIOS_ROLES
create table USUARIOS_ROLES(
usuario_id int not null,
rol_id int not null,
unique key (usuario_id,rol_id),
constraint fk_usuario_rol_usuario foreign key (usuario_id) references USUARIOS(usuario_id),
constraint fk_usuario_rol_rol foreign key (rol_id) references ROLES(rol_id)
)ENGINE = INNODB;


#table TIPO_SOLICITUDES
create table TIPO_SOLICITUDES(
tipo_solicitud_id int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table SOLICITUDES
create table SOLICITUDES(
solicitud_id int auto_increment primary key,
descripcion varchar(255),
tipo_solicitud_id int not null,
constraint fk_solicitud_tipo_solicitud foreign key (tipo_solicitud_id) references TIPO_SOLICITUDES(tipo_solicitud_id) 
)ENGINE = INNODB;


#table ESTADOS
create table ESTADOS(
estado_id int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table ESTADOS_SOLICITUDES
create table ESTADOS_SOLICITUDES(
estado_solicitud_id int auto_increment primary key,
solicitud_id int not null,
estado_id int not null,
fecha date,
descripcion varchar(255),
unique key (solicitud_id, estado_id),
constraint fk_estado_solicitud_solicitud foreign key (solicitud_id) references SOLICITUDES(solicitud_id),
constraint fk_estado_solicitud_estado foreign key (estado_id) references ESTADOS(estado_id)
)ENGINE = INNODB;


#table ROL_SOLICITUDES
create table ROL_SOLICITUDES(
rol_solicitud_id int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table PERSONAS_SOLICITUDES
create table PERSONAS_SOLICITUDES(
persona_solicitud_id int auto_increment primary key,
persona_id int not null,
solicitud_id int not null,
rol_solicitud_id int not null,
unique key (persona_id, solicitud_id, rol_solicitud_id),
constraint fk_persona_solicitud_persona foreign key (persona_id) references PERSONAS(persona_id),
constraint fk_persona_solicitud_solicitud foreign key (solicitud_id) references SOLICITUDES(solicitud_id),
constraint fk_persona_solicitud_rol_solicitud foreign key (rol_solicitud_id) references ROL_SOLICITUDES(rol_solicitud_id)
)ENGINE = INNODB;


#table TIPO_ARCHIVOS
create table TIPO_ARCHIVOS(
tipo_archivo_id int auto_increment primary key,
nombre varchar(255)
)ENGINE = INNODB;


#table ARCHIVOS
create table ARCHIVOS(
archivo_id int auto_increment primary key,
descripcion varchar(255),
file varchar(255),
tipo_archivo_id int not null,
solicitud_id int,
constraint fk_archivo_tipo_archivo foreign key (tipo_archivo_id) references TIPO_ARCHIVOS(tipo_archivo_id),
constraint fk_archivo_solicitud foreign key (solicitud_id) references SOLICITUDES(solicitud_id)
)ENGINE = INNODB;

insert into PUESTOS(nombre) values ('Estudiante');
insert into PUESTOS(nombre) values ('Empresa');
insert into PUESTOS(nombre) values ('Secretaria/o');
insert into PUESTOS(nombre) values ('Rector');
insert into PUESTOS(nombre) values ('Vicerrector');
insert into PUESTOS(nombre) values ('Docente');

insert into PERSONAS(correo, dni_ruc, nombre, apellido, puesto_id) values ('gino.francisco@hotmail.com', '73360326', 'Gino', 'Ascencio Gomez', 1);

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