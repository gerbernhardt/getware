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
define('_ACCESS','Acceso');
define('_ACCESSDENIED','Acceso Denegado');
define('_ACTIVE','Activo');
define('_ADD','Agregar');
define('_AXX','Agregar Matriz');
define('_UXX','Upload Matriz');
define('_ADMINISTRATION','Administración');
define('_BACK','Atras');
define('_BLOCK','Bloque');
define('_BLOCKNOFOUND','Bloque no encontrado!');
define('_BLOCKS','Bloques');
define('_CHANGEPASSWORD','Cambiar Contraseña');
define('_CLOSE','Cerrar');
define('_COMMENTS','Comentarios');
define('_CONTENT','Contenido');
define('_DENIEDACCESS','Acceso denegado!');
define('_DESCRIPTION','Descripcion');
define('_EDIT','Editar');
define('_EMAIL','E-mail');
define('_ENTER','Entrar');
define('_ERROR','Error!');
define('_EXIT','Salir');
define('_FILE','Archivo');
define('_GOBACK','<a href="javascript:history.go(-1)">'.'Volver atras</a>');
define('_GOHOME','<a href="?">'.'Ir al Home</a>');
define('_HEADER','Cabecera');
define('_HOME','Home');
define('_INACTIVE','Inactivo');
define('_LOGIN','Login');
define('_LOGOUT','Logout');
define('_MODULE','Modulo');
define('_MODULEADMIN','Modulo ADMIN');
define('_MODULEINACTIVE','Modulo Inactivo');
define('_MODULEUSERS','Esta area es para usuarios registrados!');
define('_MODULEGUEST','Esta area es para invitados!');
define('_MODULENOFOUND','El modulo no existe!');
define('_MODULES','Modulos');
define('_MODULESADMIN','Modulos ADMIN');
define('_NEXT','Siguiente');
define('_NOFOUND','No encontrado!');
define('_NONE','Ninguno');
define('_NOUSERS','Solo invitados');
define('_PAGEGENERATION_SQL','Pagina generada con');
define('_PAGEGENERATION_TIME','Pagina generada en');
define('_PASSWORD','Password');
define('_PASSCHANGED','El password a sido cambiado!');
define('_PASSDIFFERENT','Los password son diferentes!');
define('_PASSNEW','Nuevo password');
define('_PASSCONFIRM','Confirmar nuevo password');
define('_PRINT','Imprimir');
define('_PRIVILEGE','Privilegio');
define('_PRIVILEGES','Privilegios');
define('_QUERY','Consulta');
define('_REGISTER','Registrarse');
define('_REMOVE','Remover');
define('_RETYPEPASSWORD','Re-escribe el Password');
define('_SEARCH','Buscar');
define('_SEARCHNOFOUND','No se encontraron resultados!');
define('_SAVE','Guardar');
define('_SECONDS','Segundos');
define('_SEND','Enviar');
define('_SETTINGS','Preferencias');
define('_UNKWON','Desconocido');
define('_UPLOAD','Cargar');
define('_USER','Usuario');

?>