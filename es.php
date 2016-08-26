<?php
/*
 * Keep It Simple, Stupid!
 * Filename: es.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

# DECLARACION DE CONSTANTES PARA EL IDIOMA
define('CHARSET','iso-8859-1');
define('_ACCESS','Acceso');
define('_ACCESSDENIED','Acceso Denegado');
define('_ACCOUNTCREATED','La cuenta ha sido creada!');
define('_ACTIVE','Activo');
define('_ADD','Agregar');
define('_AXX','Agregar Matriz');
define('_UXX','Upload Matriz');
define('_ADMINISTRATION','Administración');
define('_ALL','Todos');
define('_ALLUSERS','Todos los usuarios');
define('_ARTICLE','Articulo');
define('_ARTICLENOFOUND','Articulo no encontrado');
define('_ARTICLES','Articulos');
define('_ARTICLESNOFOUND','No hay articulos!');
define('_BACK','Atras');
define('_BACKEND','Backend');
define('_BACKENDINACTIVE','Backend inactivo!');
define('_BACKENDNOFOUND','Backend no encontrado!');
define('_BLOCK','Bloque');
define('_BLOCKNOFOUND','Bloque no encontrado!');
define('_BLOCKS','Bloques');
define('_BROWSERS','Exploradores');
define('_CENTER','Centro');
define('_CHANGEPASSWORD','Cambiar Contraseña');
define('_CLOSE','Cerrar');
define('_COMMENTS','Comentarios');
define('_CONTENT','Contenido');
define('_COUNTRY','Pais');
define('_DATE','Fecha');
define('_DEFAULTMODULE','Modulo predeterminado');
define('_DEMON','Demonio');
define('_DEMONS','Demonios');
define('_DENIEDACCESS','Acceso denegado!');
define('_DESCRIPTION','Descripcion');
define('_DOWNLOAD','Descargar');
define('_DOWNLOADNOFOUND','Descarga no encontrada');
define('_DOWNLOADS','Descargas');
define('_DOWNLOADSNOFOUND','No hay descargas!');
define('_EDIT','Editar');
define('_EMAIL','E-mail');
define('_ENTER','Entrar');
define('_ERROR','Error!');
define('_EXCEL','Exportar a Excel');
define('_EXIT','Salir');
define('_FILE','Archivo');
define('_FROM','De');
define('_FORMERROR','Hay un error en el formulario!');
define('_FOOTER','Pie');
//define('_GOBACK','<a href=\'javascript:history.go(-1)\'>'.'Volver atras').'</a>');
//define('_GOHOME','<a href=\'?\'>'.'Ir al Home').'</a>');
define('_GROUP','Grupo');
define('_GUESTS','Invitados');
define('_HEADER','Cabecera');
define('_HELLO','Hola');
define('_HOME','Home');
define('_HOUR','Hora');
define('_HOURS','Horas');
define('_IMAGE','Imagen');
define('_IMAGENOFOUND','No se encuentra la imagen!');
define('_IMAGESNOFOUND','No se encuentra ninguna imagen!');
define('_INACTIVE','Inactivo');
define('_INDEX','Indice');
define('_INFO','Info');
define('_INSERT','Insertar');
define('_INVALIDINFO','Informacion invalida!');
define('_INVALIDMAIL','E-mail invalido!');
define('_INVALIDPASS','Contraseña invalida!');
define('_INVALIDUSER','Usuario invalido!');
define('_INVISIBLE','Invisible');
define('_ISSUE','Issue');
define('_KEYWORDS','Keywords');
define('_LANGUAGE','Lenguaje');
define('_LEFT','Izquierda');
define('_LEVEL','Nivel');
define('_LINK','Enlace');
define('_LOGIN','Login');
define('_LOGO','Logo');
define('_LOGOUT','Logout');
define('_MAKE','Generar');
define('_MAKENEW','Crear Nuevo');
define('_MESSAGE','Mensaje');
define('_MODULE','Modulo');
define('_MODULEADMIN','Modulo ADMIN');
define('_MODULEINACTIVE','Modulo Inactivo');
define('_MODULEUSERS','Esta area es para usuarios registrados!');
define('_MODULEGUEST','Esta area es para invitados!');
define('_MODULENOFOUND','El modulo no existe!');
define('_MODULES','Modulos');
define('_MODULESADMIN','Modulos \'ADMIN\'');
define('_MSGNOFOUND','No hay mensajes!');
define('_NAME','Nombre');
define('_NEW','Nuevo');
define('_NEWS','Noticias');
define('_NEXT','Siguiente');
define('_NOEDIT','No editable!');
define('_NOFOUND','No encontrado!');
define('_NONE','Ninguno');
define('_NOUSERS','Solo invitados');
define('_ON','el');
define('_OPERATOR','operador');
define('_OPTION','Opcion');
define('_OS','Sistemas Operativos');
define('_OTHERS','Otros');
define('_PAGE','Pagina');
define('_PAGEGENERATION_SQL','Pagina generada con');
define('_PAGEGENERATION_TIME','Pagina generada en');
define('_PASSWORD','Password');
define('_PASSCHANGED','El password a sido cambiado!');
define('_PASSDIFFERENT','Los password son diferentes!');
define('_PASSMIN','El password debe contener al menos 6 caracteres!');
define('_PASSNEW','Nuevo password');
define('_PASSCONFIRM','Confirmar nuevo password');
define('_PAY','Pagar');
define('_PHOTO','Foto');
define('_PHOTOS','Fotos');
define('_PHOTOSNOFOUND','No hay fotos disponibles!');
define('_POSTEDBY','Posteado por');
define('_PRIMARY','Primario');
define('_PRINT','Imprimir');
define('_PRIVILEGE','Privilegio');
define('_PRIVILEGES','Privilegios');
define('_QUERY','Consulta');
define('_RATE','Valorar');
define('_RATED','Valoraciones');
define('_READ','Leer');
define('_READS','lecturas');
define('_READMORE','leer mas...');
define('_REALNAME','Nombre real');
define('_RECOMMEND','Recomendar');
define('_RECORDS','Registros');
define('_REFRESH','Refrescar');
define('_REGISTER','Registrarse');
define('_REGISTERDATE','Registrado el');
define('_REMOVE','Remover');
define('_RETYPEPASSWORD','Re-escribe el Password');
define('_RSS','RSS');
define('_RSSPROBLEM','Actualmente hay un problema con los titulares de este sitio');
define('_RESULTS','Resultados');
define('_RIGHT','Derecha');
define('_SEARCH','Buscar');
define('_SEARCHENGINE','Buscadores');
define('_SEARCHNOFOUND','No se encontraron resultados!');
define('_SAVE','Guardar');
define('_SECONDS','Segundos');
define('_SECUNDARY','Secundario');
define('_SELECTCOLOR','Seleccionar color');
define('_SEND','Enviar');
define('_SENDMAIL','Enviar mail');
define('_SETTINGS','Preferencias');
define('_SHOUTBOX','ShoutBox');
define('_SHOUTBOXARCHIVE','Archivo ShoutBox');
define('_SHOUTBOXNOFOUND','No se han posteado ShoutBox');
define('_SITE','Sitio Web');
define('_SITENAME','Nombre del sitio');
define('_SIZE','Tamaño');
define('_SLOGAN','Slogan');
define('_SMALLIMAGE','Imagen chica');
define('_SQLCONSULT','consultas SQL');
define('_STATISTICS','Estadisticas');
define('_STOCK','Stock');
define('_STORY','Historia');
define('_SUBJECT','Asunto');
define('_SUBMITARTICLE','Enviar Articulo');
define('_SURVEY','Encuesta');
define('_SURVEYS','Encuestas');
define('_SURVEYNOFOUND','La encuesta no existe!');
define('_SURVEYSNOFOUND','No hay encuestas!');
define('_THEME','Theme');
define('_TIPNOFOUND','Tip no encontrado!');
define('_TIP','Tip');
define('_TIPS','Tips');
define('_TIPSNOFOUND','No hay tips!');
define('_TITLE','Titulo');
define('_TO','Para');
define('_TOPAGE','de');
define('_TOPIC','Tema');
define('_TOPICS','Temas');
define('_TOTAL','Total');
define('_TUTORIAL','Tutorial');
define('_TUTORIALNOFOUND','Tutorial no encontrado');
define('_TUTORIALS','Tutoriales');
define('_TUTORIALSNOFOUND','No hay tutoriales!');
define('_TYPE','Tipo');
define('_TYPENEWPASSWORD','Nuevo Password');
define('_UBICATION','Ubicacion');
define('_UNKWON','Desconocido');
define('_UP','Subir');
define('_UPLOAD','Cargar');
define('_URL','URL');
define('_USER','Usuario');
define('_USERINFO','Informacion de usuario');
define('_USERNAME','Usuario');
define('_USERNOFOUND','El usuario no esta registrado!');
define('_USERPRIV','Privilegios');
define('_USERS','Usuarios');
define('_VIEW','Ver');
define('_VIEWCOMMENTS','Ver comentarios');
define('_VIEWS','Views');
define('_VISIBLE','visible');
define('_VOTE','Votar');
define('_VOTES','Votos');
define('_WEBLINK','Enlace Web');
define('_WEBLINKNOFOUND','Enlace no encontrado!');
define('_WEBLINKS','Enlaces Web');
define('_WEBLINKSNOFOUND','No hay enlaces!');
define('_WEBSITE','Sitio Web');
define('_WRITECOMMENT','Escribir Comentario');

?>