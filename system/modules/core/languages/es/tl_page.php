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

$GLOBALS['TL_LANG']['tl_page']['title'][0] = 'Nombre de página';
$GLOBALS['TL_LANG']['tl_page']['title'][1] = 'El nombre de página se muestra en la navegación de la web.';
$GLOBALS['TL_LANG']['tl_page']['alias'][0] = 'Alias de página';
$GLOBALS['TL_LANG']['tl_page']['alias'][1] = 'El alias de página es una referencia única a la página que puede ser llamada en vez de la ID de página. Es especialmente útil si TYPOlight utiliza URL estáticas.';
$GLOBALS['TL_LANG']['tl_page']['type'][0] = 'Tipo de página';
$GLOBALS['TL_LANG']['tl_page']['type'][1] = 'Por favor selecciona el tipo de página en función de su fin.';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][0] = 'Título de página';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][1] = 'El título de página se muestra en la etiqueta TITLE de tu web y en los resultados de búsquedas. No debería contener más de 65 caracteres. Si deja este campo en blanco, se utilizará el nombre de página.';
$GLOBALS['TL_LANG']['tl_page']['language'][0] = 'Idioma';
$GLOBALS['TL_LANG']['tl_page']['robots'][0] = 'Etiqueta Robots';
$GLOBALS['TL_LANG']['tl_page']['robots'][1] = 'Aquí puedes definir cómo los motores de búsqueda acceden a la página.';
$GLOBALS['TL_LANG']['tl_page']['description'][0] = 'Descripción de página';
$GLOBALS['TL_LANG']['tl_page']['description'][1] = 'Puedes introducir una breve descripción de la página que se mostrará en los motores de búsqueda. Un motor de búsqueda normalmente indica entre 150 y 300 caracteres.';
$GLOBALS['TL_LANG']['tl_page']['redirect'][0] = 'Tipo de redireccionamiento';
$GLOBALS['TL_LANG']['tl_page']['redirect'][1] = 'Las redirecciones temporales enviaran una cabecera HTTP 302, y las permanentes una cabecera HTTP 301.';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][0] = 'Redireccionar a';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][1] = 'Por favor selecciona una página destino del árbol de páginas.';
$GLOBALS['TL_LANG']['tl_page']['fallback'][0] = 'Fallo idioma';
$GLOBALS['TL_LANG']['tl_page']['fallback'][1] = 'TYPOlight automáticamente redirecciona un visitante a la página raiz de una web en su idioma o a la página de fallo de idioma. Si no existe una página de fallo de idioma, se mostrará el mensaje de error <em>No pages found</em>.';
$GLOBALS['TL_LANG']['tl_page']['dns'][0] = 'Nombre de dominio';
$GLOBALS['TL_LANG']['tl_page']['dns'][1] = 'Si asignas un nombre de dominio a la página raiz de la web, tus visitas serán redirigidas a esta web cuando introduzcan el dominio correspondiente (por ejemplo <em>midominio.com</em> o <em>subdominio.midominio.com</em>).';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][0] = 'Dirección e-mail del administrador de la web';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][1] = 'La dirección e-mail del administrador de la web se utilizará como remitente para los mensajes auto generados (por ejemplo e-mails de activación o confirmación).';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][0] = 'Formato de fecha';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][1] = 'La variable de formato de fecha será parseada con la función PHP date().';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][0] = 'Formato de hora';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][1] = 'La variable de formato de hora será parseada con la función PHP date().';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][0] = 'Formato de fecha y hora';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][1] = 'La variable de formato de fecha y hora será parseada con la función PHP date().';
$GLOBALS['TL_LANG']['tl_page']['createSitemap'][0] = 'Crear un mapa del sitio XML';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][0] = 'Nombre de archivo';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][1] = 'Por favor, introduzca un nombre para el archivo XML.';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][0] = 'Utilizar HTTPS en sitemaps';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][1] = 'Generar las URL del sitemap de este sitio web utilizando <em>https://</em>.';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][0] = 'Redireccionar a otra página';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][1] = 'Si seleccionas esta opción, los visitantes serán redirigidos a otra página (por ejemplo una página de conexión o de bienvenida).';
$GLOBALS['TL_LANG']['tl_page']['protected'][0] = 'Proteger página';
$GLOBALS['TL_LANG']['tl_page']['protected'][1] = 'Si seleccionas esta opción puedes restringir el acceso a ciertos miembros de grupos.';
$GLOBALS['TL_LANG']['tl_page']['groups'][0] = 'Miembros de grupos permitidos';
$GLOBALS['TL_LANG']['tl_page']['groups'][1] = 'Aquí puede dar acceso a uno o más grupos de usuarios. Si no selecciona un grupo, cualquier usuario registrado tendrá acceso a la página.';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][0] = 'Asignar un diseño';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][1] = 'Por defecto una página utiliza el mismo diseño que su página padre. Si seleccionas esta opción, puedes asignar un diseño nuevo a la página actual y sus subpáginas.';
$GLOBALS['TL_LANG']['tl_page']['layout'][0] = 'Diseño página';
$GLOBALS['TL_LANG']['tl_page']['layout'][1] = 'Por favor selecciona un diseño de página. Puede editar o crear diseños utilizando el módulo <em>page layout</em>.';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][0] = 'Diseño para dispositivos  móviles';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][1] = 'Este diseño será utilizado si el visitante esta accediendo desde un dispositivo móvil.';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][0] = 'Asignar un valor timeout cache';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][1] = 'Por defecto una página utiliza el mismo timeout cache que su página padre. Si seleccionas esta opción, puedes asignar un valor nuevo de timeout cache a la página actual y sus subpáginas.';
$GLOBALS['TL_LANG']['tl_page']['cache'][0] = 'Cache timeout';
$GLOBALS['TL_LANG']['tl_page']['cache'][1] = 'Dentro del periodo de cache timeout, una página será cargada de la tabla de cache. Esto disminuirá el tiempo de carga de tus páginas.';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][0] = 'Asignar permisos';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][1] = 'Los permisos te permiten definir hasta qué punto un usuario del back end puede modificar una página y sus artículos. Si no seleccionas esta opción, la página utiliza los mismos permisos que su página padre.';
$GLOBALS['TL_LANG']['tl_page']['cuser'][0] = 'Propietario';
$GLOBALS['TL_LANG']['tl_page']['cuser'][1] = 'Por favor selecciona un usuario como propietario de la página actual.';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][0] = 'Grupo';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][1] = 'Por favor selecciona un grupo como propietario de la página actual.';
$GLOBALS['TL_LANG']['tl_page']['chmod'][0] = 'Permisos';
$GLOBALS['TL_LANG']['tl_page']['chmod'][1] = 'Cada página tiene tres niveles de acceso: uno para el usuario, uno para el grupo y otro para todos los demás. Puedes asignar diferentes permisos para cada nivel.';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][0] = 'No buscar en esta página';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][1] = 'Si seleccionas esta opción, la página actual será excluida de las operaciones de búsqueda en la web.';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][0] = 'Clase CSS';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][1] = 'Si introduces un nombre de clase aquí, se utilizará como atributo de clase en el menú de navegación. Lo que permite dar formato a elementos de navegación independientemente.';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][0] = 'Mostrar en sitemap';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][1] = 'Indica si la página aparece en el sitemap (mapa del sitio).';
$GLOBALS['TL_LANG']['tl_page']['hide'][0] = 'Esconde página de la navegación';
$GLOBALS['TL_LANG']['tl_page']['hide'][1] = 'Si selecciona esta opción, la página actual no se mostrará en la navegación de la web.';
$GLOBALS['TL_LANG']['tl_page']['guests'][0] = 'Mostrar sólo a los invitados';
$GLOBALS['TL_LANG']['tl_page']['guests'][1] = 'Ocultar la página desde el menú de navegación si un miembro está conectado.';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][0] = 'Navegación por pestañas';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][1] = 'Este número especifica la posición de elemento de navegación actual en el orden de las pestañas. Puede introducir un número entre 1 y 32767.';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][0] = 'Tecla acceso';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][1] = 'Una tecla de acceso es un único carácter que se puede ser asignado a un elemento de la navegación. Si un visitante pulsa simultáneamente la tecla [ALT] y la tecla de acceso, el elemento de la navegación estará preparado para recibir una acción (focus).';
$GLOBALS['TL_LANG']['tl_page']['published'][0] = 'Publicada';
$GLOBALS['TL_LANG']['tl_page']['published'][1] = 'Si esta opción no esta seleccionada, la página no puede ser vista por los visitantes de tu web.';
$GLOBALS['TL_LANG']['tl_page']['start'][0] = 'Mostrar desde';
$GLOBALS['TL_LANG']['tl_page']['start'][1] = 'Si introduces una fecha aquí, la página actual no será visible antes de esa fecha.';
$GLOBALS['TL_LANG']['tl_page']['stop'][0] = 'Mostrar hasta';
$GLOBALS['TL_LANG']['tl_page']['stop'][1] = 'Si introduces una fecha aquí, la página actual no será visible después de esa fecha.';
$GLOBALS['TL_LANG']['tl_page']['title_legend'] = 'Nombre y tipo';
$GLOBALS['TL_LANG']['tl_page']['meta_legend'] = 'Información Meta';
$GLOBALS['TL_LANG']['tl_page']['system_legend'] = 'Configuración de sistema';
$GLOBALS['TL_LANG']['tl_page']['redirect_legend'] = 'Ajustes de redirección';
$GLOBALS['TL_LANG']['tl_page']['dns_legend'] = 'Ajustes DNS';
$GLOBALS['TL_LANG']['tl_page']['global_legend'] = 'Configuración global';
$GLOBALS['TL_LANG']['tl_page']['mobile_legend'] = 'Configuración de dispositivos móviles';
$GLOBALS['TL_LANG']['tl_page']['sitemap_legend'] = 'Mapa web XML';
$GLOBALS['TL_LANG']['tl_page']['forward_legend'] = 'Redirigir automáticamente';
$GLOBALS['TL_LANG']['tl_page']['protected_legend'] = 'Protección de acceso';
$GLOBALS['TL_LANG']['tl_page']['layout_legend'] = 'Ajustes de diseño';
$GLOBALS['TL_LANG']['tl_page']['cache_legend'] = 'Ajustes de caché';
$GLOBALS['TL_LANG']['tl_page']['chmod_legend'] = 'Privilegios de acceso';
$GLOBALS['TL_LANG']['tl_page']['search_legend'] = 'Ajustes de búsqueda';
$GLOBALS['TL_LANG']['tl_page']['expert_legend'] = 'Ajustes avanzados';
$GLOBALS['TL_LANG']['tl_page']['tabnav_legend'] = 'Navegación con teclado';
$GLOBALS['TL_LANG']['tl_page']['publish_legend'] = 'Ajustes de publicación';
$GLOBALS['TL_LANG']['tl_page']['permanent'] = 'permanente';
$GLOBALS['TL_LANG']['tl_page']['temporary'] = 'temporal';
$GLOBALS['TL_LANG']['tl_page']['map_default'] = 'Predeterminado';
$GLOBALS['TL_LANG']['tl_page']['map_always'] = 'Mostrar siempre';
$GLOBALS['TL_LANG']['tl_page']['map_never'] = 'Nunca mostrar';
$GLOBALS['TL_LANG']['tl_page']['new'][0] = 'Nueva página';
$GLOBALS['TL_LANG']['tl_page']['new'][1] = 'Crear una página nueva';
$GLOBALS['TL_LANG']['tl_page']['show'][0] = 'Detalles página';
$GLOBALS['TL_LANG']['tl_page']['show'][1] = 'Mostrar los detalles de la página con ID %s';
$GLOBALS['TL_LANG']['tl_page']['edit'][0] = 'Editar página';
$GLOBALS['TL_LANG']['tl_page']['edit'][1] = 'Editar página con ID %s';
$GLOBALS['TL_LANG']['tl_page']['cut'][0] = 'Mover página';
$GLOBALS['TL_LANG']['tl_page']['cut'][1] = 'Mover página ID %s';
$GLOBALS['TL_LANG']['tl_page']['copy'][0] = 'Duplicar página';
$GLOBALS['TL_LANG']['tl_page']['copy'][1] = 'Duplicar página con ID %s';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][0] = 'Duplicar página con subpáginas';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][1] = 'Duplicar la página con ID %s y subpáginas';
$GLOBALS['TL_LANG']['tl_page']['delete'][0] = 'Eliminar página';
$GLOBALS['TL_LANG']['tl_page']['delete'][1] = 'Eliminar página con ID %s';
$GLOBALS['TL_LANG']['tl_page']['toggle'][0] = 'Publicar/retirar página';
$GLOBALS['TL_LANG']['tl_page']['toggle'][1] = 'Publicar/retirar la página con ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][0] = 'Pegar después';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][1] = 'Pegar después de la página con ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][0] = 'Pegar dentro';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][1] = 'Pegar dentro de la página con ID %s';
$GLOBALS['TL_LANG']['tl_page']['articles'][0] = 'Editar artículos';
$GLOBALS['TL_LANG']['tl_page']['articles'][1] = 'Editar artículos de la página con ID %s';
$GLOBALS['TL_LANG']['CACHE'][0] = 'No realizar caché';
$GLOBALS['TL_LANG']['CACHE'][5] = '5 segundos';
$GLOBALS['TL_LANG']['CACHE'][15] = '15 segundos';
$GLOBALS['TL_LANG']['CACHE'][30] = '30 segundos';
$GLOBALS['TL_LANG']['CACHE'][60] = '1 minuto';
$GLOBALS['TL_LANG']['CACHE'][300] = '5 minutos';
$GLOBALS['TL_LANG']['CACHE'][900] = '15 minutos';
$GLOBALS['TL_LANG']['CACHE'][1800] = '30 minutos';
$GLOBALS['TL_LANG']['CACHE'][3600] = '1 hora';
$GLOBALS['TL_LANG']['CACHE'][10800] = '3 horas';
$GLOBALS['TL_LANG']['CACHE'][21600] = '6 horas';
$GLOBALS['TL_LANG']['CACHE'][43200] = '12 horas';
$GLOBALS['TL_LANG']['CACHE'][86400] = '1 día';
$GLOBALS['TL_LANG']['CACHE'][259200] = '3 días';
$GLOBALS['TL_LANG']['CACHE'][604800] = '7 días (1 semana)';
$GLOBALS['TL_LANG']['CACHE'][2592000] = '30 días (1 mes)';
