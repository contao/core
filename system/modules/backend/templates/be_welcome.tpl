
<h1 id="tl_welcome"><?php echo $this->welcome; ?></h1>

<div id="tl_soverview">
<?php if ($this->messages): ?>

<div id="tl_messages">
  <h2><?php echo $this->systemMessages; ?></h2>
<?php if ($this->tasksDue): ?>
  <div class="tl_task_due"><?php echo $this->tasksDue; ?></div>
<?php endif; ?>
<?php if ($this->tasksNew): ?>
  <div class="tl_task_new"><?php echo $this->tasksNew; ?></div>
<?php endif; ?>
<?php if ($this->tasksCur): ?>
  <div class="tl_task_reg"><?php echo $this->tasksCur; ?></div>
<?php endif; ?>
<?php if ($this->update): ?>
  <div class="tl_update"><?php echo $this->update; ?></div>
<?php endif; ?>
</div>
<?php endif; ?>

<table cellspacing="0" cellpadding="0" id="tl_shortcuts" summary="shortcut overview">
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
