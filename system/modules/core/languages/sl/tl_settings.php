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
 * @link https://www.transifex.com/projects/p/contao/language/sl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_settings']['websiteTitle'][0] = 'Naslov spletnega mesta';
$GLOBALS['TL_LANG']['tl_settings']['websiteTitle'][1] = 'Prosimo, vnesite naslov spletnega mesta.';
$GLOBALS['TL_LANG']['tl_settings']['adminEmail'][0] = 'Administratorjev naslov elektronske pošte';
$GLOBALS['TL_LANG']['tl_settings']['adminEmail'][1] = 'Samodejno ustvarjena sporočila, kot npr. potrditev naročila na obveščanje, bodo poslana na ta e-naslov.';
$GLOBALS['TL_LANG']['tl_settings']['dateFormat'][0] = 'Oblika zapisa datuma';
$GLOBALS['TL_LANG']['tl_settings']['dateFormat'][1] = 'Oblika zapisa datuma bo razčlenjena s pomočjo funkcije PHP date().';
$GLOBALS['TL_LANG']['tl_settings']['timeFormat'][0] = 'Oblika zapisa časa';
$GLOBALS['TL_LANG']['tl_settings']['timeFormat'][1] = 'Oblika zapisa časa bo razčlenjena s pomočjo funkcije PHP date().';
$GLOBALS['TL_LANG']['tl_settings']['datimFormat'][0] = 'Oblika zapisa datuma in časa';
$GLOBALS['TL_LANG']['tl_settings']['datimFormat'][1] = 'Oblika zapisa datuma in časa bo razčlenjena s pomočjo funkcije PHP date().';
$GLOBALS['TL_LANG']['tl_settings']['timeZone'][0] = 'Časovni pas';
$GLOBALS['TL_LANG']['tl_settings']['timeZone'][1] = 'Prosimo, izberite časovni pas strežnika.';
$GLOBALS['TL_LANG']['tl_settings']['websitePath'][0] = 'Relativna pot do Contao imenika.';
$GLOBALS['TL_LANG']['tl_settings']['websitePath'][1] = 'Relativno pot do Contao imenika praviloma samodejno nastavi orodje za namestitev.';
$GLOBALS['TL_LANG']['tl_settings']['characterSet'][0] = 'Nabor znakov.';
$GLOBALS['TL_LANG']['tl_settings']['characterSet'][1] = 'Priporočeno je, da uporabite UTF-8 nabor znakov, da bodo lahko posebni znaki pravilno prikazani.';
$GLOBALS['TL_LANG']['tl_settings']['customSections'][0] = 'Prilagojeni oddelki postavitve strani';
$GLOBALS['TL_LANG']['tl_settings']['customSections'][1] = 'Tu lahko vnesete z vejico ločene prilagojene oddelke postavitve strani.';
$GLOBALS['TL_LANG']['tl_settings']['disableCron'][0] = 'Onemogoči ukaz periodičnih opravil';
$GLOBALS['TL_LANG']['tl_settings']['disableCron'][1] = 'Onemogoči ukaz periodičnih opravil in izvajaj cron.php skripto s pravim cron opravilom (katerega morate nastaviti ročno na strežniku)';
$GLOBALS['TL_LANG']['tl_settings']['minifyMarkup'][0] = 'Skrči HTML označevanje';
$GLOBALS['TL_LANG']['tl_settings']['minifyMarkup'][1] = 'Skrči HTML označevanje predno je poslano brsalniku (zahteva je PHP tidy razširitev)';
$GLOBALS['TL_LANG']['tl_settings']['gzipScripts'][0] = 'Kompresiraj skripte';
$GLOBALS['TL_LANG']['tl_settings']['gzipScripts'][1] = 'Ustvari kompresirano verzijo združenih CSS in JavaScript datotek. Zahteva je prilagoditev datoteke .htaccess';
$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage'][0] = 'Elementov na stran';
$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage'][1] = 'Tu lahko določite število elementov na stran v skrbništvu.';
$GLOBALS['TL_LANG']['tl_settings']['maxResultsPerPage'][0] = 'Največje število elementov na stran';
$GLOBALS['TL_LANG']['tl_settings']['maxResultsPerPage'][1] = 'Ta splošna omejitev bo upoštevana, če uporabnik izbere možnost "prikaži vse zapise".';
$GLOBALS['TL_LANG']['tl_settings']['fileSyncExclude'][0] = 'Izvzemi mape iz sinhronizacije';
$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse'][0] = 'Ne razbij postavitve elementov';
$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse'][1] = 'Ne razbij postavitve elementov v pogledu skrbništva.';
$GLOBALS['TL_LANG']['tl_settings']['urlSuffix'][0] = 'URL pripona';
$GLOBALS['TL_LANG']['tl_settings']['urlSuffix'][1] = 'URL pripona bo dodana URI nizu za posnemanje statičnih dokumentov.';
$GLOBALS['TL_LANG']['tl_settings']['cacheMode'][0] = 'Način z medpomnilnikom';
$GLOBALS['TL_LANG']['tl_settings']['cacheMode'][1] = 'Tu lahko določite v kolikor naj se uporablja medpomnilnik.';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeIp'][0] = 'Anonimiziraj IP naslove';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeIp'][1] = 'V bazo shranjuj anonimizirane IP naslove, z izjemo tabele <em>tl_session</em> (IP naslov je vezan na sejo iz varnostnih razlogov).';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeGA'][0] = 'Anonimiziraj Google Analytics';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeGA'][1] = 'Storitvi Google Analytics posreduj anonimizirane IP naslove.';
$GLOBALS['TL_LANG']['tl_settings']['rewriteURL'][0] = 'Prepiši URL-je';
$GLOBALS['TL_LANG']['tl_settings']['rewriteURL'][1] = 'Določite v kolikor naj Contao ustvarja statične URL-je brez index.php razdelka. Za pravilno delovanje te možnosti, je potrebno omogočiti "mod_rewrite", preimenovati ".htaccess.default" datoteko v ".htaccess" in prilagoditi RewriteBase.';
$GLOBALS['TL_LANG']['tl_settings']['addLanguageToUrl'][0] = 'Dodaj jezik k URL naslovom';
$GLOBALS['TL_LANG']['tl_settings']['addLanguageToUrl'][1] = 'Dodaj jezikovno oznako kot prvi parameter v URL naslovu (npr. <em>http://domain.tld/sl/</em>).';
$GLOBALS['TL_LANG']['tl_settings']['doNotRedirectEmpty'][0] = 'Ne preusmeri praznih URL naslovov';
$GLOBALS['TL_LANG']['tl_settings']['disableAlias'][0] = 'Onemogoči uporabo alias-a strani.';
$GLOBALS['TL_LANG']['tl_settings']['disableAlias'][1] = 'Uporabi številčno kodo ID-ja strani ali članka namesto njenega/njegovega alias-a.';
$GLOBALS['TL_LANG']['tl_settings']['allowedTags'][0] = 'Dovoljene HTML oznake';
$GLOBALS['TL_LANG']['tl_settings']['allowedTags'][1] = 'Tu lahko vnesete dovoljene HTML oznake, ki ne bodo odstranjene.';
$GLOBALS['TL_LANG']['tl_settings']['debugMode'][0] = 'Omogoči način za odpravljanje napak';
$GLOBALS['TL_LANG']['tl_settings']['debugMode'][1] = 'Na zaslon izpiši določene podatke o delovanju strani, kot npr. povpraševanja podatkovnih tabel ipd.';
$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'][0] = 'Onemogoči preverjanje posrednika';
$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'][1] = 'Ne preverjaj naslova posrednika, ko je obrazec oddan. Izbira te možnosti lahko predstavlja varnostno tveganje!';
$GLOBALS['TL_LANG']['tl_settings']['lockPeriod'][0] = 'Čas zaklepanja računa';
$GLOBALS['TL_LANG']['tl_settings']['lockPeriod'][1] = 'Vaš račun se bo zaklenil za določen čas, v kolikor trikrat zapored vnesete napačno geslo.';
$GLOBALS['TL_LANG']['tl_settings']['displayErrors'][0] = 'Prikaži opozorila o napakah';
$GLOBALS['TL_LANG']['tl_settings']['displayErrors'][1] = 'Na zaslon izpiši sporočilo o napaki (ni priporočljivo za že razvite, proizvodne strani).';
$GLOBALS['TL_LANG']['tl_settings']['logErrors'][0] = 'Napake zapisuj v dnevniško datoteko';
$GLOBALS['TL_LANG']['tl_settings']['logErrors'][1] = 'Zapiši sporočila o napakah v dnevniško datoteko (<em>system/logs/error.log</em>).';
$GLOBALS['TL_LANG']['tl_settings']['coreOnlyMode'][0] = 'Zaženi v safe mode';
$GLOBALS['TL_LANG']['tl_settings']['coreOnlyMode'][1] = 'Zaženi Contao v varnem načinu in naloži le osnovne module';
$GLOBALS['TL_LANG']['tl_settings']['disableIpCheck'][0] = 'Onemogoči preverjanje IP';
$GLOBALS['TL_LANG']['tl_settings']['disableIpCheck'][1] = 'Ne omejuj sej na IP naslove. Izbira te možnosti lahko predstavlja varnostno tveganje!';
$GLOBALS['TL_LANG']['tl_settings']['allowedDownload'][0] = 'Tipi datotek za prenos';
$GLOBALS['TL_LANG']['tl_settings']['allowedDownload'][1] = 'Tu lahko naštejete (ločite z vejico) tipe datotek, ki jih bo mogoče prenesti.';
$GLOBALS['TL_LANG']['tl_settings']['validImageTypes'][0] = 'Tipi slikovnih datotek';
$GLOBALS['TL_LANG']['tl_settings']['validImageTypes'][1] = 'Tu lahko naštejete (ločite z vejico) tipe slikovnih datotek.';
$GLOBALS['TL_LANG']['tl_settings']['editableFiles'][0] = 'Tipi datotek za urejanje';
$GLOBALS['TL_LANG']['tl_settings']['editableFiles'][1] = 'Tu lahko naštejete (ločite z vejico) tipe datotek, ki jih bo mogoče urejati v urejevalniku izvorne kode.';
$GLOBALS['TL_LANG']['tl_settings']['templateFiles'][0] = 'Tipi datotek za predloge';
$GLOBALS['TL_LANG']['tl_settings']['templateFiles'][1] = 'Tukaj lahko vnesete (ločeno z vejico) seznam podprtih tipov datotek za predloge.';
$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth'][0] = 'Največja širina predstavitve';
$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth'][1] = 'Če širina slike ali filma presega določeno vrednost, bo ta samodejno prilagojena.';
$GLOBALS['TL_LANG']['tl_settings']['jpgQuality'][0] = 'Kvaliteta JPG predoglednih sličic';
$GLOBALS['TL_LANG']['tl_settings']['jpgQuality'][1] = 'Tu lahko v odstotkih določite kvaliteto predoglednih sličic.';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgWidth'][0] = 'Maksimalna širina GD slike';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgWidth'][1] = 'Tukaj lahko vnesete maksimalno širino slike, s katero naj jo GD knjižnica poskusi obdelati.';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgHeight'][0] = 'Maksimalna višina GD slike';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgHeight'][1] = 'Tukaj lahko vnesete maksimalno višino slike, s katero naj jo GD knjižnica poskusi obdelati.';
$GLOBALS['TL_LANG']['tl_settings']['uploadPath'][0] = 'Datotečni imenik upravitelja datotek';
$GLOBALS['TL_LANG']['tl_settings']['uploadPath'][1] = 'Tu lahko določite relativno pot do upravitelja datotek.';
$GLOBALS['TL_LANG']['tl_settings']['uploadTypes'][0] = 'Tipi datotek za nalaganje';
$GLOBALS['TL_LANG']['tl_settings']['uploadTypes'][1] = 'Tu lahko naštejete (ločite z vejico) tipe datotek, ki jih bo mogoče naložiti.';
$GLOBALS['TL_LANG']['tl_settings']['uploadFields'][0] = 'Sočasno nalaganje datotek';
$GLOBALS['TL_LANG']['tl_settings']['uploadFields'][1] = 'Tu lahko določite največje število sočasnih nalaganje datotek.';
$GLOBALS['TL_LANG']['tl_settings']['maxFileSize'][0] = 'Največja velikost datoteke za nalaganje';
$GLOBALS['TL_LANG']['tl_settings']['maxFileSize'][1] = 'Tu lahko določite največjo velikost datoteke za nalaganje v bajtih (1 MB = 1000 kB = 1000000 byte).';
$GLOBALS['TL_LANG']['tl_settings']['imageWidth'][0] = 'Največja širina slike';
$GLOBALS['TL_LANG']['tl_settings']['imageWidth'][1] = 'Tu lahko določite največjo dovoljeno širino slik za nalaganje v pikslih.';
$GLOBALS['TL_LANG']['tl_settings']['imageHeight'][0] = 'Največja višina slike';
$GLOBALS['TL_LANG']['tl_settings']['imageHeight'][1] = 'Tu lahko določite največjo dovoljeno višino slik za nalaganje v pikslih.';
$GLOBALS['TL_LANG']['tl_settings']['enableSearch'][0] = 'Omogoči iskanje';
$GLOBALS['TL_LANG']['tl_settings']['enableSearch'][1] = 'Indeksiraj strani, da jih nato iskalnik lahko preišče.';
$GLOBALS['TL_LANG']['tl_settings']['indexProtected'][0] = 'Indeksiraj zaščitene strani';
$GLOBALS['TL_LANG']['tl_settings']['indexProtected'][1] = 'Bodite previdni pri vključevanju te možnosti in vedno izključite vaše osebne strani iz indeksiranja.';
$GLOBALS['TL_LANG']['tl_settings']['useSMTP'][0] = 'Pošlji e-pošto prek SMTP';
$GLOBALS['TL_LANG']['tl_settings']['useSMTP'][1] = 'Uporabi SMTP strežnik namesto PHP mail() funkcije za pošiljanje e-pošte.';
$GLOBALS['TL_LANG']['tl_settings']['smtpHost'][0] = 'Ime SMTP gostitelja';
$GLOBALS['TL_LANG']['tl_settings']['smtpHost'][1] = 'Prosimo, vnesite ime SMTP gostitelja.';
$GLOBALS['TL_LANG']['tl_settings']['smtpUser'][0] = 'SMTP uporabniško ime.';
$GLOBALS['TL_LANG']['tl_settings']['smtpUser'][1] = 'Tu lahko vnesete SMTP uporabniško ime.';
$GLOBALS['TL_LANG']['tl_settings']['smtpPass'][0] = 'SMTP geslo';
$GLOBALS['TL_LANG']['tl_settings']['smtpPass'][1] = 'Tu lahko vnesete SMTP geslo.';
$GLOBALS['TL_LANG']['tl_settings']['smtpEnc'][0] = 'SMTP šifriranje';
$GLOBALS['TL_LANG']['tl_settings']['smtpEnc'][1] = 'Tu lahko vnesete metodo šifriranja (SSL ali TLS).';
$GLOBALS['TL_LANG']['tl_settings']['smtpPort'][0] = 'Številka SMTP vrat';
$GLOBALS['TL_LANG']['tl_settings']['smtpPort'][1] = 'Prosimo, vnesite številko SMTP vrat.';
$GLOBALS['TL_LANG']['tl_settings']['inactiveModules'][0] = 'Neaktivne razširitve';
$GLOBALS['TL_LANG']['tl_settings']['inactiveModules'][1] = 'Tu lahko onemogočite nepotrebne razširitve.';
$GLOBALS['TL_LANG']['tl_settings']['undoPeriod'][0] = 'Čas shranjevanja razveljavitvenih korakov';
$GLOBALS['TL_LANG']['tl_settings']['undoPeriod'][1] = 'Tu lahko določite čas shranjevanja razveljavitvenih korakov v sekundah (24 ur= 86400 sekund).';
$GLOBALS['TL_LANG']['tl_settings']['versionPeriod'][0] = 'Čas shranjevanja različic';
$GLOBALS['TL_LANG']['tl_settings']['versionPeriod'][1] = 'Tu lahko določite čas shranjevanja preteklih različic v sekundah (90 dni= 7776000 sekund).';
$GLOBALS['TL_LANG']['tl_settings']['logPeriod'][0] = 'Čas shranjevanja dnevnika vnosov';
$GLOBALS['TL_LANG']['tl_settings']['logPeriod'][1] = 'Tu lahko določite čas shranjevanja dnevnika vnosov v sekundah (14 dni= 1209600 sekund).';
$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout'][0] = 'Dolžina seje';
$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout'][1] = 'Tu lahko določite največjo dolžino seje v sekundah (60 minut= 3600 sekund).';
$GLOBALS['TL_LANG']['tl_settings']['autologin'][0] = 'Interval samodejne prijave';
$GLOBALS['TL_LANG']['tl_settings']['autologin'][1] = 'Tu lahko določite interval dolžine prijave uporabnika v predstavitvenem delu (front end) strani v sekundah (90 dni= 7776000 sekund).';
$GLOBALS['TL_LANG']['tl_settings']['defaultUser'][0] = 'Privzeti lastnik strani';
$GLOBALS['TL_LANG']['tl_settings']['defaultUser'][1] = 'Tu lahko določite uporabnika kot privzetega lastnika strani.';
$GLOBALS['TL_LANG']['tl_settings']['defaultGroup'][0] = 'Privzeta skupina strani';
$GLOBALS['TL_LANG']['tl_settings']['defaultGroup'][1] = 'Tu lahko določite skupino kot privzetega lastnika strani.';
$GLOBALS['TL_LANG']['tl_settings']['defaultChmod'][0] = 'Privzete pravice dostopa';
$GLOBALS['TL_LANG']['tl_settings']['defaultChmod'][1] = 'Tu lahko določite privzete pravice dostopa strani in člankov.';
$GLOBALS['TL_LANG']['tl_settings']['liveUpdateBase'][0] = 'URL enostavne posodobitve';
$GLOBALS['TL_LANG']['tl_settings']['liveUpdateBase'][1] = 'Tu lahko vnesete URL enostavne posodobitve.';
$GLOBALS['TL_LANG']['tl_settings']['title_legend'] = 'Naslov spletne strani';
$GLOBALS['TL_LANG']['tl_settings']['date_legend'] = 'Datum in čas';
$GLOBALS['TL_LANG']['tl_settings']['global_legend'] = 'Splošne nastavitve';
$GLOBALS['TL_LANG']['tl_settings']['backend_legend'] = 'Nastavitev skrbništva';
$GLOBALS['TL_LANG']['tl_settings']['frontend_legend'] = 'Nastavitev predstavitvenega dela';
$GLOBALS['TL_LANG']['tl_settings']['privacy_legend'] = 'Nastavitve zasebnosti';
$GLOBALS['TL_LANG']['tl_settings']['security_legend'] = 'Varnostne nastavitve';
$GLOBALS['TL_LANG']['tl_settings']['files_legend'] = 'Datoteke in slike';
$GLOBALS['TL_LANG']['tl_settings']['uploads_legend'] = 'Nastavitve nalaganja';
$GLOBALS['TL_LANG']['tl_settings']['search_legend'] = 'Nastavitve iskanja';
$GLOBALS['TL_LANG']['tl_settings']['smtp_legend'] = 'Nastavitve SMTP';
$GLOBALS['TL_LANG']['tl_settings']['ftp_legend'] = 'Trik "varnega načina"';
$GLOBALS['TL_LANG']['tl_settings']['modules_legend'] = 'Neaktivne razširitve';
$GLOBALS['TL_LANG']['tl_settings']['timeout_legend'] = 'Določitev časovnih vrednosti';
$GLOBALS['TL_LANG']['tl_settings']['chmod_legend'] = 'Privzete pravice dostopa';
$GLOBALS['TL_LANG']['tl_settings']['update_legend'] = 'Enostavna posodobitev';
$GLOBALS['TL_LANG']['tl_settings']['static_legend'] = 'Statični viri';
$GLOBALS['TL_LANG']['tl_settings']['edit'] = 'Uredi krajevne nastavitve';
$GLOBALS['TL_LANG']['tl_settings']['both'] = 'Uporabi medpomnilnik strežnika in brskalnika';
$GLOBALS['TL_LANG']['tl_settings']['server'] = 'Uporabi le medpomnilnik strežnika';
$GLOBALS['TL_LANG']['tl_settings']['browser'] = 'Uporabi le medpomnilnik brskalnika';
$GLOBALS['TL_LANG']['tl_settings']['none'] = 'Onemogoči medpomnenje';
