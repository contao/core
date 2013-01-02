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

$GLOBALS['TL_LANG']['tl_page']['title'][0] = 'Nume pagină';
$GLOBALS['TL_LANG']['tl_page']['title'][1] = 'Numele unei pagini este afişat în meniul de navigare al sitului. Este limitat la 65 caractere.';
$GLOBALS['TL_LANG']['tl_page']['alias'][0] = 'Alias pagină';
$GLOBALS['TL_LANG']['tl_page']['alias'][1] = 'Page alias este o referinţă către pagina care poate fi apelată în locul ID-ului. Este utilă mai ales dacă Contao foloseşte URL-uri statice.';
$GLOBALS['TL_LANG']['tl_page']['type'][0] = 'Tip pagină';
$GLOBALS['TL_LANG']['tl_page']['type'][1] = 'Selectaţi tipul paginii în funcţie de scopul acesteia.';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][0] = 'Titlu pagină';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][1] = 'Titlul paginii este afişat în tag-ul TITLE al sitului dumneavoastră şi în rezultatele căutării. Nu trebuie să conţină mai mult de 65 caractere. Dacă lăsaţi acest câmp necompletat, numele paginii va fi folosit ca titlu.';
$GLOBALS['TL_LANG']['tl_page']['language'][0] = 'Limba';
$GLOBALS['TL_LANG']['tl_page']['robots'][0] = 'Motoare de căutare';
$GLOBALS['TL_LANG']['tl_page']['robots'][1] = 'Definiţi cum abordează motoarele de căutare pagina.';
$GLOBALS['TL_LANG']['tl_page']['description'][0] = 'Descrierea paginii';
$GLOBALS['TL_LANG']['tl_page']['description'][1] = 'Puteţi introduce o scurtă descriere a paginii, va fi afişată de motoarele de căutare. Un motor de căutare arată de obicei între 150 şi 300 caractere.';
$GLOBALS['TL_LANG']['tl_page']['redirect'][0] = 'Tip de redirectare';
$GLOBALS['TL_LANG']['tl_page']['redirect'][1] = 'Redirectările temporare vor trimite un header HTTP 302, cele permanente vor trimite un header HTTP 301.';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][0] = 'Avansează la';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][1] = 'Alegeţi pagina ţintă din structura de pagini.';
$GLOBALS['TL_LANG']['tl_page']['fallback'][0] = 'Limba preferată';
$GLOBALS['TL_LANG']['tl_page']['fallback'][1] = 'Contao redirectează automat un vizitator către o rădăcină de website în limba sa sau către limba nativă. Dacă nu există o pagină cu limba nativă, este afişat mesajul de eroare <em>Nu am găsit nicio pagină</em>.';
$GLOBALS['TL_LANG']['tl_page']['dns'][0] = 'Nume domeniu';
$GLOBALS['TL_LANG']['tl_page']['dns'][1] = 'Dacă desemnaţi un nume de domeniu unei pagini rădăcină de website, vizitatorii vor fi redirectaţi către acest website când vor introduce numele corespunzător de domeniu (ex. <em>domeniultau.com</em> sau <em>subdomeniu.domeniultau.com</em>).';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][0] = 'Email webmaster';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][1] = 'Adresa de email a administratorului sitului va fi folosită ca adresă de trimitere pentru mesaje autogenerate ca mesajele de activare sau cele de confirmare a subscrierii.';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][0] = 'Format de dată';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][1] = 'Introduceţi un format de dată pentru funcţia PHP date().';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][0] = 'Format de timp';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][1] = 'Introduceţi un format de oră pentru funcţia PHP date().';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][0] = 'Format de dată şi timp';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][1] = 'Introduceţi un format de dată şi oră pentru funcţia PHP date().';
$GLOBALS['TL_LANG']['tl_page']['createSitemap'][0] = 'Creaţi un sitemap XML';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][0] = 'Nume fişier';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][1] = 'Introduceţi un nume prentru fişierul XML (nu includeţi extensia).';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][0] = 'Foloseşte HTTPS în sitemaps';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][1] = 'Generează un sitemap cu URL-uri cu <em>https://</em>.';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][0] = 'Avansează la o altă pagină';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][1] = 'Dacă folosiţi această opţiune, vizitatorii vor fi trimişi către o altă pagina (ex. o pagină de autentificare sau una de bun venit).';
$GLOBALS['TL_LANG']['tl_page']['protected'][0] = 'Protejează pagina';
$GLOBALS['TL_LANG']['tl_page']['protected'][1] = 'Dacă alegeţi această opţiune puteţi restricţiona accesul către pagină doar pentru anumite grupuri de membri.';
$GLOBALS['TL_LANG']['tl_page']['groups'][0] = 'Grupuri cu acces';
$GLOBALS['TL_LANG']['tl_page']['groups'][1] = 'Aici puteţi acorda accesul unuia sau mai multor grupuri de utilizatori. Dacă nu alegeţi un grup orice utilizator logat în front-end va putea accesa această pagină.';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][0] = 'Desemnează un ansamblu';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][1] = 'Iniţial o pagină foloseşte acelaşi ansamblu ca şi pagina părinte. Alegând această opţiune puteţi desemna un ansamblu diferit paginii şi subpaginilor sale.';
$GLOBALS['TL_LANG']['tl_page']['layout'][0] = 'Ansamblu';
$GLOBALS['TL_LANG']['tl_page']['layout'][1] = 'Alegeţi un ansamblu de pagină. Puteţi edita sau crea ansambluri folosind modulul <em>Ansambluri</em>.';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][0] = 'Ansamblu de pagină pentru portabile';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][1] = 'Acest ansamblu de pagină se va folosi dacă utilizatorul accesează de pe un portabil';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][0] = 'Desemnează expirare cache';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][1] = 'Nativ o pagină foloseşte aceeaşi valoare ca şi pagina părinte. Dacă alegeţi aceasta opţiune puteţi desemna o valoare diferită pentru pagina curentă şi subpaginile sale.';
$GLOBALS['TL_LANG']['tl_page']['cache'][0] = 'Expirare cache';
$GLOBALS['TL_LANG']['tl_page']['cache'][1] = 'În timpul perioadei cache timeout, o pagină va fi încărcată din tabela de cache. Acest lucru va scurta timpul de încărcare al unui site.';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][0] = 'Desemnează permisiuni';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][1] = 'Permisiunile vă dau posibilitatea să definiţi în ce măsură un utilizator back-end poate modifica o pagină şi articolele acesteia. Dacă nu alegeţi această opţiune, pagina va folosi aceleaşi permisiuni ca şi pagina părinte.';
$GLOBALS['TL_LANG']['tl_page']['cuser'][0] = 'Proprietar';
$GLOBALS['TL_LANG']['tl_page']['cuser'][1] = 'Alegeţi un utilizator ca proprietar al paginii curente.';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][0] = 'Grup';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][1] = 'Alegeţi un grup ca proprietar al paginii curente.';
$GLOBALS['TL_LANG']['tl_page']['chmod'][0] = 'Permisiuni';
$GLOBALS['TL_LANG']['tl_page']['chmod'][1] = 'Fiecare pagină are trei nivele de acces: una pentru utilizator, una pentru grup şi una pentru toţi ceilalţi. Puteţi acorda permisiuni diferite fiecărui nivel.';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][0] = 'Nu căuta în această pagină';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][1] = 'Alegând aceasta opţiune pagina curentă va fi exclusă din operaţiunile de căutare din cadrul sitului.';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][0] = 'Clasă CSS';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][1] = 'Dacă introduceţi un nume de clasă aici, va fi folosit ca atribut al clasei în meniul de navigare. Puteţi astfel formata elementele navigării individual.';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][0] = 'Include în sitemap';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][1] = 'Puteţi defini dacă pagina este inclusă în sitemap.';
$GLOBALS['TL_LANG']['tl_page']['hide'][0] = 'Ascunde pagina în meniul de navigare';
$GLOBALS['TL_LANG']['tl_page']['hide'][1] = 'Dacă alegeţi această opţiune, pagina curentă nu va apare în meniu.';
$GLOBALS['TL_LANG']['tl_page']['guests'][0] = 'Arată doar vizitatorilor';
$GLOBALS['TL_LANG']['tl_page']['guests'][1] = 'Ascunde pagina în meniul de navigare dacă un membru este logat.';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][0] = 'Navigare cu TAB';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][1] = 'Acest număr specifică poziţia elementului de navigare curent în ordinea TAB. Puteţi introduce un număr între 1 şi 32767.';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][0] = 'Caracter de acces';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][1] = 'Un caracter de acces este un caracter care poate fi desemnat unui element de navigaţie. Dacă un vizitator apasă simultan [ALT] şi caracterul respectiv, elementul de navigare este activat.';
$GLOBALS['TL_LANG']['tl_page']['published'][0] = 'Publicat';
$GLOBALS['TL_LANG']['tl_page']['published'][1] = 'Dacă nu alegeţi această opţiune pagina nu va fi vizibilă vizitatorilor.';
$GLOBALS['TL_LANG']['tl_page']['start'][0] = 'Publică de la';
$GLOBALS['TL_LANG']['tl_page']['start'][1] = 'Dacă introduceţi o dată pagina curentă va fi vizibilă începând cu acea dată.';
$GLOBALS['TL_LANG']['tl_page']['stop'][0] = 'Publică până la';
$GLOBALS['TL_LANG']['tl_page']['stop'][1] = 'Dacă introduceţi o dată pagina curentă va fi vizibilă până la acea dată.';
$GLOBALS['TL_LANG']['tl_page']['title_legend'] = 'Nume şi tip';
$GLOBALS['TL_LANG']['tl_page']['meta_legend'] = 'Metainformaţie';
$GLOBALS['TL_LANG']['tl_page']['system_legend'] = 'Setări de sistem';
$GLOBALS['TL_LANG']['tl_page']['redirect_legend'] = 'Setări de redirectare';
$GLOBALS['TL_LANG']['tl_page']['dns_legend'] = 'Setări DNS';
$GLOBALS['TL_LANG']['tl_page']['global_legend'] = 'Setări globale';
$GLOBALS['TL_LANG']['tl_page']['mobile_legend'] = 'Setări pentru portabile';
$GLOBALS['TL_LANG']['tl_page']['sitemap_legend'] = 'Sitemap XML';
$GLOBALS['TL_LANG']['tl_page']['forward_legend'] = 'Autocontinuare';
$GLOBALS['TL_LANG']['tl_page']['protected_legend'] = 'Restricţionare acces';
$GLOBALS['TL_LANG']['tl_page']['layout_legend'] = 'Setări de aranjare';
$GLOBALS['TL_LANG']['tl_page']['cache_legend'] = 'Setări de cache';
$GLOBALS['TL_LANG']['tl_page']['chmod_legend'] = 'Drepturi de acces';
$GLOBALS['TL_LANG']['tl_page']['search_legend'] = 'Setări de căutare';
$GLOBALS['TL_LANG']['tl_page']['expert_legend'] = 'Setări avansate';
$GLOBALS['TL_LANG']['tl_page']['tabnav_legend'] = 'Navigare prin tastatură';
$GLOBALS['TL_LANG']['tl_page']['publish_legend'] = 'Publicare';
$GLOBALS['TL_LANG']['tl_page']['permanent'] = 'Redirectare permanentă 301';
$GLOBALS['TL_LANG']['tl_page']['temporary'] = 'Redirectare temporară 302';
$GLOBALS['TL_LANG']['tl_page']['map_default'] = 'Iniţial';
$GLOBALS['TL_LANG']['tl_page']['map_always'] = 'Arată mereu';
$GLOBALS['TL_LANG']['tl_page']['map_never'] = 'Nu arăta';
$GLOBALS['TL_LANG']['tl_page']['new'][0] = 'Pagină nouă';
$GLOBALS['TL_LANG']['tl_page']['new'][1] = 'Creează o pagină nouă';
$GLOBALS['TL_LANG']['tl_page']['show'][0] = 'Detalii pagină';
$GLOBALS['TL_LANG']['tl_page']['show'][1] = 'Arată detaliile paginii ID %s';
$GLOBALS['TL_LANG']['tl_page']['edit'][0] = 'Editează pagina';
$GLOBALS['TL_LANG']['tl_page']['edit'][1] = 'Editează pagina ID %s';
$GLOBALS['TL_LANG']['tl_page']['cut'][0] = 'Mută pagină';
$GLOBALS['TL_LANG']['tl_page']['cut'][1] = 'Mută pagina ID %s';
$GLOBALS['TL_LANG']['tl_page']['copy'][0] = 'Copiază pagină';
$GLOBALS['TL_LANG']['tl_page']['copy'][1] = 'Copiază pagina ID %s';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][0] = 'Copiază pagină cu subpagini';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][1] = 'Copiază pagina ID %s inclusiv subpaginile';
$GLOBALS['TL_LANG']['tl_page']['delete'][0] = 'Şterge pagină';
$GLOBALS['TL_LANG']['tl_page']['delete'][1] = 'Şterge pagina ID %s';
$GLOBALS['TL_LANG']['tl_page']['toggle'][0] = 'Publică pagină';
$GLOBALS['TL_LANG']['tl_page']['toggle'][1] = 'Publică pagina ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][0] = 'Lipeşte după';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][1] = 'Lipeşte după pagina ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][0] = 'Lipeşte în';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][1] = 'Lipeşte în pagina ID %s';
$GLOBALS['TL_LANG']['tl_page']['articles'][0] = 'Editează articolele';
$GLOBALS['TL_LANG']['tl_page']['articles'][1] = 'Editează articolele din pagina ID %s';
$GLOBALS['TL_LANG']['CACHE'][0] = '0 secunde (fără cache)';
$GLOBALS['TL_LANG']['CACHE'][5] = '5 secunde';
$GLOBALS['TL_LANG']['CACHE'][15] = '15 secunde';
$GLOBALS['TL_LANG']['CACHE'][30] = '30 secunde';
$GLOBALS['TL_LANG']['CACHE'][60] = '1 minut';
$GLOBALS['TL_LANG']['CACHE'][300] = '5 minute';
$GLOBALS['TL_LANG']['CACHE'][900] = '15 minute';
$GLOBALS['TL_LANG']['CACHE'][1800] = '20 minute';
$GLOBALS['TL_LANG']['CACHE'][3600] = '1 oră';
$GLOBALS['TL_LANG']['CACHE'][10800] = '3 ore';
$GLOBALS['TL_LANG']['CACHE'][21600] = '6 ore';
$GLOBALS['TL_LANG']['CACHE'][43200] = '12 ore';
$GLOBALS['TL_LANG']['CACHE'][86400] = '1 zi';
$GLOBALS['TL_LANG']['CACHE'][259200] = '3 zile';
$GLOBALS['TL_LANG']['CACHE'][604800] = '7 zile';
$GLOBALS['TL_LANG']['CACHE'][2592000] = '30 zile';
