
<h1 id="tl_welcome"><?php echo $this->welcome; ?></h1>

<div id="tl_soverview">

<div id="tl_messages">
  <h2><?php echo $this->systemMessages; ?></h2>
<?php if ($this->tasksDue): ?>
  <p class="tl_task_due"><a href="typolight/main.php?do=tasks"><?php echo $this->tasksDue; ?></a></p>
<?php endif; ?>
<?php if ($this->tasksNew): ?>
  <p class="tl_task_new"><a href="typolight/main.php?do=tasks"><?php echo $this->tasksNew; ?></a></p>
<?php endif; ?>
<?php if ($this->tasksCur): ?>
  <p class="tl_task_reg"><a href="typolight/main.php?do=tasks"><?php echo $this->tasksCur; ?></a></p>
<?php endif; ?>
<?php if ($this->update): ?>
  <p class="tl_update"><a href="typolight/main.php?do=maintenance"><?php echo $this->update; ?></a></p>
<?php endif; ?>
<?php if ($this->lastLogin): ?>
  <p class="tl_update"><?php echo $this->lastLogin; ?></p>
<?php endif; ?>
</div>

<table cellspacing="0" cellpadding="0" id="tl_shortcuts" summary="Shortcut overview">
<?php foreach ($this->arrShortcuts as $arrShortcut): ?>
<?php if ($arrShortcut[0] == 'colspan'): ?>
  <tr>
    <td colspan="2" class="headline"><div><?php echo $arrShortcut[1]; ?></div></td>
  </tr>
<?php else: ?>
  <tr>
    <td class="col_1"><?php echo $arrShortcut[0]; ?></td>
    <td class="col_2"><?php echo $arrShortcut[1]; ?></td>
  </tr>
<?php endif; endforeach; ?>
</table>

<div id="tl_moverview">
<?php foreach ($this->arrGroups as $strGroup=>$arrModules): ?>

<h2><?php echo $strGroup; ?></h2>
<?php foreach ($arrModules as $strModule=>$arrModule): ?>

<div class="tl_module_desc">
<h3><a href="<?php echo $this->script; ?>?do=<?php echo $strModule; ?>" class="navigation <?php echo $strModule; ?>"<?php if ($arrModule['icon']): ?> style="background-image:url('<?php echo $arrModule['icon']; ?>')"<?php endif; ?>><?php echo $arrModule['name']; ?></a></h3>
<?php echo $arrModule['description']; ?> 
</div>
<?php endforeach; endforeach; ?>
</div>

</div>
