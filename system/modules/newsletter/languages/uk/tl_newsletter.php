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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Тема';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Будь ласка, введіть тему розсилки.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Аліас каналу розсилки';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Аліас каналу розсилки є унікальною назвою, яка може бути використана для виклику компоненту замість ID';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML текст';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Будь ласка, введіть HTML текст розсилки. Ви можете використати запис <em>##email##</em> для того, щоб вставити адресу електронної пошти користувача. Використайте в листі підпис та вкажіть адрес електронної пошти де користувачі зможуть відмовитись від розсилки.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Текст';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Будь ласка, введіть текст розсилки. Ви можете використати запис <em>##email##</em> для того, щоб вставити адресу електронної пошти користувача.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Додати файли';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Додати файли до листа розсилки.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Прикріплені файли';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Виберіть, будь ласка, файли котрі необхідно додати до листа.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Шаблон листа';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Будь ласка, виберіть шаблон листа (група шаблонів <em>mail_</em>)';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Відправити як текст';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Якщо Ви виберете дану опцію то всі HTML теги буде вилучено із листа.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Зовнішні зображення';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Не вставляти в HTML код розсилки зображення.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Ім\'я відправника';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Тут ви можете вказати ім\'я відправника.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'E-mail відправника';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Якщо Ви не вкажете адресу відправника то по замовчуванню буде використано адресу адміністратора сайту.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Листів за один цикл';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'При великій кількості листів, процес відправки листів розбивається на декілька циклів.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Час очікування в секундах';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Деякі поштові сервери обмежують кількість повідомлень електронної пошти, які можуть бути спрямовані в хвилину. Тут ви можете змінити таймаут між кожним циклом в секундах.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Зміщення';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'У разі якщо процес відправки був перерваний, ви можете ввести значення зміщення для продовження з цієї позиції. Перевірити кількість відправлених повідомлень можливо в файлі <em>system/logs/newsletter_*.log</em>. Наприклад, якщо було відправлено 120 повідомлень, то введіть в поле число "120" для того, щоб відправка повідомлень розпочалася зі 121 отримувача (нумерація розпочинається з нуля).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Надіслати зразок до';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Надіслати зразок на вказану електронну пошту.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Назва та тема';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML текст';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Текст';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Прикріплені файли';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Налаштування шаблону';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Експертні налаштування';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Відправити';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Відправити %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Ще не відправлено';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Дата відправлення';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Лист розіслано %s користувачам.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s невірних e-mail адресів було відключено.';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Немає активних абонентів на каналі.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Від';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Прикріплені файли';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Відправити попередній перегляд';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Ви дійсно хочете відправити розсилку?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Нова розсилка';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Створити нову розсилку';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Детальна інформація розсилки';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Детальна інформація розсилки ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Редагувати розсилку';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Редагувати розсилку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Копіювати розсилку';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Копіювати розсилку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Перемістити розсилку';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Перемістити розсилку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Видалити розсилку';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Видалити розсилку ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Редагувати канал розсилки';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Редагувати налаштування каналу розсилки';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Вставити в канал розсилки';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Вставити після розсилки ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Розіслати повідомлення';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Розіслати повідомлення розсилки ID %s';
