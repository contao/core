Contao Open Source CMS API changes
==================================

Version 3.4 to 3.5
------------------

### PHP version

The minimum PHP version is raised from 5.3.7 to 5.4.0.


### DataContainer

The `DataContainer` class is now abstract, which however should not matter,
because the contructor has been protected ever since. Also, the methods
`getPalette()` and `save()` have been added as abstract methods, which need to
be implemented by all child classes (already the case in the `DC_` classes).


Version 3.3 to 3.4
------------------

### PHP version

We are using a new Blowfish mode that deals with potential high-bit attacks in
Contao 3.4, therefore the minimum PHP version is raised from 5.3.2 to 5.3.7.


Version 3.2 to 3.3
------------------

### processFormData hook

The "processFormData" hook now passes `$arrSubmitted` as first argument instead
of `$_SESSION['FORM_DATA']`. This is actually a bug fix, because so far the hook
passed everything that was stored in the session instead of just the fields of
the current form.

However, the change implies a backwards compatibility break, because the former
implementation allowed to add form fields by modifying `$arrData`:

```php
public function myProcessFormData(&$arrData, …) {
    $arrData['new_field'] = 'new_value';
}
```

In version 3.3, you have to add to `$_SESSION['FORM_DATA']` instead:

```php
public function myProcessFormData($arrData, …) {
    $_SESSION['FORM_DATA']['new_field'] = 'new_value';
}
```


Version 3.1 to 3.2
------------------

### Controller::addImageToTemplate()

Before Contao 3.2.2, the `addImageToTemplate()` method would override the "href"
property with the image URL or link target even if the property was set already.
This was causing issues in the event templates, which use the "href" property to
store the event details URL.

Therefore, if the "href" property is set, the `addImageToTemplate()` method will
store the image URL or link target in the "imageHref" property instead. However,
this requires to adjust custom `event_*` templates which render the event image.
Note that this does not affect the core templates.

Usage before version 3.2.2:

```php
<h2><a href="<?php echo $this->href; ?>Event title</a></h2>
<p><a href="<?php echo $this->href; ?>"><img src="..."></a></p>
```

New usage as of version 3.2.2:

```php
<h2><a href="<?php echo $this->href; ?>Event title</a></h2>
<p><a href="<?php echo $this->imageHref; ?>"><img src="..."></a></p>
```


### buttons_callback

The "buttons_callback" was introduced in Contao 3.0 to add custom buttons when
selecting multiple records for editing. In Contao 3.2 this behavior has been
enhanced in a way that not only the selection buttons but any button set can be
altered as well as existing buttons can be removed.

We have decided to use the existing "buttons_callback" for this, because it does
not make sense to add another hook which essentially does the same. However,
this decision implies a backwards compatibilty break, since the API of the hook
has had to change as described below.

Usage in Contao 3.0 and 3.1:

```php
$GLOBALS['TL_DCA']['tl_page']['edit'] => array
(
    'buttons_callback' => array
    (
        array('tl_page', 'addAliasButton')
    )
);

/**
 * Button callback
 * @return string
 */
public function addAliasButton()
{
    return '<input...';
}
```

New usage as of Contao 3.2:

```php
// Add a button to the "select multiple" screen
$GLOBALS['TL_DCA']['tl_page']['select'] => array
(
    'buttons_callback' => array
    (
        array('tl_page', 'addAliasButton')
    )
);

// Add a button to the "edit record(s)" screen
$GLOBALS['TL_DCA']['tl_page']['edit'] => array
(
    'buttons_callback' => array
    (
        array('tl_page', 'addAliasButton')
    )
);

/**
 * Button callback
 * @param array
 * @return array
 */
public function addAliasButton($arrButtons)
{
    // Unset the save button
    unset($arrButtons['edit']);

    // Add a custom "alias" button
    $arrButtons['alias'] = '<input … >';

    // Return the array of buttons
    return $arrButtons;
}
```

In case you have been using the "buttons_callback", please make sure to adjust
your extension accordingly.


### Model::save()

In Contao 3.0 and 3.1 it was possible to create two models for the same database
record by passing `true` to the `Model::save()` method. However, this could lead
to a loss of data in certain edge-cases, so we have decided to implement a model
registry to ensure that there is only one model per database record.

The registry, however, requires to use the `clone` command to duplicate models,
therefore the `$blnForceInsert` argment had to be removed. If you have used it
in your custom extension, be sure to adjust the code accordingly.

Usage in Contao 3.0 and 3.1:

```php
$objPage = PageModel::findByPk(1);
$objPage->title = 'New page title';
$objPage->save(true);
```

New usage as of Contao 3.2:

```php
$objPage = PageModel::findByPk(1);
$objCopy = clone $objPage;
$objCopy->title = 'New page title';
$objCopy->save();
```
