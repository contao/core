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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Тема';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Введите тему рассылки.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Алиас рассылки';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Алиас рассылки является уникальной ссылкой на неё, которую можно использовать вместо её ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML-контент';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Введите HTML-содержание рассылки. Вы можете применять специальные символы <em>##email##</em> для вставки e-mail адреса получателей. Сгенерировать ссылку отписки как <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Текстовый контент';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Введите текст рассылки. Вы можете применять специальные символы <em>##email##</em> для вставки e-mail адреса получателей. Сгенерировать ссылку отписки как <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Добавить вложение';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Прикрепить один или несколько файлов к рассылке.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Вложения';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Выберите файлы, которые вы хотите добавить к рассылке.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Шаблон е-mail письма';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Выберите шаблон e-mail письма (шаблон группы <em>mail_</em>).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Отправить как текст';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Если вы выбрали эту опцию, рассылка будет отправлена как текст. Все HTML-теги будут удалены из письма.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Внешние изображения';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Не вставлять изображения в HTML рассылки.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Имя отправителя';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Введите имя отправителя';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Адрес отправителя';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Введите адрес отправителя. Если не изменять, будет использован e-mail адрес администратора сайта.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Электронных писем за цикл';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Для защиты скрипта от превышения времени исполнения, процесс отправки разделён на несколько циклов. Вы можете задать число писем на цикл рассылки, в зависимости от максимального времени выполнения скрипта, заданного в вашем php.ini.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Задержка в секундах';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Некоторые почтовые серверы ограничивают число электронных писем, которые можно отправить в минуту. Вы можете изменить время ожидания между циклами в секундах, для контроля над процессом.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Смещение';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'В случае, если процесс отправки прервется, вы можете ввести цифровое значение смещения для продолжения с определенного получателя. Вы можете проверить сколько писем было отправлено в файле <em>system/logs/newsletter_*.log</em>. Например, если 120 писем было отправлено, введите "120", чтобы продолжить со 121-го получателя (отсчет начинается с 0).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Отправить предварительный просмотр на';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Отправить предварительный просмотр рассылки по следующему электронному адресу.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Название и тема';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML-контент';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Текстовый контент';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Вложения';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Настройки шаблона';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Экспертные настройки';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Отправить';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Отправить на %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Не отправлено';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Дата отправки';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Рассылка была отправлена %s получателям.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = 'Неверных/отключенных e-mail адресов: %s шт. (см. system log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Нет активных подписчиков на этот канал.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'От';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Вложения';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Отправить предварительный просмотр';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Вы действительно хотите отправить рассылку?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Новая рассылка';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Создать новую рассылку';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Детали рассылки';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Показать детали рассылки ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Редактировать рассылку';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Редактировать рассылку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Дублировать рассылку';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Дублировать рассылку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Переместить рассылку';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Переместить рассылку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Удалить рассылку';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Удалить рассылку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Редактировать канал';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Редактировать этот канал';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Вставить в этот канал';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Вставить после рассылки с ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Отправить рассылку';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Отправить рассылку ID %s';
