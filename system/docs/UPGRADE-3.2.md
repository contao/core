Contao Open Source CMS Upgrade
==============================

From 3.1 to 3.2
---------------

### button_callback
In favor of  a clearer understanding of the code base, it was decided to add a
BC break in this minor release instead of adding yet another callback.

This is why developers that use the `button_callback` introduced
in Contao 3.0 need to adjust their code to reflect the changes.

Before:

```
$GLOBALS['TL_DCA']['tl_page']['edit'] => array
(
    'buttons_callback' => array
    (
        array('tl_page', 'addAliasButton')
    )
);

public function addAliasButton()
{
    // Add the button
    return '<input...';
}
```


After:

```
$GLOBALS['TL_DCA']['tl_page']['edit'] => array
(
    'buttons_callback' => array
    (
        array('tl_page', 'addAliasButton')
    )
);

public function addAliasButton($arrButtons)
{
    // Can now for example unset the save button
    unset($arrButtons['edit']);

    // Add the button
    $arrButtons['alias'] = '<input...';

    return $arrButtons;
}
```