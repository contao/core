Contao Open Source CMS changelog
================================

Version 3.3.beta1 (2014-XX-XX)
------------------------------

### Improved
Add `$parent` as fourth parameter to the "compileDefinition" hook (see #6697).

### Update
Update TCPDF to version 6.0.062.

### Changed
Enable the maintanance mode by default (see #6758).

### New
Added a markdown content element (see #6052).

### Changed
Merged the "newsarchive" and "newsarchive_empty" templates (see #6647).

### Changed
Make the following functions public static (see #6351):

 - `Controller::getArticle`
 - `Controller::getContentElement`
 - `Controller::getForm`
 - `Controller::getFrontendModule`

### New
Support editing the front end preview page via the "url" parameter (see #6471).

### Improved
Do not combine .js and .css files when running in debug mode (see #6450).

### New
Added a `DcaLoader` class to decouple the DCA loading process (see #5441). DCAs
can now be loaded anywhere using `Controller::loadDataContainer()`.

### Changed
Convert slashes to hyphens in the `standardize()` function (see #6396).

### Improved
Add a `getModel()` method to modules, elements and hybrids (see #6492).

### Improved
Support the "HAVING" command in the `Model\QueryBuilder` class (see #6446).

### Changed
Use class constants for `BackendUser::isAllowed()`.
