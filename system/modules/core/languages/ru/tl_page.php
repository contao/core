<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/ru/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_page']['title'][0] = 'Название страницы';
$GLOBALS['TL_LANG']['tl_page']['title'][1] = 'Введите название страницы.';
$GLOBALS['TL_LANG']['tl_page']['alias'][0] = 'Алиас страницы';
$GLOBALS['TL_LANG']['tl_page']['alias'][1] = 'Алиас страницы является уникальной ссылкой на страницу, которую можно использовать вместо её ID.';
$GLOBALS['TL_LANG']['tl_page']['type'][0] = 'Тип страницы';
$GLOBALS['TL_LANG']['tl_page']['type'][1] = 'Выберите тип страницы.';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][0] = 'Заголовок страницы (в теге TITLE)';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][1] = 'Заголовок страницы помещаемый в тег TITLE вашего веб-сайта и отображаемый в результатах поиска. Если оставить поле пустым, то заголовок страницы будет взят из поля \'Название страницы\'.';
$GLOBALS['TL_LANG']['tl_page']['language'][0] = 'Язык';
$GLOBALS['TL_LANG']['tl_page']['language'][1] = 'Укажите язык веб-сайта в соответствии со стандартом ISO-639-1, (напр. "en" для English, ru для Русский).';
$GLOBALS['TL_LANG']['tl_page']['robots'][0] = 'Robots tag';
$GLOBALS['TL_LANG']['tl_page']['robots'][1] = 'Вы можете выбрать, как поисковой системе следует обрабатывать страницу.';
$GLOBALS['TL_LANG']['tl_page']['description'][0] = 'Описание страницы';
$GLOBALS['TL_LANG']['tl_page']['description'][1] = 'Вы можете ввести краткое описание страницы, которое будет выводиться при поиске. Индексируется обычно от 150 до 300 символов. Добавляет описание в тег <em>meta name="description" content=" "</em>';
$GLOBALS['TL_LANG']['tl_page']['redirect'][0] = 'Тип перенаправления';
$GLOBALS['TL_LANG']['tl_page']['redirect'][1] = 'Временные передачи посылают HTTP 302 заголовок, постоянные посылают HTTP 301 заголовок.';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][0] = 'Перейти на страницу';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][1] = 'Выберите страницу, на которую будут перенаправлены посетители. Оставьте пустым, для перенаправления на первую регулярную подстраницу.';
$GLOBALS['TL_LANG']['tl_page']['fallback'][0] = 'Язык обратной связи';
$GLOBALS['TL_LANG']['tl_page']['fallback'][1] = 'Contao автоматически переадресует посетителя на корневую страницу сайта на подходящем языке. Если нет такой локализованной страницы, то выводится сообщение об ошибке <em>No pages found</em> (страница не найдена).';
$GLOBALS['TL_LANG']['tl_page']['dns'][0] = 'Имя домена';
$GLOBALS['TL_LANG']['tl_page']['dns'][1] = 'Вы можете ограничить доступ на сайт определенным доменным именем. Если назначите доменное имя корневой странице сайта, посетители будут перенаправляться на этот сайт всякий раз, когда введут соответствующее доменное имя (напр. <em>youdomain.com</em> или <em>subdomain.yourdomain.com</em>).';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][0] = 'Адрес электронной почты администратора';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][1] = 'Автоматически-созданные сообщения (напр. электронные письма активации или подписные электронные письма подтверждения) будут отправлены на этот адрес. Также используется как адрес отправителя.';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][0] = 'Формат даты';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][1] = 'Введите формат даты, используемый функцией PHP date()';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][0] = 'Формат времени';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][1] = 'Введите формат времени, используемый функцией PHP date()';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][0] = 'Формат даты и времени';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][1] = 'Введите формат даты и времени, используемый функцией PHP date()';
$GLOBALS['TL_LANG']['tl_page']['createSitemap'][0] = 'Создать XML-карту сайта';
$GLOBALS['TL_LANG']['tl_page']['createSitemap'][1] = 'Создать XML-карту сайта (для системы Google) в корневом каталоге сайта';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][0] = 'Имя файла карты сайта';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][1] = 'Введите имя файла карты сайта без расширения.';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][0] = 'Использовать HTTPS в карте сайта';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][1] = 'Создание карты данного сайта с <em>https://</em> ссылками.';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][0] = 'Перенаправить на страницу';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][1] = 'Если выбрать опцию, посетитель будет перенаправлен на другую страницу (например, страницу с формой авторизации или на главную страницу).';
$GLOBALS['TL_LANG']['tl_page']['protected'][0] = 'Защищенная страница';
$GLOBALS['TL_LANG']['tl_page']['protected'][1] = 'Если выбрать опцию, можно ограничить доступ к странице определенным участникам группы.';
$GLOBALS['TL_LANG']['tl_page']['groups'][0] = 'Допустить группу участников';
$GLOBALS['TL_LANG']['tl_page']['groups'][1] = 'Вы можете предоставить доступ одной или нескольким группам участников. Если вы не укажете группу, любой зарегистрированный пользователь сайта будет иметь доступ к странице.';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][0] = 'Назначить макет';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][1] = 'По умолчанию страница использует тот же макет, что и родительская страница. Если выбрать эту опцию, можно переопределить макет для конкретной страницы и всех вложенных страниц.';
$GLOBALS['TL_LANG']['tl_page']['layout'][0] = 'Макет страницы';
$GLOBALS['TL_LANG']['tl_page']['layout'][1] = 'Выберите макет страницы. Вы можете редактировать или создать новый макет, используя модуль \'Макет страницы\'.';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][0] = 'Мобильный макет страницы';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][1] = 'Этот макет будет использован, если посетитель использует мобильные устройства.';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][0] = 'Установить время хранения в кэше';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][1] = 'Разрешить кэширование страницы и подстраницы. По умолчанию страница использует то же время хранения кэша, что и родительская страница. Вы можете назначить новое время хранения в кэше для данной страницы и подстраниц.';
$GLOBALS['TL_LANG']['tl_page']['cache'][0] = 'Время кэширования';
$GLOBALS['TL_LANG']['tl_page']['cache'][1] = 'В заданный период кэширования, страница будет загружаться из кэша.';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][0] = 'Назначить права';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][1] = 'Назначение прав доступа позволяют определить, кто из пользователей сможет модифицировать страницу и статьи. По умолчанию - права родительской страницы.';
$GLOBALS['TL_LANG']['tl_page']['cuser'][0] = 'Владелец';
$GLOBALS['TL_LANG']['tl_page']['cuser'][1] = 'Выберите пользователя в качестве владельца текущей страницы.';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][0] = 'Группа';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][1] = 'Выберите группу в качестве владельца текущей страницы.';
$GLOBALS['TL_LANG']['tl_page']['chmod'][0] = 'Права доступа';
$GLOBALS['TL_LANG']['tl_page']['chmod'][1] = 'Определите права доступа к странице и ее подстраницам. У каждой страницы может быть три уровня прав доступа: для пользователя, для группы и для всех.';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][0] = 'Запретить поиск на странице';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][1] = 'Исключить страницу из поискового индекса.';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][0] = 'CSS класс';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][1] = 'Класс(ы) будут использоваться в меню навигации и теле тега. Таким образом, вы можете отформатировать пункты навигации индивидуально.';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][0] = 'Показать в карте сайта';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][1] = 'Вы можете определить, будет ли страница отображается в карте сайта.';
$GLOBALS['TL_LANG']['tl_page']['hide'][0] = 'Исключить страницу из навигации';
$GLOBALS['TL_LANG']['tl_page']['hide'][1] = 'Если выбрать опцию, текущая страница не будет отображаться в меню навигации.';
$GLOBALS['TL_LANG']['tl_page']['guests'][0] = 'Показывать только для гостей';
$GLOBALS['TL_LANG']['tl_page']['guests'][1] = 'Скрыть страницу в навигации если посетитель вошёл в систему.';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][0] = 'Атрибут Tab index';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][1] = 'Положение элемента навигации в порядке табуляции.';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][0] = 'Клавиша доступа';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][1] = 'Клавиша доступа - символ, который может быть назначен полю формы. При нажатии [ALT] или [CTRL] и данный символ, поле формы получит фокус.';
$GLOBALS['TL_LANG']['tl_page']['published'][0] = 'Опубликовать';
$GLOBALS['TL_LANG']['tl_page']['published'][1] = 'Отметьте пункт для отображения страницы посетителям.';
$GLOBALS['TL_LANG']['tl_page']['start'][0] = 'Публиковать с';
$GLOBALS['TL_LANG']['tl_page']['start'][1] = 'Если ввести дату начала публикации, текущая страница будет показана на сайте с этого дня.';
$GLOBALS['TL_LANG']['tl_page']['stop'][0] = 'Публиковать до';
$GLOBALS['TL_LANG']['tl_page']['stop'][1] = 'Если ввести дату окончания публикации, текущая страница не будет показана на сайте после этого дня.';
$GLOBALS['TL_LANG']['tl_page']['title_legend'] = 'Название и тип';
$GLOBALS['TL_LANG']['tl_page']['meta_legend'] = 'Мета-информация';
$GLOBALS['TL_LANG']['tl_page']['system_legend'] = 'Системные настройки';
$GLOBALS['TL_LANG']['tl_page']['redirect_legend'] = 'Настройки перенаправления';
$GLOBALS['TL_LANG']['tl_page']['dns_legend'] = 'Настройки DNS';
$GLOBALS['TL_LANG']['tl_page']['global_legend'] = 'Общие настройки';
$GLOBALS['TL_LANG']['tl_page']['mobile_legend'] = 'Настройки для мобильных устройств';
$GLOBALS['TL_LANG']['tl_page']['sitemap_legend'] = 'XML-карта сайта';
$GLOBALS['TL_LANG']['tl_page']['forward_legend'] = 'Автоперенаправление';
$GLOBALS['TL_LANG']['tl_page']['protected_legend'] = 'Защита доступа';
$GLOBALS['TL_LANG']['tl_page']['layout_legend'] = 'Настройки макета';
$GLOBALS['TL_LANG']['tl_page']['cache_legend'] = 'Настройки кэша';
$GLOBALS['TL_LANG']['tl_page']['chmod_legend'] = 'Права доступа';
$GLOBALS['TL_LANG']['tl_page']['search_legend'] = 'Настройки поиска';
$GLOBALS['TL_LANG']['tl_page']['expert_legend'] = 'Экспертные настройки';
$GLOBALS['TL_LANG']['tl_page']['tabnav_legend'] = 'Кнопки навигации';
$GLOBALS['TL_LANG']['tl_page']['publish_legend'] = 'Настройки публикации';
$GLOBALS['TL_LANG']['tl_page']['permanent'] = '301 Постоянное перенаправление';
$GLOBALS['TL_LANG']['tl_page']['temporary'] = '302 Временное перенаправление';
$GLOBALS['TL_LANG']['tl_page']['map_default'] = 'По умолчанию';
$GLOBALS['TL_LANG']['tl_page']['map_always'] = 'Всегда показывать';
$GLOBALS['TL_LANG']['tl_page']['map_never'] = 'Никогда не показывать';
$GLOBALS['TL_LANG']['tl_page']['new'][0] = 'Новая страница';
$GLOBALS['TL_LANG']['tl_page']['new'][1] = 'Создать новую страницу';
$GLOBALS['TL_LANG']['tl_page']['show'][0] = 'Детали страницы';
$GLOBALS['TL_LANG']['tl_page']['show'][1] = 'Показать детали страницы ID %s';
$GLOBALS['TL_LANG']['tl_page']['edit'][0] = 'Редактировать страницу';
$GLOBALS['TL_LANG']['tl_page']['edit'][1] = 'Редактировать страницу ID %s';
$GLOBALS['TL_LANG']['tl_page']['cut'][0] = 'Переместить страницу';
$GLOBALS['TL_LANG']['tl_page']['cut'][1] = 'Переместить страницу ID %s';
$GLOBALS['TL_LANG']['tl_page']['copy'][0] = 'Дублировать страницу';
$GLOBALS['TL_LANG']['tl_page']['copy'][1] = 'Дублировать страницу ID %s';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][0] = 'Дублировать страницу с подстраницами';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][1] = 'Дублировать страницу ID %s с подстраницами';
$GLOBALS['TL_LANG']['tl_page']['delete'][0] = 'Удалить страницу';
$GLOBALS['TL_LANG']['tl_page']['delete'][1] = 'Удалить страницу ID %s';
$GLOBALS['TL_LANG']['tl_page']['toggle'][0] = 'Опубликовать/изъять страницу';
$GLOBALS['TL_LANG']['tl_page']['toggle'][1] = 'Опубликовать/изъять страницу ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][0] = 'Вставить после';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][1] = 'Вставить после страницы ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][0] = 'Вставить в';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][1] = 'Вставить в страницу ID %s';
$GLOBALS['TL_LANG']['tl_page']['articles'][0] = 'Редактировать статью';
$GLOBALS['TL_LANG']['tl_page']['articles'][1] = 'Редактировать статью на странице ID %s';
$GLOBALS['TL_LANG']['CACHE'][0] = '0 (не кэшировать)';
$GLOBALS['TL_LANG']['CACHE'][5] = '5 секунд';
$GLOBALS['TL_LANG']['CACHE'][15] = '15 секунд';
$GLOBALS['TL_LANG']['CACHE'][30] = '30 секунд';
$GLOBALS['TL_LANG']['CACHE'][60] = '1 минута';
$GLOBALS['TL_LANG']['CACHE'][300] = '5 минут';
$GLOBALS['TL_LANG']['CACHE'][900] = '15 минут';
$GLOBALS['TL_LANG']['CACHE'][1800] = '30 минут';
$GLOBALS['TL_LANG']['CACHE'][3600] = '60 минут';
$GLOBALS['TL_LANG']['CACHE'][10800] = '3 часа';
$GLOBALS['TL_LANG']['CACHE'][21600] = '6 часов';
$GLOBALS['TL_LANG']['CACHE'][43200] = '12 часов';
$GLOBALS['TL_LANG']['CACHE'][86400] = '24 часа';
$GLOBALS['TL_LANG']['CACHE'][259200] = '3 дня';
$GLOBALS['TL_LANG']['CACHE'][604800] = '7 дней';
$GLOBALS['TL_LANG']['CACHE'][2592000] = '30 дней';
