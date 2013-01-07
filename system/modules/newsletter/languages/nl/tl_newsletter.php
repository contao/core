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
 * @link https://www.transifex.com/projects/p/contao/language/nl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Onderwerp';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Geef onderwerp van de nieuwsbrief op.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Nieuwsbrief alias';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Een nieuwsbrief alias is een unieke referentie naar een nieuwsbrief waarmee deze aangeroepen kan worden in plaats van met zijn numerieke ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML inhoud';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Voer de HTML-inhoud van de nieuwsbrief in. Gebruik de wildcard <em>##email##</em> om het e-mail adres van de abonnee in te voegen.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Platte tekst inhoud';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Voer de platte tekst voor de nieuwsbrief in. Gebruik de wildcard <em>##email##</em> om het e-mailadres van de ontvanger in te voeren.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Bijlage toevoegen';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Voeg een of meerdere bijlage(n) toe aan de nieuwsbrief.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Bijlagen';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Selecteer de bestanden die u wilt toevoegen aan de nieuwsbrief.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'E-mail template';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Kies een e-mail template.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Als platte tekst versturen';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Als u deze optie kiest zal de nieuwsbrief als platte tekst e-mail worden verzonden. Alle HTML tags zullen worden verwijderd.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Externe afbeeldingen';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Geen afbeeldingen insluiten in de HTML-nieuwsbrief zelf.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Naam afzender';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Voer de naam van de afzender in.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Adres afzender';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Het e-mailadres van de administrator zal gebruikt worden als u hier geen ander e-mailadres opgeeft.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Berichten per cyclus';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Om te voorkomen dat er een time-out optreedt in het script is verzending opgesplitst in verschillende cycli.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Timeout in seconden';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Hier kunt u de wachttijd aanpassen tussen elke cyclus om controle te verkrijgen over het aantal e-mails per minuut.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Offset';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'In het geval dat het verzenden wordt onderbroken, kunt u hier een numerieke offset invoeren om door te gaan met een bepaalde ontvanger. U kunt controleren hoeveel mails er zijn verzonden in het <em>systeem/logs/newsletter_*. log</em> bestand. Bijv. als er 120 mails zijn verzonden, voer dan "120" in om door te gaan met de 121e ontvanger (telling begint bij 0).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Stuur preview naar';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Stuur de preview van een nieuwsbrief naar dit e-mailadres.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titel en onderwerp';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML inhoud';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Platte tekst inhoud';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Bijlagen';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Template instellingen';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Expert instellingen';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Verstuurd';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Verstuurd op %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Nog niet verstuurd';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Postdatum';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'De nieuwsbrief is verstuurd naar %s abonnees.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s ongeldige e-mail adressen zijn geblokkeerd (zie systeemlog).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Er zijn geen abonnees op dit nieuwsbriefkanaal.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Van';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Bijlagen';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Verstuur preview';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Wilt u de nieuwsbrief werkelijk versturen?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nieuwe nieuwsbrief';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Maak een nieuwe nieuwsbrief';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Nieuwsbrief details';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Toon details van nieuwsbrief ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Bewerk nieuwsbrief';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Bewerk nieuwsbrief ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Kopieer nieuwsbrief';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Kopieer nieuwsbrief ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Verplaats nieuwsbrief';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Verplaats nieuwsbrief ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Verwijder nieuwsbrief';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Verwijder nieuwsbrief ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Bewerk kanaal';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Bewerk de instellingen van dit nieuwsbriefkanaal';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Plak in dit kanaal';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Plak na nieuwsbriefkanaal ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Verstuur nieuwsbrief';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Verstuur nieuwsbrief ID %s';
