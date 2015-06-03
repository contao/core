Contao Open Source CMS
======================

Contao is an Open Source PHP Content Management System for people who want a
professional website that is easy to maintain. Visit the [project website][1]
for more information.


System requirements
-------------------

 * Web server
 * PHP 5.4.0+ with GDlib, DOM, Phar and SOAP
 * MySQL 5.0.3+


Installation
------------

See the [installation chapter][2] of the user's manual.


Documentation
-------------

 * [User's manual][3]
 * [Change log][4]
 * [API changes][5]
 * [Community wiki][6]


License
-------

Contao is licensed under the terms of the LGPLv3. The full license text is
available in the [`system/docs`][7] folder.

Note that the LGPL incorporates the terms and conditions of the GPL, therefore
both licenses are included there. This, however, does not imply that Contao is
dual licensed under both the GPL and the LGPL.


Getting support
---------------

Visit the [support page][8] to learn about the available support options.


Installing from Git
-------------------

We are using [Composer][9] to manage third-party scripts, so after you have
cloned the repository, make sure to install the vendor libraries:

```
git clone https://github.com/contao/core.git
cd core
php composer.phar install --prefer-dist
```


[1]: https://contao.org
[2]: https://docs.contao.org/books/manual/current/en/01-installation/installing-contao.html
[3]: https://docs.contao.org/books/manual/current/
[4]: system/docs/CHANGELOG.md
[5]: system/docs/UPGRADE.md
[6]: http://contaowiki.org
[7]: system/docs
[8]: https://contao.org/support.html
[9]: https://getcomposer.org
