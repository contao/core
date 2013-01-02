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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Nettoyer les données';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Choisir les données à purger.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Membre';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Se connecter automatiquement en tant que membre pour indexer les pages protégées.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Travail';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Description';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Nettoyer les données';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Les données ont été nettoyées';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Mise à jour automatique';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'ID de mise à jour automatique (Live Update ID)';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Aller au Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Votre version %s de Contao est à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Une nouvelle version %s de Contao est disponible';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Saisir l\'ID de mise à jour automatique';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Le répertoire temporaire (system/tmp) ne possède pas de droits en écriture';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Voir le journal des modifications';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Lancer la mise à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Contenu de la mise à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Fichiers sauvegardés';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Fichiers mis à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Recréer l\'index de recherche';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Recréer l\'index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Il n\'y a aucune page pouvant être recherchée';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Veuillez attendre le chargement complet de cette page avant de continuer !';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Veuillez patienter pendant que l\'index de recherche est en cours de création.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'L\'index de recherche a été recréé. Vous pouvez maintenant continuer.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Veuillez saisir votre %s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Purger l\'index de recherche';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Vide les tables <em>tl_search</em> et <em>tl_search_index</em>. Ensuite, reconstruire l\'index de recherche (voir ci-dessus).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Purger la table des annulations';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Vide la table <em>tl_undo</em> qui stocke les enregistrements supprimés. Ce travail supprime de façon permanente ces enregistrements.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Purger la table des versions';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Vide la table <em>tl_version</em> qui stocke les précédentes versions d\'un enregistrement. Ce travail supprime de façon permanente ces enregistrements.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Purger le cache des images';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Supprime les images générées automatiquement et de ce fait purge le cache des pages, ainsi il n\'y a plus de liens vers les ressources supprimées.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Purger le cache des scripts';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Supprime les fichiers .css et .js générés automatiquement, recrée la feuille de style interne et purge le cache des pages.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Purger le cache des pages';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Supprime les versions en cache des pages front office.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Purger le cache interne';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Supprime les versions en cache du DCA et des fichiers de langue. Il est possible de désactiver de manière permanente le cache interne dans les paramètres du back office.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Purger le répertoire temporaire';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Supprime les fichiers temporaires.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Recréer les fichiers XML';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Recrée les fichiers XML (carte du site et flux RSS) puis purge le cache des pages afin de ne plus avoir de liens vers des ressources supprimées.';
