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

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Outil d\'installation de Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Connexion à l\'outil d\'installation';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'L\'outil d\'installation a été verrouillé';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Pour des raisons de sécurité, l\'outil d\'installation a été verrouillé. Ceci arrive si vous entrez un mauvais mot de passe plus de trois fois d\'affilée. Veuillez éditer le fichier de configuration locale et définissez <em>installCount</em> à <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Mot de passe';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Veuillez saisir le mot de passe de l\'outil d\'installation. Le mot de passe de l\'outil d\'installation n\'est pas le même que le mot de passe du back office de Contao.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Mot de passe de l\'outil d\'installation';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'De plus, si vous voulez sécuriser l\'outil d\'installation de Contao, vous pouvez renommer ou supprimer complètement le fichier <strong>contao/install.php</strong>';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Générer une clé de cryptage';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Cette clé est utilisée pour stocker des données cryptées. Veuillez noter que les données cryptées peuvent être décryptées SEULEMENT avec cette clé ! Ne la changez pas s\'il existe déjà des données cryptées. Laisser vide pour générer une clé aléatoire.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Vérifier la connexion à la base de données';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Saisissez vos paramètres de connexion à la base de données.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Classement';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Pour de plus amples renseignements, consultez le <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" target="_blank">manuel MySQL</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Mettre à jour les tables de la base de données';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Veuillez noter que cet assistant de mise à jour a uniquement été testé avec les bases de données MySQL et MySQLi. Si vous utilisez une autre base de données (Oracle par exemple), vous pourriez avoir à installer/mettre à jour votre base de données manuellement. Dans ce cas, allez dans le répertoire <strong>system/modules</strong> et cherchez dans tous ses sous-dossiers pour trouver les fichiers appelés <strong>dca/database.sql</strong>.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importer un modèle';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Vous pouvez importer un fichier <em>.sql</em> contenant par exemple un site web pré-configuré. Ce fichier devra se situer dans le répertoire <em>templates</em>. Les données existantes seront effacées ! Si vous souhaitez seulement importer un thème, utilisez le gestionnaire de thème dans le back office de Contao.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Créer un utilisateur administrateur';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Si vous avez importé le site d\'exemple, l\'identifiant de l\'utilisateur administrateur sera <strong>k.jones</strong> et son mot de passe <strong>kevinjones</strong>. Consulter la page d\'accueil du site d\'exemple (front office) pour plus d\'information.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Félicitations !';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Maintenant, veuillez vous connecter au <a href="contao/">back office de Contao</a> et vérifiez tous les paramètres système. Puis, visitez votre site Web pour s\'assurer que Contao fonctionne correctement.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modifier les fichiers par FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Votre serveur ne supporte pas l\'accès aux fichiers via PHP. PHP fonctionne probablement avec le module Apache sous un utilisateur différent. Donc, veuillez saisir vos informations de connexion FTP de sorte Contao puisse modifier des fichiers via FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Accepter la licence';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Connexion au back office de Contao';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Veuillez saisir un mot de passe pour empêcher tout accès non autorisé !';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Le mot de passe personnalisé à été défini.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Enregistrer le mot de passe';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Veuillez créer une clé de cryptage !';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Une clé de cryptage doit être au minimum de 12 caractères de long !';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Une clé de cryptage a été créée.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Générer la clé de cryptage';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Générer ou enregistrer la clé';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Connexion à la base de données établie.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Non connecté à la base de données !';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Pilote';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Serveur';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Identifiant';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Base de données';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Connexion persistante';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Jeu de caractères';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Classement';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Numéro de port';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Enregistrer les paramètres de la base de données';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'La modification du classement aura une incidence sur toutes les tables avec un préfixe <em>tl_</em>';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'La base de données n\'est pas à jour !';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'La base de données est à jour.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Mettre à jour la base de données';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Changer le classement';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Il semble que vous mettez à niveau une version de Contao antérieure à la version %s. Si tel est le cas, il est <strong>nécessaire d\'exécuter la mise à jour vers la version %s</strong> pour assurer l\'intégrité de vos données !';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Exécuter la mise à jour vers la version %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Exécute la mise à jour de la version %s - étape %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'L\'importation a échoué ! La structure de la base de données est-elle à jour ? Est-ce que le modèle est compatible avec votre version de Contao ?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Veuillez choisir un fichier de modèle !';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Modèle importé le %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Toutes les données existantes seront supprimées !';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Modèles';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Ne pas vider les tables';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importer un modèle';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Toutes les données existantes seront supprimées ! Voulez-vous vraiment continuer ?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Veuillez remplir tous les champs pour créer un utilisateur administrateur !';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Un utilisateur administrateur a été créé.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Créer un compte administrateur';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Vous avez réussi à installer Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'Serveur FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Chemin d\'accès relatif au répertoire Contao (ex. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'Identifiant FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'Mot de passe FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Connexion sécurisée';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Connexion via FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'Port FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Enregistrer les paramètres FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Impossible de se connecter au serveur FTP %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Impossible de se connecter en tant que "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Impossible de localiser le répertoire de Contao %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Le chemin configuré du dossier n\'existe pas !';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Avez-vous renommé le dossier <strong>tl_files</strong> en <strong>files</strong> ? Vous ne pouvez pas simplement renommer le dossier car toutes les références à ce dernier dans la base de données et vos feuilles de style pointeront toujours sur l\'ancien nom. Si vous désirez renommer le dossier, procédez après la mise à jour vers la version 3 et assurez vous de corriger votre base de donnée avec le script suivant : %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Créer de nouvelles tables';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Ajouter de nouvelles colonnes';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Changer les colonnes existantes';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Supprimer les colonnes existantes';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Supprimer les tables existantes';
