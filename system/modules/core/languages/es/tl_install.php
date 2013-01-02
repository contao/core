<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/es/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Herramienta de instalación de Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Inicio de sesión de herramienta de instalación';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'La herramienta de instalación ha sido bloqueada';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Por razones de seguridad, la herramienta de seguridad ha sido bloqueada después de que se introdujo una contraseña equivocada más de tres veces seguidas. Para desbloquearla, edite el archivo de configuración local y establezca el valor <em>installCount</em> a <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Contraseña';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Por favor introduzca la contraseña para la herramienta de instalación. La contraseña de la herramienta de instalación no es la misma que la contraseña del módulo de gestión de Contao.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Contraseña de herramienta de instalación';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Para asegurar este script puede insertar un comando <strong>exit;</strong> en <strong>/install.php</strong> o puede eliminar completamente el archivo del servidor. En caso de eliminarlo, tendrá que editar los ajustes del sistema directamente en el archivo de configuración local.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Generar una clave de encriptación';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Esta clave se utiliza para guardar datos encriptados. Toma en cuenta que los datos encriptados sólo pueden desencriptarse con esta clave. Por lo tanto, anota la y no la cambies si ya existen datos encriptados. Deje en blanco para generar una clave aleatoria.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Verificar conexión a la base de datos';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Introduzca los parámetros de conexión a su base de datos.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Secuenciación';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Para más información consulte el <a href="http://dev.mysql.com/doc/refman/5.1/es/charset-unicode-sets.html" onclick="window.open(this.href); return false;">manual de MySQL</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Actualizar tablas de base de datos';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Tome en cuenta que el asistente de actualización sólo ha sido probado con los controladores MySQL y MySQLi. Si está utilizando una base de datos diferente (p.ej. Oracle), es probable que tenga que instalar o actualizar su base de datos manualmente. En este caso, explore la carpeta <strong>system/modules</strong> y busque en todas las subcarpetas los archivos <strong>config/database.sql</strong>.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importar una plantilla';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Seleccione un archivo <em>.sql</em> del directorio <em>templates</em>.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Crear usuario administrador';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Si importó el sitio de ejemplo, el nombre de usuario administrador es <strong>k.jones</strong> y su contraseña es <strong>kevinjones</strong>. Consulte el sitio de ejemplo para más información.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = '¡Felicitaciones!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Ahora inicie una sesión en el área administrativa de Contao y verifique los ajustes del sistema. Después visite su sitio para asegurarse que Contao esta funcionando correctamente.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modificar archivos a través de FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Introduzca sus credenciales de FTP para que Contao pueda modificar archivos a través de FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Aceptar licencia';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Inicio de sesión administrativa de Contao';
$GLOBALS['TL_LANG']['tl_install']['passError'] = '¡Cambie la contraseña preestablecida para evitar acceso no autorizado!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'La contraseña preestablecida ha sido cambiada.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Grabar contraseña';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = '¡Cree una clave de encriptación!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'La clave de encriptación debe tener al menos 12 caracteres.';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'La clave de encriptación ha sido creada.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Generar clave de encriptación';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Generar o grabar clave';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Conexión a base de datos exitosa.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = '¡Falló la conexión a la base de datos!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Controlador';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Servidor';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Nombre de usuario';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Base de datos';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Conexión persistente';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Conjunto de caracteres';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Secuenciación';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Número de puerto';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Grabar ajustes de base de datos';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Cambiar la secuenciación afectará todas las tablas con el prefijo <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = '¡La base de datos no está actualizada!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'La base de datos está actualizada.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Actualizar base de datos';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Cambiar colación';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Al parecer está actualizando Contao desde una versión anterior a %s. Si este es el caso, <strong>se requiere ejecutar la actualización de la versión %s</strong> para garantizar la integridad de los datos.';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Ejecute la actualización de la versión %s.';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Ejecutar versión %s actualizar - paso %s';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Seleccione un archivo de plantilla';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Plantilla importada en %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = '¡Los datos existentes serán eliminados!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Plantillas';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'No vaciar tablas';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importar plantilla';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = '¡Los datos existentes serán eliminados! ¿Desea continuar?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Indique todos los campos para crear un usuario administrador';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Se ha creado un usuario administrador.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Crear cuenta de administrador';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Ha instalado Contao con éxito.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'Servidor FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Ruta relativa al directorio de Contao (p.ej. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'Nombre de usuario FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'Contraseña FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Conexión segura';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Conectar vía FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'Puerto FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Grabar ajustes FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'No se pudo establecer conexión al servidor FTP %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'No se pudo iniciar sesión como "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'No se pudo localizar el directorio de Contao %s';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Crear tablas nuevas';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Agregar columnas nuevas';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Cambiar columnas existentes';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Eliminar columnas existentes';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Eliminar tablas existentes';
