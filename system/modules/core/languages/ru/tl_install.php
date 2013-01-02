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

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Мастер установки Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Вход в мастер установки';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Мастер установки заблокирован';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'В целях безопасности, мастер установки Contao заблокирован после ввода, более чем трех раз подряд, неправильного пароля. Для разблокирования, откройте локальный файл конфигурации и установите в поле <em>installCount</em> значение <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Пароль';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Введите пароль для входа в мастер установки. Пароль для входа в мастер установки не связан с паролем для входа в панель управления Contao.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Пароль для входа в мастер установки';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Для дополнительной защиты, вы можете вставить <strong>exit;</strong> в файл <strong>contao/install.php</strong> или удалить его с сервера. В этом случае вам придется изменять настройки вручную, прямо в конфигурационном файле.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Генерация ключа шифрования';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Ключ используется для шифрования данных. Обратите внимание, что зашифрованные данные могут быть расшифрованы только с тем же самым ключом! Поэтому, запишите ключ и не изменяйте его, если имеются зашифрованные данные. Оставьте поле пустым, для генерации случайного ключа.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Проверка соединения с базой данных';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Введите настройки соединения с базой данных.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Сопоставление';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Подробную информацию см. в <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" target="_blank">Руководство MySQL</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Обновление таблиц базы данных';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Обратите внимание! Обновление таблиц базы данных при помощи мастера установки Contao, тестировалось только с MySQL и MySQLi драйверами. Если вы используете другую базу данных (напр., Oracle), вы можете установить или обновить базу данных вручную.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Импорт шаблона';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Вы можете импортировать файл содержащий предварительно настроенный пример веб-сайта в формате <em>.sql</em> из каталога <em>templates</em>. <strong>Внимание! Все существующие данные будут удалены!</strong> Если хотите импортировать тему веб-сайта, то используйте менеджер тем в панели управления Contao.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Создание учетной записи администратора';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Если вы импортировали пример веб-сайта, предоставляемый в комплекте с Contao, то логин администратора будет <strong>k.jones</strong>, а пароль <strong>kevinjones</strong>. Смотрите пример веб-сайта (внешний интерфейс) для получения дополнительной информации.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Поздравляем!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Теперь войдите в <a href="contao/">панель управления Contao</a> и проверьте настройки системы. После чего откройте ваш веб-сайт (внешний интерфейс) и убедитесь, что Contao работает правильно.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Изменение файлов по FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Ваш сервер не поддерживает доступ к файлам с помощью PHP, скорее всего, PHP работает как модуль Apache под другим пользователем. Поэтому, введите ваш логин к FTP, чтобы Contao смог изменять файлы по FTP-протоколу (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Принять лицензию';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Вход в панель управления Contao';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Введите пароль для предотвращения несанкционированного доступа!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Пользовательский пароль не был установлен.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Сохранить пароль';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Создайте ключ шифрования!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Ключ шифрования должен быть как минимум из 12 символов!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Ключ шифрования создан.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Генерация ключа шифрования';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Создать или сохранить ключ';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Подключение к базе данных установлено.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Нет подключения к базе данных!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Драйвер';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Хост';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Пользователь';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'База данных';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Постоянное соединение';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Кодировка';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Сопоставление';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Порт';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Сохранить настройки';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Изменение сопоставления окажет эффект на все таблицы с префиксом <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'База данных не обновлена!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'База данных обновлена.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Обновить базу данных';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Изменение сортировки';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Вероятно, вы обновляете Contao до версии %s. Если это так, то для обеспечения целостности данных вам <strong>необходимо запустить обновление</strong> Contao до версии %s!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Выполнить обновление версии %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Запуск новой версии %s - шаг %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Ошибка импорта! Совместима ли структура базы данных и текущий шаблон с используемой версией Contao?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Выберите файл примера веб-сайта!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Пример веб-сайта импортирован %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Все существующие данные будут удалены!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Примеры';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Не обрезать таблицы в базе данных';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Импорт примера веб-сайта';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Все существующие данные будут удалены! Вы хотите продолжить?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Заполните все поля для создания учетной записи администратора!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Учетная запись администратора создана.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Создать учетную запись администратора';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Вы успешно установили Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP-сервер';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Относительный путь к каталогу Contao (напр., <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'Логин FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'Пароль FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Безопасное соединение';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Соединение через FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP-порт';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Сохранить настройки';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Не удалось подключиться к FTP-серверу %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Не верный логин "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Не удалось найти каталог Contao %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Сконфигурированный каталог файлов не существует!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Вы переименовали каталог <strong>tl_files</strong> в <strong>files</strong>? Вы не можете просто переименовать каталог, потому, что все ссылки на файлы в базе данных и стили будут по-прежнему указывать на старое расположение. Если хотите переименовать каталог, сделайте это после обновления до версии 3 и настройте базу данных при помощи следующего скрипта: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Создать новые таблицы';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Добавить новые колонки';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Измененить существующие колонки';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Очистить существующие колонки';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Очистить существующие таблицы';
