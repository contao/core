Contao Open Source CMS API changes
==================================

Version 3.1 to 3.2
------------------

### buttons_callback

The "buttons_callback" was introduced in Contao 3.0 to add custom buttons when
selecting multiple records for editing. In Contao 3.2, this behavior has been
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


### `Model::save()`

In Contao 3.0 and 3.1 it was possible to create two models for the same database
record by passing `true` to the `Model::save()` method. However, this could lead
to a loss of data in certain edge-cases, so we have decided to implement a model
registry to ensure that there is only one model per database record.

The registry, however, requires to use the `clone` command to duplicate models,
therefore the `$blnForceInsert` argment had to be removed. If you have used it
in your custom extension, be sure to adjust the code accordingly.

Usage in Contao 3.0 and 3.1:

```php
$objPage = PageModel::findByPK(1);
$objPage->title = 'New page title';
$objPage->save(true);
```

New usage as of Contao 3.2:

```php
$objPage = PageModel::findByPK(1);
$objCopy = clone $objPage;
$objCopy->title = 'New page title';
$objCopy->save();
```
