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

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Менеджер установки Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Вхід в менеджер установки';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Менеджер установки заблоковано';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'В цілях безпеки менеджер установки Contao заблоковано, у зв\'язку із некоректним введенням паролю більш ніж 3 рази. Для розблокування відредагуйте Ваш конфігураційний файл та встановіть значення <em>installCount</em> рівним <em>0</em>';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Пароль';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Введіть, будь ласка, пароль для менеджеру встановлення. Зверніть увагу, що пароль для менеджера встановлення та пароль для входу на адміністративну панель (бакенд) не є одне і теж.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Пароль для менеджера встановлення';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Для посилення безпеки після встановлення Ви можете переіменувати або видалити файл <strong>contao/install.php</strong>';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Створити ключ шифрування даних';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Цей ключ використовується для зберігання зашифрованих даних. Зверніть увагу, що зашифровані дані можуть бути розшифровані тільки з цим ключем! Тому не змінюйте його, якщо дані вже зашифровані . Залиште поле порожнім для автоматичної генерації ключа.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Перевірити зв\'язок із базою даних.';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Введіть, будь ласка, параметри доступу до бази даних.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Порівняння таблиці кодування (Collation)';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Для отримання детальної інформації перегляньте <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">MySQL manual</a>';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Оновити таблиці бази даних';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Зверніть увагу, що менеджер оновлення відтестовано тільки із драйверами MySQL та MySQLi. Якщо Ви використовуєте іншу базу даних (напр. Oracle) Ви повинні оновити її самостійно.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Імпортувати шаблон';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Виберіть файл із розширенням <em>.sql</em> із каталога <em>templates</em> з попередньо налаштованим сайтом. Поточні данні будуть знищені! Для того, щоб імпортувати тільки тему, то необхідно перейти до менеджеру тем.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Створити користувача з правами адміністратора.';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Якщо Ви імпортували сайт зразка, то логін користувача буде встановлено <strong>k.jones</strong>, а пароль <strong>kevinjones</strong>.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Вітаємо!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Зараз увійдіть, будь ласка, в <a href="contao/index.php">адміністративну панель Contao</a> та перевірте налаштування системи. Потім перейдіть на Ваш сайт та перевірте чи коректно працює Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Змінити файли через FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Сервер не підтримує доступ до файлів за допомогою PHP; скоріш за все PHP працює як модуль Apache під різними користувачами. Тому, будь ласка, введіть дані налаштування FTP, щоб Contao змогла редагувати дані в безпечному режимі (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Прийняти умови ліцензії';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Логін для входу в панель управління Contao.';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Будь ласка, введіть базовий пароль для запобігання неавторизованого доступу!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Базовий пароль успішно змінено.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Зберегти пароль';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Створіть, будь ласка, ключ шифрування даних!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Ключ шифрування даних повинен містити не менше 12 символів!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Ключ шифрування даних створено.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Згенерувати ключ шифрування даних';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Згенерувати або зберегти ключ';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'З\'єднання із базою даних встановлено!';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Неможливо під\'єднатись до бази даних!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Драйвер';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Хост';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Користувач';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'База даних';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Постійне з\'єднання';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Кодування';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Порівняння таблиці кодування (Collation)';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Номер порта';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Зберегти налаштування бази даних';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Зміна порівняння таблиці кодування застосується до усіх таблиць із префіксом <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Базу даних не оновлено!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Базу даних оновлено.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Оновити базу даних';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Зміна параметрів сортування';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Ви оновлюєте систему Contao до версії %s. Якщо так, то <strong>необхідно запустити оновлення системи до версії %s</strong> для забезпечення цілосності даних!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Розпочати оновлення системи до версіі %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Запуск оновлення версії %s - шаг %s';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Виберіть, будь ласка, файл шаблону.';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Шаблони імпортовано до %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Усі існуючі дані будуть видалені.';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Шаблони';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Не очищувати таблиці';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Імпортувати шаблон';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Усі існуючі дані будуть видалені. Ви дійсно бажаєте продовжити?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Введіть, будь ласка, дані для створення профілю адміністратора.';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Профіль адміністратора створено.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Створити профіль адміністратора.';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Contao успішно встановлено!';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP хост';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Відносний шлях до каталогу із Contao (наприклад <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP користувач';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP пароль';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Шифроване з\'єднання';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Під\'єднатись до FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP порт';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Зберегти налаштування FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Неможливо під\'єднатись до FTP сервера %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Неможливо увійти як "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Неможливо визначити місцезнаходження каталогу Contao %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Конфігурований каталог файлів не існує!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Ви перейменували каталог <strong>tl_files</strong> в <strong>files</strong>? Ви не можете перейменувати каталог, тому, що всі посилання на файли в базі даних і стилі будуть як і раніше вказувати на старе розташування. Якщо хочете перейменувати каталог, зробіть це після оновлення до версії 3 і налаштуйте базу даних за допомогою наступного скрипта: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Створити нові таблиці';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Додати нові колонки';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Замінити існуючі колонки';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Видалити існуючі колонки';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Видалити існуючі таблиці';
