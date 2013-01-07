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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Tablas cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Por favor selecciona la tablas de cache que deseas truncar.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Usuario del Front end';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Registrar automáticamente a un usuario del front end a páginas índice protegidas';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Trabajo';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Descripción';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Borrar cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'La cache ha sido borrada';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Actualización en vivo';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'ID de Actualización en vivo';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Ir a la actualización en vivo';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'La versión de Contao %s es la más actual';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'La nueva versión de Contao %s está disponible';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Por favor introduce tu ID de actualización en vivo';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'La carpeta temporal (system/tmp) no tiene permiso de escritura';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Ver la bitácora de cambios';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Ejecutar la actualización';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Contenido del archivo de actualización';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Archivos respaldados';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Archivos actualizados';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Reconstruir el índice de búsqueda';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Reconstruir el índice';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'No se encontraron páginas donde buscar';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = '¡Por favor espera a que la página se haya cargado completamente antes de continuar!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Por favor espera mientras el índice de búsqueda es reconstruido.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'El índice de búsqueda ha sido reconstruido. Ahora puede continuar.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Por favor introduzca tus %s aqui.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Vaciar el índice de búsqueda';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Trunca las tablas <em>tl_search</em> y <em>tl_search_index</em>. Después, hay que reconstruir el índice de búsqueda (ver arriba).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Vacia la tabla deshacer';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Trunca el <em>tl_undo</em> tabla que almacena los registros eliminados. Este trabajo elimina permanentemente estos registros.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Vaciar la tabla de versiones';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Trunca el <em>tl_version</em> tabla que almacena las versiones anteriores de un registro. Este trabajo elimina permanentemente estos registros.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Vaciar la caché de imágenes';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Elimina las imágenes generadas automáticamente y después purga la caché de la página, así que no hay enlaces a los recursos eliminados.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Vaciar la caché de script';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Elimina los .css y .js generados automáticamente, recrea las hojas de estilo internas y luego purga el caché de páginas.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Vaciar la caché de páginas';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Elimina las versiones en caché de las páginas de front end.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Purgar la caché interna';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Elimina las versiones en caché de los archivos DCA y lenguaje. Usted puede desactivar permanentemente la caché interna en la configuración de back-end.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Vaciar  la carpeta temp';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Elimina los archivos temporarios.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Volver a crear los archivos XML';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Vuelve a crear los archivos XML (sitemaps y feeds) y después purga la caché de la página, así que no hay enlaces a los recursos eliminados.';
