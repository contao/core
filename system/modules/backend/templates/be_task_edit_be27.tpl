
<div id="tl_buttons">
<a href="typolight/main.php?do=tasks" class="header_back" title="<?php echo $this->goBack; ?>" accesskey="b" onclick="Backend.getScrollOffset();"><?php echo $this->goBack; ?></a>
</div>

<h2 class="sub_headline"><?php echo $this->headline; ?></h2>

<form action="<?php echo $this->request; ?>" id="tl_tasks" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_tasks" />

<fieldset id="pal_title_legend" class="tl_tbox block<?php echo $this->titleClass; ?>">
<legend onclick="AjaxRequest.toggleFieldset(this, 'title_legend', 'tl_tasks')"><?php echo $this->titleLabel; ?></legend>
<?php if ($this->advanced): ?>
<div class="w50">
  <h3><?php echo $this->title->generateLabel(); ?></h3>
  <?php echo $this->title->generateWithError(); ?> 
<?php if ($this->title->help): ?>
  <p class="tl_help tl_tip"><?php echo $this->title->help; ?></p>
<?php endif; ?>
</div>
<div class="w50 wizard">
  <h3><?php echo $this->deadline->generateLabel(); ?></h3>
  <?php echo $this->deadline->generateWithError(); ?> 
<?php if ($this->deadline->help): ?>
  <p class="tl_help tl_tip"><?php echo $this->deadline->help; ?></p>
<?php endif; ?>
  <script type="text/javascript">
  <!--//--><![CDATA[//><!--
  window.addEvent('domready', function() { <?php echo $this->deadline->datepicker; ?> });
  //--><!]]>
  </script>
</div>
<?php else: ?>
<div>
  <h3><?php echo $this->title; ?></h3>
  <p class="tl_deadline"><?php echo $this->deadlineLabel; ?>: <?php echo $this->deadline; ?></p>
</div>
<?php endif; ?>
</fieldset>

<fieldset id="pal_assign_legend" class="tl_box block<?php echo $this->assignClass; ?>">
<legend onclick="AjaxRequest.toggleFieldset(this, 'assign_legend', 'tl_tasks')"><?php echo $this->assignLabel; ?></legend>
<div class="w50">
  <h3><?php echo $this->assignedTo->generateLabel(); ?></h3>
  <?php echo $this->assignedTo->generateWithError(); ?> 
<?php if ($this->assignedTo->help): ?>
  <p class="tl_help tl_tip"><?php echo $this->assignedTo->help; ?></p>
<?php endif; ?>
</div>
<div class="w50 m12">
  <?php echo $this->notify->generateWithError(); ?> 
<?php if ($this->notify->help): ?>
  <p class="tl_help tl_tip"><?php echo $this->notify->help; ?></p>
<?php endif; ?>
</div>
</fieldset>

<fieldset id="pal_status_legend" class="tl_box block<?php echo $this->statusClass; ?>">
<legend onclick="AjaxRequest.toggleFieldset(this, 'status_legend', 'tl_tasks')"><?php echo $this->statusLabel; ?></legend>
<div class="w50">
  <h3><?php echo $this->status->generateLabel(); ?></h3>
  <?php echo $this->status->generateWithError(); ?> 
<?php if ($this->status->help): ?>
  <p class="tl_help tl_tip"><?php echo $this->status->help; ?></p>
<?php endif; ?>
</div>
<div class="w50">
  <h3><?php echo $this->progress->generateLabel(); ?></h3>
  <?php echo $this->progress->generateWithError(); ?> 
<?php if ($this->progress->help): ?>
  <p class="tl_help tl_tip"><?php echo $this->progress->help; ?></p>
<?php endif; ?>
</div>
<div class="clr">
  <h3><?php echo $this->comment->generateLabel(); ?></h3>
  <?php echo $this->comment->generateWithError(); ?> 
<?php if ($this->comment->help): ?>
  <p class="tl_help tl_tip"><?php echo $this->comment->help; ?></p>
<?php endif; ?>
</div>
</fieldset>

<fieldset id="pal_history_legend" class="tl_box tl_history block<?php echo $this->historyClass; ?>">
<legend onclick="AjaxRequest.toggleFieldset(this, 'history_legend', 'tl_tasks')"><?php echo $this->historyLabel; ?></legend>
  <table cellpadding="0" cellspacing="0" summary="">
<?php foreach ($this->history as $row): ?>
    <tr class="odd">
      <td class="<?php echo $row['class']; ?>"><strong><?php echo $this->dateLabel; ?>:</strong> <?php echo $row['date']; ?></td>
      <td class="<?php echo $row['class']; ?>"><strong><?php echo $this->createdByLabel; ?>:</strong>  <?php echo $row['creator']; ?></td>
      <td class="<?php echo $row['class']; ?>"><strong><?php echo $this->assignedToLabel; ?>:</strong>  <?php echo $row['name']; ?></td>
      <td class="<?php echo $row['class']; ?>"><strong><?php echo $this->statusLabel; ?>:</strong>  <?php echo $row['status']; ?></td>
      <td class="<?php echo $row['class']; ?>"><strong><?php echo $this->progressLabel; ?>:</strong>  <?php echo $row['progress']; ?>%</td>
    </tr>
    <tr class="even">
      <td class="<?php echo $row['class']; ?>" colspan="5"><?php echo $row['comment']; ?></td>
    </tr>
<?php endforeach; ?>
  </table>
</fieldset>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="<?php echo $this->submit; ?>" />
</div>

</div>
</form>
