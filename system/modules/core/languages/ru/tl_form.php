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

$GLOBALS['TL_LANG']['tl_form']['title'][0] = 'Название';
$GLOBALS['TL_LANG']['tl_form']['title'][1] = 'Введите название формы.';
$GLOBALS['TL_LANG']['tl_form']['alias'][0] = 'Алиас формы';
$GLOBALS['TL_LANG']['tl_form']['alias'][1] = 'Алиас формы является уникальной ссылкой на форму, которую можно использовать вместо её ID.';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][0] = 'Страница перенаправления';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][1] = 'Выберите страницу, к которой будут перенаправлены посетители после отправки формы.';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][0] = 'Отправить данные формы по e-mail';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][1] = 'Отправить принятые данные формы на e-mail адрес.';
$GLOBALS['TL_LANG']['tl_form']['recipient'][0] = 'Адрес получателя';
$GLOBALS['TL_LANG']['tl_form']['recipient'][1] = 'Разделите несколько e-mail адресов запятой.';
$GLOBALS['TL_LANG']['tl_form']['subject'][0] = 'Тема';
$GLOBALS['TL_LANG']['tl_form']['subject'][1] = 'Введите тему сообщения.';
$GLOBALS['TL_LANG']['tl_form']['format'][0] = 'Формат данных';
$GLOBALS['TL_LANG']['tl_form']['format'][1] = 'Определяет в каком формате будут переданы данные.';
$GLOBALS['TL_LANG']['tl_form']['raw'][0] = 'Простой текст';
$GLOBALS['TL_LANG']['tl_form']['raw'][1] = 'Данные формы посылаются как простое текстовое сообщение, каждое поле с новой строки.';
$GLOBALS['TL_LANG']['tl_form']['xml'][0] = 'XML файл';
$GLOBALS['TL_LANG']['tl_form']['xml'][1] = 'Данные формы прикрепляются к письму как XML файл.';
$GLOBALS['TL_LANG']['tl_form']['csv'][0] = 'CSV файл';
$GLOBALS['TL_LANG']['tl_form']['csv'][1] = 'Данные формы прикрепляются к письму как CSV файл.';
$GLOBALS['TL_LANG']['tl_form']['email'][0] = 'E-mail';
$GLOBALS['TL_LANG']['tl_form']['email'][1] = 'Игнорируются все поля, кроме <em>email</em>, <em>тема</em>, <em>сообщение</em> и <em>cc</em> (скрытая копия). Данные формы передаются так, как они были отправлены из почтового клиента. Разрешена загрузка файлов.';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][0] = 'Пропускать пустые поля';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][1] = 'Скрыть пустые поля в письме.';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][0] = 'Хранить значения';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][1] = 'Хранить принятые данные в базе данных.';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][0] = 'Таблица для записи';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][1] = 'Таблица должна содержать колонку для каждого поля формы.';
$GLOBALS['TL_LANG']['tl_form']['method'][0] = 'Метод передачи формы';
$GLOBALS['TL_LANG']['tl_form']['method'][1] = 'По умолчанию используется метод передачи POST.';
$GLOBALS['TL_LANG']['tl_form']['attributes'][0] = 'CSS ID и класс';
$GLOBALS['TL_LANG']['tl_form']['attributes'][1] = 'Вы можете ввести CSS ID (ID атрибута) и один или несколько CSS классов (атрибутов класса).';
$GLOBALS['TL_LANG']['tl_form']['formID'][0] = 'ID формы';
$GLOBALS['TL_LANG']['tl_form']['formID'][1] = 'Идентификатор (ID) формы требуется для запуска модуля Contao.';
$GLOBALS['TL_LANG']['tl_form']['tableless'][0] = 'Бестабличная вёрстка';
$GLOBALS['TL_LANG']['tl_form']['tableless'][1] = 'Генерация формы без использования HTML-таблиц.';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][0] = 'Разрешить HTML-теги';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][1] = 'Разрешить HTML-теги в полях формы.';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][0] = 'Дата изменения';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][1] = 'Дата и время последнего изменения';
$GLOBALS['TL_LANG']['tl_form']['title_legend'] = 'Название и перенаправление';
$GLOBALS['TL_LANG']['tl_form']['email_legend'] = 'Отправка данных формы';
$GLOBALS['TL_LANG']['tl_form']['store_legend'] = 'Хранение данных формы';
$GLOBALS['TL_LANG']['tl_form']['expert_legend'] = 'Экспертные настройки';
$GLOBALS['TL_LANG']['tl_form']['config_legend'] = 'Конфигурация формы';
$GLOBALS['TL_LANG']['tl_form']['new'][0] = 'Новая форма';
$GLOBALS['TL_LANG']['tl_form']['new'][1] = 'Создать новую форму';
$GLOBALS['TL_LANG']['tl_form']['show'][0] = 'Детали формы';
$GLOBALS['TL_LANG']['tl_form']['show'][1] = 'Показать детали формы ID %s';
$GLOBALS['TL_LANG']['tl_form']['edit'][0] = 'Редактировать форму';
$GLOBALS['TL_LANG']['tl_form']['edit'][1] = 'Редактировать форму ID %s';
$GLOBALS['TL_LANG']['tl_form']['editheader'][0] = 'Редактировать настройки формы';
$GLOBALS['TL_LANG']['tl_form']['editheader'][1] = 'Редактировать настройки формы ID %s';
$GLOBALS['TL_LANG']['tl_form']['copy'][0] = 'Дублировать форму';
$GLOBALS['TL_LANG']['tl_form']['copy'][1] = 'Дублировать форму ID %s';
$GLOBALS['TL_LANG']['tl_form']['delete'][0] = 'Удалить форму';
$GLOBALS['TL_LANG']['tl_form']['delete'][1] = 'Удалить форму ID %s';
