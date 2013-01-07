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
 * @link https://www.transifex.com/projects/p/contao/language/pl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Temat';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Wpisz temat newlettera.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Alias tego newslettera';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Alias tego newslettera jest unikalnym odwołaniem do tego newslettera, który może być używany zamiast jego ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'Treść';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Wprowadź treść newslettera. Użyj tagu <em>##email##</em> do umieszczenia adresu e-mail odbiorcy. Link do anulowania subskrypcji <em>http://www.domena.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Treść';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Wprowadź treść newslettera. Użyj tagu <em>##email##</em> do umieszczenia adresu e-mail odbiorcy. Link do anulowania subskrypcji <em>http://www.domena.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Dodaj załącznik';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Dodaj jeden lub więcej załączników';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Załączniki';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Wybierz pliki, które chcesz dołączyć do newslettera.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Szablon e-mail';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Wybierz szablon e-mail (grupa szablonów <em>mail_</em>).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Wyślij jako tekst';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Jeśli wybierzesz tę opcję, e-mail zostanie wysłany jako zwykły tekst. Treść zostanie pozbawiona wszystkich tagów HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Zewnętrzne obrazki';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Nie osadzaj obrazków w newsletterach HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Nazwa nadawcy';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Tutaj możesz wpisać nazwę nadawcy.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Adres nadawcy';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Jeśli nie wpiszesz adresu nadawcy, użyty zostanie adres administratora.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Maili w cyklu';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'By uniemożliwić skryptowi przekroczenia dozwolonego czasu wykonania, proces wysyłania został podzielony na kilka cykli. Tutaj możesz zdefiniować ilość wysyłanych maili w ciągu jednego cyklu, jest to uzależnione od zdefiniowanego w pliku php.ini maksymalnego czasu wykonywania jednego skryptu.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Przerwa w sekundach';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Niektóre mail serwery mają limit maili, które mogą wysłać w ciągu jednej minuty. Tutaj możesz ustawić przerwę w sekundach pomiędzy każdym cyklem w celu większej kontroli operacji w ramach danego czasu.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Offset';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'W przypadku kiedy proces wysyłania został przerwany, możesz wpisać ilość wysłanych maili aby kontynuować od danego odbiorcy. Możesz sprawdzić ile maili zostało wysłanych w pliku <em>system/logs/newsletter_*.log</em>. Np. jeśli zostało wysłanych 120 maili, wpisz "120" aby kontynuować wysyłanie od 121-ego odbiorcy (liczenie rozpoczyna się od 0).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Wyślij podgląd do';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Wyślij podgląd newslettera na ten adres email.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Tytuł i temat';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'Zawartość HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Zawartość tekstowa';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Załączniki';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Ustawienia szablonu';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Ustawienia zaawansowane';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Wysłany';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Wysłano %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Jeszcze nie wysłany';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Data wysyłki';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Newsletter został wysłany do %s odbiorców.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s błędny/e adres/y e-mail zostały wyłączone.';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Nie ma żadnych aktywnych subskrybentów tego kanału.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Od';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Załączniki';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Wyślij podgląd';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Czy na pewno chcesz wysłać newsletter?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nowy newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Utwórz nowy newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Szczegóły newslettera';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Pokaż szczegóły newslettera ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Edytuj newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Edytuj newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Kopiuj newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Kopiuj newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Przenieś newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Przenieś newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Skasuj newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Skasuj newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Edytuj kanał';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Edytuj kanał';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Wklej do tego kanału';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Wklej po newsletterze ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Wyślij newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Wyślij newsletter ID %s';
