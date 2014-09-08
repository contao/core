Contao Open Source CMS changelog
================================

Version 3.4.0-beta1 (2014-10-XX)
--------------------------------

### New
Added `widget-*` CSS classes to front end form fields (see #7041).

### Improved
Make the loading order of the style sheets configurable (see #6937).

### Removed
Remove the `rel="author` support (see #7291).

### New
Added `$item['isTrail']` to the navigation menu templates (see #7096).

### Improved
Handle `data-` and `ng-` attributes in `Widget::addAttributes()` (see #7095).

### Changed
Add the class "tableless" to the `member_` templates (see #7207).

### Improved
Added the `|async` flag to `$GLOBALS['TL_JAVASCRIPT']` (see #7172).

### New
Added the "link_name" insert tag (see #7218).

### Improved
Simplify the "member_grouped" template (see #7015).

### Changed
Make the front controller classes overwritable.
