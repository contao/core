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
 * @link https://www.transifex.com/projects/p/contao/language/ru/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Очистить данные';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Выберите данные, которые хотите очистить и перестроить.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Пользователь внешнего интерфейса';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Авторизовать пользователя внешнего интерфейса для индексирования защищенных страниц.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Задание';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Описание';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Очистить данные';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Данные были очищены';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Онлайн обновление';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'ID онлайн обновления';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Перейти к онлайн обновлению';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Contao %s не требует обновления.';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Доступна новая Contao %s';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Введите ID онлайн обновления';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Временный каталог (system/tmp) не доступен для записи';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Посмотреть список изменений';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Запустить обновление';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Содержимое архива обновления';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Резервные копии файлов';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Обновленные файлы';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Перестроить поисковый индекс';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Перестроить индекс';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Доступных для поиска страниц не найдено';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Дождитесь полной загрузки страницы прежде, чем продолжите!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Дождитесь окончания перестройки поискового индекса.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Поисковый индекс перестроен. Теперь можете продолжить.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Введите %s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Очитить поисковый индекс';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Очищает таблицы <em>tl_search</em> и <em>tl_search_index</em>. После этого вы должны перестроить поисковый индекс (см. выше).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Очитить таблицу востановлений';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Очищает <em>tl_undo</em> таблицу, которая хранит удаленные данные. Это задание постоянно удаляет эти данные.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Очитить таблицу с версиями';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Очищает <em>tl_version</em> таблицу, которая хранит предыдущие версии данных. Это задание постоянно удаляет эти версии.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Очистить кэш изображений';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Удаляет автоматически созданные изображения, а затем очищает кэш страниц, при этом очищаются ссылки на удаленные ресурсы.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Очистить кэш скриптов';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Удаляет автоматически созданные .css и .js файлы, создает заново внутренние таблицы стилей и затем очищает кэш страниц.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Очистить кэш страниц';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Удаляет  кэшированные страницы внешнего интерфейса.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Очистить внутренний кеш';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Удаляет кэшированные версии DCA и языковых файлов. Вы можете полностью отключить внутренний кэш в настройках панели управления.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Очистить временные файлы';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Удаляет временные файлы.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Создать заново XML файлы';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Создает заново XML файлы (карты веб-сайта и каналы), а затем очищает кэш страниц, при этом очищаются ссылки на удаленные ресурсы.';
