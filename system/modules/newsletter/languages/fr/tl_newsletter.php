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
 * @link https://www.transifex.com/projects/p/contao/language/fr/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Sujet';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Saisir le titre du bulletin d\'information.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Alias du bulletin d\'information';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'L\'alias du bulletin d\'information est une référence unique qui remplace l\'ID du bulletin d\'information.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'Contenu HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Saisir le contenu HTML du bulletin d\'information. Utiliser le joker <em>##email##</em> pour insérer l\'adresse e-mail du destinataire.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Contenu texte';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Saisir le contenu texte du bulletin d\'information. Utiliser le joker <em>##email##</em> pour insérer l\'adresse e-mail du destinataire.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Ajout de fichier joint';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Attacher un ou plusieurs fichiers avec le bulletin d\'information.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Fichiers joints';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Choisir les fichiers à attacher au bulletin d\'information.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Modèle d\'e-mail';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Choisir un modèle d\'e-mail.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Envoyer comme texte brut';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Envoyer le bulletin d\'information en texte brut, sans le contenu HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Images externes';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Ne pas inclure des images dans les bulletins HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Nom de l\'expéditeur';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Saisir le nom de l\'expéditeur.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Adresse de l\'expéditeur';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Saisir l\'adresse e-mail de l\'expéditeur.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Nombre d\'e-mails par cycle';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Afin de protéger le script de coupures intempestives, le processus d\'envoi est décomposé en plusieurs cycles.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Délai en secondes';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Modifier le délai entre chaque cycle d\'envoi, afin de contrôler le nombre d\'e-mails envoyés à la minute.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Décalage';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'Dans le cas où le processus d\'envoi est interrompu, saisir une valeur numérique de décalage pour continuer avec un destinataire particulier. Vous pouvez vérifier le nombre de mails qui ont été envoyés dans le fichier <em>/system/logs/log newsletter_*.log</em>. Par exemple, si 120 mails ont été envoyés, saisir "120" pour continuer avec le 121 ème destinataire (le compteur commançant à 0).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Envoyer un aperçu';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Envoyer un aperçu du bulletin d\'information à cette adresse e-mail.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titre et sujet';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'Contenu HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Contenu texte';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Fichiers attachés';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Paramètres des modèles';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Envoyé';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Envoyé le %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Pas encore envoyé';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Date du mailing';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Le bulletin d\'information a été envoyé à %s destinataires.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s adresse(s) e-mail(s) non valide(s) a/ont été désactivée(s).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Il n\'y a pas d\'abonnés actifs dans cette liste de diffusion.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'De';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Fichiers joints';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Envoyer un aperçu';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Voulez-vous vraiment envoyer le bulletin d\'information ?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nouveau bulletin';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Créer un nouveau bulletin d\'information.';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Détails du bulletin';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Afficher les détails du bulletin d\'information ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Éditer le bulletin';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Éditer le bulletin d\'information ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Dupliquer le bulletin ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Dupliquer le bulletin d\'information ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Déplacer le bulletin';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Déplacer le bulletin d\'information ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Supprimer le bulletin ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Supprimer le bulletin d\'information ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Éditer la liste de diffusion';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Éditer les paramètres de la liste de diffusion';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Coller dans cette liste de diffusion';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Coller après le bulletin ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Envoyer le bulletin';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Envoyer le bulletin d\'information ID %s';
