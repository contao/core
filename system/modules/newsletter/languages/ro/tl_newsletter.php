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
 * @link https://www.transifex.com/projects/p/contao/language/ro/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Subiect';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Introduceţi subiectul newsletter-ului.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Alias la newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Un alias la newsletter este o referinţă unică la newsletter ce se poate folosi în loc de ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'Conţinut HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Introduceţi conţinutul. Folosiţi "wildcard-ul" <em>##email##</em> pentru a introduce adresa email a destinatarului. Generaţi link-uri de dezabonare astfel <em>http://www.domeniu.ro/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Conţinut text';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Introduceţi conţinutul. Folosiţi "wildcard-ul" <em>##email##</em> pentru a introduce adresa email a destinatarului. Generaţi link-uri de dezabonare astfel <em>http://www.domeniu.ro/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Adaugă ataşament';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Ataşează unul sau mai multe fişiere la newsletter.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Ataşamente';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Alegeţi fişierele care doriţi să fie ataşate la newsletter.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Şablon de email';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Alegeţi un şablon de email (grup <em>mail_</em>).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Trimite ca text';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Dacă alegeţi această opţiune, emailul va fi trimis ca text şi toate tag-urile HTML vor fi şterse.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Imagini externe';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Nu include imaginile în newslettere HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Nume expeditor';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Aici puteţi introduce numele expeditorului.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Adresă expeditor';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Dacă nu introduceţi o adresă de expeditor va fi folosită cea a administratorului.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Mesaje per ciclu';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Pentru a evita expirarea timpului maxim de execuţie, procesul de trimitere este împărţit în mai multe cicluri. Aici puteţi defini numărul de mesaje per ciclu în funcţie de timpul maxim de execuţie definit în php.ini.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Interval în secunde';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Unele servere de email limitează numărul de mesaje care pot fi trimise pe minut. Aici puteţi alege o valoare pentru pauza în secunde dintre ciclurile de expediere în scopul de a controla mai bine intervalul de timp în care se face trimiterea.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Ciclu de pornire';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'În cazul în care procesul de trimitere este întrerupt, puteţi introduce numărul ciclului de repornire aic pentru a continua cu un anumit destinatar. Se poate verifica rapid câte mesaje au fost trimise în fişierul <em>system/logs/newsletter_*.log</em> De exemplu dacă 120 mesaje au fost trimise introduceţi 120 pentru a continua cu destinatarul 121 (numărarea porneşte de la 0 pentru primul ciclu).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Trimite test';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Trimite un test al newsletter-ului la această adresă de email.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titlu şi subiect';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'Conţinut HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Conţinut Text';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Ataşamente';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Setări de şablon';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Setări de';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Trimis';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Trimis pe %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Netrimis încă';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Data expedierii';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Newsletter trimis către %s destinatari.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s adresele de email invalide au fost dezactivate (verificaţi jurnalul de sistem)';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Nu sunt abonaţi activi pe acest canal.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'De la';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Ataşamente';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Previzualizează';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Doriţi într-adevăr să trimiteţi newsletter-ul?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Newsletter nou';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Creează un newsletter nou';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Detalii newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Arată detaliile newsletter-ului ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Editează newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Editează newsletter-ul ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Copiază newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Copiază newsletter-ul ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Mută newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Mută newsletter-ul ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Şterge newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Şterge newsletter-ul ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Editează canal';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Editează canalul';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Adaugă la acest canal';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Adaugă după newsletter-ul ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Trimite newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Trimite newsletter-ul ID %s';
