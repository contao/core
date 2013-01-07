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

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Strumento di installazione di Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Login strumento di installazione';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Lo strumento di installazione è stato bloccato';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Per ragioni di sicurezza lo strumento di installazione è stato bloccato. Questo succede se viene sbagliata più volte la password di installazione. Per sbloccare il sistema occorre aprire il file di configurazione e impostare la variabile <em>installCount</em> a <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Password';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Inserire la password per lo strumento di installazione. Ricorda che la password non è la stessa del pannello di controllo di Contao.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Password strumento di installazione';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Per aggiungere sicurezza a questo script, è possibile inserire <strong>un\'interruzione</strong> all\'interno del file <strong>contao/install.php</strong> oppure rimuovere completamente il file. In questo caso per modificare le impostazioni del sistema, occorre editare manualmente il file di configurazione.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Genera una chiave cifrata';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Questa chiave è usata per memorizzare i dati cifrati. Si ricorda che i dati cifrati possono essere decifrati solo con questa chiave! Pertanto non variare questa chiave se vi sono già dei dati cifrati memorizzati. Lasciando il campo in bianco verrà generata una chiave casuale.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Verifica connessione al database';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Inserire i parametri per la connessione al database.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Set dei caratteri';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Per maggiori informazioni consultare <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">il manuale MySQL </a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Aggiorna le tabelle del database';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Questo script per l\'aggiornamento delle tabelle è statao testato solo su RDBMS MySQL e MySQLi. Se si sta utilizzando un differente database (e.s. Oracle), sarà necessario installare/aggiornare il database manualmente. In questo caso occorre posizionarsi all\'interno della cartella <strong>system/modules</strong> e cercare in tutte le sottocartelle i file chiamati <strong>dca/database.sql</strong>.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importa un template';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Selezionare un file <em>.sql</em> dalla cartella <em>templates</em> per l\'importazione di un sito web di prova. Eventuali dati presenti saranno cancellati!!! Se invece si vuole importare un tema, utilizzare l\'apposita funzione nel backend.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Crea un utente amministratore';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Se hai importato il sito web di esempio, l\'utente amministratore sarà <strong>k.jones</strong> e la sua password è <strong>kevinjones</strong>. Consulta il sito di esempio per avere maggiori informazioni.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Contao è stato configurato correttamente!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Accedi al <a href="contao/index.php">pannello di controllo di Contao </a> e verifica tutte le impostazioni del sistema. Successivamente accedi al sito web per assicurarti che tutto funzioni correttamente.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modifica file in modalità FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Il server non supporta l\'accesso ai file tramite PHP; Molto probabilmente PHP è eseguito come modulo di Apache con un utente differente. Si prega di inserire i dati di login FTP, in modo che Contao possa modificare i file via FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Accetta la licenza';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao back end login';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Inserire una password per evitare accessi non autorizzati!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'La password inserita è stata memorizzata.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Salva la password';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Crea una chiave cifrata!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'La chiave cifrata deve essere lunga almeno 12 caratteri!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'La chiave cifrata è stata creata correttamente.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Genera una chiave cifrata!';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Genera o memorizza una chiave cifrata';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Connessione al database eseguita correttamente.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Non è possibile effettuare la connessione al database!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Driver';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Username';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Database';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Connessioni persistenti';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Set dei caratteri';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Collation';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Numero porta';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Salva le impostazioni del database';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Cambiare le regole di confronto modifichera tutte le tabelle con un prefisso <em> tl_</em>';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Il database non è aggiornato!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Il database è aggiornato.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Aggiorna il database';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Modifica la codifica caratteri';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Sembra che si stia aggiornando una versione di Contao precedente versione %s. Se si tratta di questo caso, <strong> è necessario eseguire l\'aggiornamento alla versione %s </ strong> per assicurare l\'integrità dei dati!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Esegui aggiornamento versione %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Esecuzione  aggiornamento versione %s - step %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'L\'importazione non è riuscita! E\' la struttura della banca dati aggiornata ed è il file della template compatibile con la tua versione Contao?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Selezionare un file template';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Template importato il %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'ATTENZIONE, ogni informazione esistente sarà cancellata!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Templates';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Non troncare le tabelle';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importa templates';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Ogni informazione esistente sarà cancellata. Vuoi procedere?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Indicare tutte le informazioni per creare un utente amministratore';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'L\'utente amministratore è stato creato.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Crea un utente amministratore';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'L\'installazione di Contao è avvenuta con successo';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP hostname';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Path relativo della cartella di Contao (es. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'Utente FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'Password FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Connessione sicura';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Connessione tramite FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'Porta FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Impostazioni FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Impossibile connettersi al server FTP %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Impossibile effettuare il login come "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Impossibile trovare la cartella %s di Contao';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'La cartella configurata come cartella file non esiste!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Hai rinominato la cartella <strong>tl_files</strong> in <strong>files</strong>? Non si può semplicemente rinominare la cartella, perchè tutti i riferimenti ai file presenti nel database ed i tuoi fogli di stile continueranno a puntare alla destinazione precedente. Se si desidera rinominare la cartella, si prega di farlo dopo l\'aggiornamento alla versione 3 ed aggiornare la banca dati usando il seguente script: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Crea nuove tabelle';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Aggiungi nuove colonne';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Modifica colonne esistenti';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Elimina colonne esistenti';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Elimina tabelle esistenti';
