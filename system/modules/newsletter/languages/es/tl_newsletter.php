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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Asunto';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Por favor introduzca en asunto del boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Alias de boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Un alias de boletín es una referencia única al boletín que puede utilizarse para llamarlo en vez de su ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'Contenido';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Por favor introduce el contenido del boletín. Utilliza los comodines <em>##email##</em> para insertar el e-mail del recipiente. Generar los enlaces de baja de suscripción como <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Contenido de texto';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Aqui puedes introducir el texto del boletín. Utiliza el formato <em>##email##</em> para insertar el e-mail del recipiente.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Agregar adjunto';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Adjunta uno o mas archivos al boletín.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Adjuntos';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Por favor selecciona los archivos que quieres adjuntar al boletín.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Plantilla e-mail';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Por favor selecciona una plantilla e-mail (grupo plantilla <em>mail_</em>).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Enviar como texto';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Si selecciona esta opción, el e-mail será enviado como texto. Todas las etiquetas HTML serán eliminadas.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Imágenes externas';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Si selecciona esta opción, no se incrustarán las imágenes en los boletines HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Nombre del remitente';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Aqui puedes introducir el nombre del remitente';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Dirección remitente';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Si no introduce una dirección e-mail de remitente, se utilizará el e-mail del administrador.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Mails por ciclo';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Para prevenir un timout del script, el proceso de envio se dividen en varios ciclos. Aqui puedes definir los e-mails por ciclo dependiendo del tiempo máximo de ejecución definido en tu php.ini';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Tiempo de espera en segundos';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Aqui pudes modificar el tiempo de espera entre cada ciclo para controlar el número de e-mails por minuto.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Iniciar ciclo';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'En el caso de que el proceso de envio sea interrumpido, puedes ingresar el número del ciclo para continuar con un destinatario en particular Puedes revisar cuantos correos electrónicos han sido enviados en el archivo <em>system/logs/newsletter_*.log</em>. Por ejemplo, si han sido enviados 120 correos electrónicos, ingresa "120" para continuar con el destinatario número 121(el conteo inicia en 0).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Enviar muestra a';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Enviar la una muestra del boletín a esta dirección e-mail';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Título y asunto';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'Contenido HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Contenido texto';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Documentso adjuntos';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Ajustes plantilla';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Ajustes avanzados';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Enviado';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Enviado el %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'No enviando';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Fecha mailing';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'El newsletter ha sido enviado a %s e-mails';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = 'Se ha/han desactivado la(s) direcciones de correo electrónico %s no válidas.';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'No hay suscripciones activas a este canal';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'De';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Adjuntos';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Enviar muestra';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = '¿Realmente quieres enviar el boletín?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nuevo boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Crear un boletín nuevo';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Detalles del boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Mostrar los detalles del boletín con ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Editar boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Editar boletín con ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Copiar boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Copiar boletín con ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Mover boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Mover boletín con ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Eliminar boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Eliminar boletín con ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Editar canal';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Editar el canal';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Pegar en este canal';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Colocar después del boletín con ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Enviar boletín';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Enviar boletín con ID %s';
