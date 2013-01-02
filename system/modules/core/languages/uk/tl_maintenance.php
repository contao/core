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
 * @link https://www.transifex.com/projects/p/contao/language/uk/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Кеш таблиці';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Будь ласка, виберіть кешування таблиць, які ви хочете виключити.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Фронтенд користувач';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Для того, щоб індексувати захищені сторінки, ви повинні створити зовнішній інтерфейс користувача, який має доступ до цих сторінок.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Завдання';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Опис';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Очистити кеш';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Кеш був скинутий';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Он-лайн оновлення';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Ключ ID для он-лайн оновлення';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Перейти до он-лайн оновлення';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Ваша версія Contao %s не вимагає оновлення';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Доступна більш нова версія %s Contao';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Введіть, будь-ласка, ваш ключ ID он-лайн оновлення';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Тимчасовий каталог (system/tmp) недоступний для запису';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Подивитися зміни у версії';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Оновити систему';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Зміст архіву';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Зарезервовані файли';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Оновлені файли';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Перебудувати пошукові індекси';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Перебудувати індекси';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Незнайдено сторінок для пошуку';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Будь-ласка, почекайте сторінку, щоб завантажити повністю, перш ніж приступити!.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Зачекайте, будь ласка, поки пошукові індекси будуть оновлені';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Пошукові індекси успішно оновлено.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Будь ласка, введіть ваш %s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Очистити пошуковий індекс';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Очищує таблиці <em>tl_search</em> та <em>tl_search_index</em>. Після цього ви повинні перебудувати пошуковий індекс (див. вище).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Очистити таблицю відновлень';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Очищує таблицю <em>tl_undo</em>, в котрій зберігаються видалені записи. Це завадання постійно видаляє ці записи.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Очистити таблицю версій';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Очищує таблицю <em>tl_version</em>, в котрій зберігаються попередні версії записів. Це завдання постійно видаляє ці записи.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Очистити кеш зображень';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Видаляє автоматично створені зображення та очищає кеш сторінок, таким чином оновлюються посилання на видалені ресурси.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Очистити кеш скриптів';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Видаляє автоматично створені .css та .js файли, заново створює стилі панелі управління та потім очищає кеш сторінок.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Очистити кеш сторінок';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Видаляє кешовані сторінки зовнішнього інтерфейсу.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Очистити внутрішній кеш.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Видаляє кешованні версії DCA та мовних файлів. Ви маєте можливість повністю відключити внутрішній кеш в налаштуваннях системи.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Очистити тимчасові файли';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Видаляє тимчасові файли.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Перебудувати XML файли';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Створює заново XML файли (карта веб-сайту та файли каналів), потім очищує кеш сторінок, таким чином оновлюються посилання на видалені ресурси.';
