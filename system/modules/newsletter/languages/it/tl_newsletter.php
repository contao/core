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
 * @link https://www.transifex.com/projects/p/contao/language/it/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Oggetto';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Inserisci un oggetto per la newsletter.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Alias newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'L\'alias è un riferimento univoco alla newsletter che può essere utilizzato per richiamare la newsletter al posto dell\'ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'Contenuto in formato HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Inserisci un contenuto in formato HTML per la newsletter. Puoi usare il carattere sostitutivi<em>##email##</em> per inserire l\'indirizzo di e-mail del destinatario.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Contenuto in formato testo';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Inserisci un contenuto in formato testo per la newsletter. Puoi usare i caratteri sostitutivi<em>##email##</em> per inserire l\'indirizzo e-mail del destinatario.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Aggiungi allegati';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Allega uno o più files alla newsletter.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Allegati';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Seleziona i file che vuoi allegare alla newsletter.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Template E-mail';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Qui puoi scegliere una template e-mail.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Invia come testo';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Invia la newsletter in formato testo, senza contenuto HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Immagini esterne';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Non inserire immagini nelle newsletter HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Nome mittente';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Qui puoi specificare il nome del mittente.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Indirizzo mittente';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Qui puoi introdurre un indirizzo de mail personalizzato.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Mail per ciclo';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Il processo di invio è suddiviso in vari cicli per prevenire che lo script scada.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Timeout in secondi';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Qui puoi modificare il tempo di attesa tra due cicli per controllare il numero di e-mail al minuto.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Offset';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'Nel caso in cui il processo di invio venga interrotto, puoi inserire il numero di invii rimanenti da eseguire indicando da quale destinatario riprendere.E\' possibile verificare quante e-mail sono state inviate nel file <em>system/logs/newsletter_*.log</em>. Ad esempio, se sono stare inviate 120 e-mail, digitando 120 l\'applicazione riprenderà dall\'email 121.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Invia anteprima a';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Invia un\'anteprima della newsletter a questo indirizzo.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titolo e argomento';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'Contenuto HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Contenuto testo';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Allegati';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Impostazioni template';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Impostazioni avanzate';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Inviata';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Inviata il %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Non ancora inviata';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Data invio';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'La newsletter è stata inviata a %s iscritti.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s gli indirizzi e-mail non validi sono stati disabilitati.';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Non ci sono iscritti attivi a questa newsletter.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Da';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Allegati';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Invia anteprima';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Sei sicuro di voler inviare la newsletter?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nuova newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Crea una nuova newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Dettaglio newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Mostra i dettagli della newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Modifica newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Modifica la newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Copia newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Copia la newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Sposta newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Sposta newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Cancella newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Cancella la newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Modifica newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Modifica impostazioni newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Incolla in questa newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Incolla dopo la newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Invia newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Invia newsletter ID %s';
