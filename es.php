<?php

#
# Getware: Ultra-Secure Script
# Filename: language/es.php, 2004/06/07
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

# DECLARACION DE CONSTANTES PARA EL IDIOMA
define('CHARSET',utf8_encode('iso-8859-1'));
define('_ACCESS',utf8_encode('Acceso'));
define('_ACCESSDENIED',utf8_encode('Acceso Denegado'));
define('_ACCOUNTCREATED',utf8_encode('La cuenta ha sido creada!'));
define('_ACTIVE',utf8_encode('Activo'));
define('_ADD',utf8_encode('Agregar'));
define('_AXX',utf8_encode('Agregar Matriz'));
define('_UXX',utf8_encode('Upload Matriz'));
define('_ADMINISTRATION',utf8_encode('Administración'));
define('_ALL',utf8_encode('Todos'));
define('_ALLUSERS',utf8_encode('Todos los usuarios'));
define('_ARTICLE',utf8_encode('Articulo'));
define('_ARTICLENOFOUND',utf8_encode('Articulo no encontrado'));
define('_ARTICLES',utf8_encode('Articulos'));
define('_ARTICLESNOFOUND',utf8_encode('No hay articulos!'));
define('_BACK',utf8_encode('Atras'));
define('_BACKEND',utf8_encode('Backend'));
define('_BACKENDINACTIVE',utf8_encode('Backend inactivo!'));
define('_BACKENDNOFOUND',utf8_encode('Backend no encontrado!'));
define('_BLOCK',utf8_encode('Bloque'));
define('_BLOCKNOFOUND',utf8_encode('Bloque no encontrado!'));
define('_BLOCKS',utf8_encode('Bloques'));
define('_BROWSERS',utf8_encode('Exploradores'));
define('_CENTER',utf8_encode('Centro'));
define('_CHANGEPASSWORD',utf8_encode('Cambiar Contraseña'));
define('_CLOSE',utf8_encode('Cerrar'));
define('_COMMENTS',utf8_encode('Comentarios'));
define('_CONTENT',utf8_encode('Contenido'));
define('_COUNTRY',utf8_encode('Pais'));
define('_DATE',utf8_encode('Fecha'));
define('_DEFAULTMODULE',utf8_encode('Modulo predeterminado'));
define('_DEMON',utf8_encode('Demonio'));
define('_DEMONS',utf8_encode('Demonios'));
define('_DENIEDACCESS',utf8_encode('Acceso denegado!'));
define('_DESCRIPTION',utf8_encode('Descripcion'));
define('_DOWNLOAD',utf8_encode('Descargar'));
define('_DOWNLOADNOFOUND',utf8_encode('Descarga no encontrada'));
define('_DOWNLOADS',utf8_encode('Descargas'));
define('_DOWNLOADSNOFOUND',utf8_encode('No hay descargas!'));
define('_EDIT',utf8_encode('Editar'));
define('_EMAIL',utf8_encode('E-mail'));
define('_ENTER',utf8_encode('Entrar'));
define('_ERROR',utf8_encode('Error!'));
define('_EXCEL',utf8_encode('Exportar a Excel'));
define('_EXIT',utf8_encode('Salir'));
define('_FILE',utf8_encode('Archivo'));
define('_FROM',utf8_encode('De'));
define('_FORMERROR',utf8_encode('Hay un error en el formulario!'));
define('_FOOTER',utf8_encode('Pie'));
//define('_GOBACK',utf8_encode('<a href=\'javascript:history.go(-1)\'>'.'Volver atras').'</a>'));
//define('_GOHOME',utf8_encode('<a href=\'?\'>'.'Ir al Home').'</a>'));
define('_GROUP',utf8_encode('Grupo'));
define('_GUESTS',utf8_encode('Invitados'));
define('_HEADER',utf8_encode('Cabecera'));
define('_HELLO',utf8_encode('Hola'));
define('_HOME',utf8_encode('Home'));
define('_HOUR',utf8_encode('Hora'));
define('_HOURS',utf8_encode('Horas'));
define('_IMAGE',utf8_encode('Imagen'));
define('_IMAGENOFOUND',utf8_encode('No se encuentra la imagen!'));
define('_IMAGESNOFOUND',utf8_encode('No se encuentra ninguna imagen!'));
define('_INACTIVE',utf8_encode('Inactivo'));
define('_INDEX',utf8_encode('Indice'));
define('_INFO',utf8_encode('Info'));
define('_INSERT',utf8_encode('Insertar'));
define('_INVALIDINFO',utf8_encode('Informacion invalida!'));
define('_INVALIDMAIL',utf8_encode('E-mail invalido!'));
define('_INVALIDPASS',utf8_encode('Contraseña invalida!'));
define('_INVALIDUSER',utf8_encode('Usuario invalido!'));
define('_INVISIBLE',utf8_encode('Invisible'));
define('_ISSUE',utf8_encode('Issue'));
define('_KEYWORDS',utf8_encode('Keywords'));
define('_LANGUAGE',utf8_encode('Lenguaje'));
define('_LEFT',utf8_encode('Izquierda'));
define('_LEVEL',utf8_encode('Nivel'));
define('_LINK',utf8_encode('Enlace'));
define('_LOGIN',utf8_encode('Login'));
define('_LOGO',utf8_encode('Logo'));
define('_LOGOUT',utf8_encode('Logout'));
define('_MAKE',utf8_encode('Generar'));
define('_MAKENEW',utf8_encode('Crear Nuevo'));
define('_MESSAGE',utf8_encode('Mensaje'));
define('_MODULE',utf8_encode('Modulo'));
define('_MODULEADMIN',utf8_encode('Modulo ADMIN'));
define('_MODULEINACTIVE',utf8_encode('Modulo Inactivo'));
define('_MODULEUSERS',utf8_encode('Esta area es para usuarios registrados!'));
define('_MODULEGUEST',utf8_encode('Esta area es para invitados!'));
define('_MODULENOFOUND',utf8_encode('El modulo no existe!'));
define('_MODULES',utf8_encode('Modulos'));
define('_MODULESADMIN',utf8_encode('Modulos \'ADMIN\''));
define('_MSGNOFOUND',utf8_encode('No hay mensajes!'));
define('_NAME',utf8_encode('Nombre'));
define('_NEW',utf8_encode('Nuevo'));
define('_NEWS',utf8_encode('Noticias'));
define('_NEXT',utf8_encode('Siguiente'));
define('_NOEDIT',utf8_encode('No editable!'));
define('_NOFOUND',utf8_encode('No encontrado!'));
define('_NONE',utf8_encode('Ninguno'));
define('_NOUSERS',utf8_encode('Solo invitados'));
define('_ON',utf8_encode('el'));
define('_OPERATOR',utf8_encode('operador'));
define('_OPTION',utf8_encode('Opcion'));
define('_OS',utf8_encode('Sistemas Operativos'));
define('_OTHERS',utf8_encode('Otros'));
define('_PAGE',utf8_encode('Pagina'));
define('_PAGEGENERATION_SQL',utf8_encode('Pagina generada con'));
define('_PAGEGENERATION_TIME',utf8_encode('Pagina generada en'));
define('_PASSWORD',utf8_encode('Password'));
define('_PASSCHANGED',utf8_encode('El password a sido cambiado!'));
define('_PASSDIFFERENT',utf8_encode('Los password son diferentes!'));
define('_PASSMIN',utf8_encode('El password debe contener al menos 6 caracteres!'));
define('_PASSNEW',utf8_encode('Nuevo password'));
define('_PASSCONFIRM',utf8_encode('Confirmar nuevo password'));
define('_PAY',utf8_encode('Pagar'));
define('_PHOTO',utf8_encode('Foto'));
define('_PHOTOS',utf8_encode('Fotos'));
define('_PHOTOSNOFOUND',utf8_encode('No hay fotos disponibles!'));
define('_POSTEDBY',utf8_encode('Posteado por'));
define('_PRIMARY',utf8_encode('Primario'));
define('_PRINT',utf8_encode('Imprimir'));
define('_PRIVILEGE',utf8_encode('Privilegio'));
define('_PRIVILEGES',utf8_encode('Privilegios'));
define('_QUERY',utf8_encode('Consulta'));
define('_RATE',utf8_encode('Valorar'));
define('_RATED',utf8_encode('Valoraciones'));
define('_READ',utf8_encode('Leer'));
define('_READS',utf8_encode('lecturas'));
define('_READMORE',utf8_encode('leer mas...'));
define('_REALNAME',utf8_encode('Nombre real'));
define('_RECOMMEND',utf8_encode('Recomendar'));
define('_RECORDS',utf8_encode('Registros'));
define('_REFRESH',utf8_encode('Refrescar'));
define('_REGISTER',utf8_encode('Registrarse'));
define('_REGISTERDATE',utf8_encode('Registrado el'));
define('_REMOVE',utf8_encode('Remover'));
define('_RETYPEPASSWORD',utf8_encode('Re-escribe el Password'));
define('_RSS',utf8_encode('RSS'));
define('_RSSPROBLEM',utf8_encode('Actualmente hay un problema con los titulares de este sitio'));
define('_RESULTS',utf8_encode('Resultados'));
define('_RIGHT',utf8_encode('Derecha'));
define('_SEARCH',utf8_encode('Buscar'));
define('_SEARCHENGINE',utf8_encode('Buscadores'));
define('_SEARCHNOFOUND',utf8_encode('No se encontraron resultados!'));
define('_SAVE',utf8_encode('Guardar'));
define('_SECONDS',utf8_encode('Segundos'));
define('_SECUNDARY',utf8_encode('Secundario'));
define('_SELECTCOLOR',utf8_encode('Seleccionar color'));
define('_SEND',utf8_encode('Enviar'));
define('_SENDMAIL',utf8_encode('Enviar mail'));
define('_SETTINGS',utf8_encode('Preferencias'));
define('_SHOUTBOX',utf8_encode('ShoutBox'));
define('_SHOUTBOXARCHIVE',utf8_encode('Archivo ShoutBox'));
define('_SHOUTBOXNOFOUND',utf8_encode('No se han posteado ShoutBox'));
define('_SITE',utf8_encode('Sitio Web'));
define('_SITENAME',utf8_encode('Nombre del sitio'));
define('_SIZE',utf8_encode('Tamaño'));
define('_SLOGAN',utf8_encode('Slogan'));
define('_SMALLIMAGE',utf8_encode('Imagen chica'));
define('_SQLCONSULT',utf8_encode('consultas SQL'));
define('_STATISTICS',utf8_encode('Estadisticas'));
define('_STOCK',utf8_encode('Stock'));
define('_STORY',utf8_encode('Historia'));
define('_SUBJECT',utf8_encode('Asunto'));
define('_SUBMITARTICLE',utf8_encode('Enviar Articulo'));
define('_SURVEY',utf8_encode('Encuesta'));
define('_SURVEYS',utf8_encode('Encuestas'));
define('_SURVEYNOFOUND',utf8_encode('La encuesta no existe!'));
define('_SURVEYSNOFOUND',utf8_encode('No hay encuestas!'));
define('_THEME',utf8_encode('Theme'));
define('_TIPNOFOUND',utf8_encode('Tip no encontrado!'));
define('_TIP',utf8_encode('Tip'));
define('_TIPS',utf8_encode('Tips'));
define('_TIPSNOFOUND',utf8_encode('No hay tips!'));
define('_TITLE',utf8_encode('Titulo'));
define('_TO',utf8_encode('Para'));
define('_TOPAGE',utf8_encode('de'));
define('_TOPIC',utf8_encode('Tema'));
define('_TOPICS',utf8_encode('Temas'));
define('_TOTAL',utf8_encode('Total'));
define('_TUTORIAL',utf8_encode('Tutorial'));
define('_TUTORIALNOFOUND',utf8_encode('Tutorial no encontrado'));
define('_TUTORIALS',utf8_encode('Tutoriales'));
define('_TUTORIALSNOFOUND',utf8_encode('No hay tutoriales!'));
define('_TYPE',utf8_encode('Tipo'));
define('_TYPENEWPASSWORD',utf8_encode('Nuevo Password'));
define('_UBICATION',utf8_encode('Ubicacion'));
define('_UNKWON',utf8_encode('Desconocido'));
define('_UP',utf8_encode('Subir'));
define('_UPLOAD',utf8_encode('Cargar'));
define('_URL',utf8_encode('URL'));
define('_USER',utf8_encode('Usuario'));
define('_USERINFO',utf8_encode('Informacion de usuario'));
define('_USERNAME',utf8_encode('Usuario'));
define('_USERNOFOUND',utf8_encode('El usuario no esta registrado!'));
define('_USERPRIV',utf8_encode('Privilegios'));
define('_USERS',utf8_encode('Usuarios'));
define('_VIEW',utf8_encode('Ver'));
define('_VIEWCOMMENTS',utf8_encode('Ver comentarios'));
define('_VIEWS',utf8_encode('Views'));
define('_VISIBLE',utf8_encode('visible'));
define('_VOTE',utf8_encode('Votar'));
define('_VOTES',utf8_encode('Votos'));
define('_WEBLINK',utf8_encode('Enlace Web'));
define('_WEBLINKNOFOUND',utf8_encode('Enlace no encontrado!'));
define('_WEBLINKS',utf8_encode('Enlaces Web'));
define('_WEBLINKSNOFOUND',utf8_encode('No hay enlaces!'));
define('_WEBSITE',utf8_encode('Sitio Web'));
define('_WRITECOMMENT',utf8_encode('Escribir Comentario'));

?>