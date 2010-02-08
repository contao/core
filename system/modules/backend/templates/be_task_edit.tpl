
<div id="tl_buttons">
<a href="typolight/main.php?do=tasks" class="header_back" title="<?php echo $this->goBack; ?>" accesskey="b" onclick="Backend.getScrollOffset();"><?php echo $this->goBack; ?></a>
</div>

<h2 class="sub_headline"><?php echo $this->headline; ?></h2>

<form action="<?php echo $this->request; ?>" id="tl_tasks" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_tasks" />

<div class="tl_tbox block">
<?php if ($this->advanced): ?>
  <h3><?php echo $this->title->generateLabel(); ?></h3>
  <?php echo $this->title->generateWithError(); ?> 
<?php if ($this->title->help): ?>
  <p class="tl_help"><?php echo $this->title->help; ?></p>
<?php endif; ?>
  <h3><?php echo $this->deadline->generateLabel(); ?></h3>
  <?php echo $this->deadline->generateWithError(); ?> 
<?php if ($this->deadline->help): ?>
  <p class="tl_help"><?php echo $this->deadline->help; ?></p>
<?php endif; ?>
  <script type="text/javascript">
  <!--//--><![CDATA[//><!--
  window.addEvent('domready', function() { <?php echo $this->deadline->datepicker; ?> });
  //--><!]]>
  </script>
<?php else: ?>
  <h3><?php echo $this->title; ?></h3>
  <p class="tl_deadline"><?php echo $this->deadlineLabel; ?>: <?php echo $this->deadline; ?></p>
<?php endif; ?>
</div>

<div class="tl_box block">
  <h3><?php echo $this->assignedTo->generateLabel(); ?></h3>
  <?php echo $this->assignedTo->generateWithError(); ?> 
<?php if ($this->assignedTo->help): ?>
  <p class="tl_help"><?php echo $this->assignedTo->help; ?></p>
<?php endif; ?>
  <?php echo $this->notify->generateWithError(); ?> 
<?php if ($this->notify->help): ?>
  <p class="tl_help"><?php echo $this->notify->help; ?></p>
<?php endif; ?>
</div>

<div class="tl_box block">
  <h3><?php echo $this->status->generateLabel(); ?></h3>
  <?php echo $this->status->generateWithError(); ?> 
<?php if ($this->status->help): ?>
  <p class="tl_help"><?php echo $this->status->help; ?></p>
<?php endif; ?>
  <h3><?php echo $this->progress->generateLabel(); ?></h3>
  <?php echo $this->progress->generateWithError(); ?> 
<?php if ($this->progress->help): ?>
  <p class="tl_help"><?php echo $this->progress->help; ?></p>
<?php endif; ?>
  <h3><?php echo $this->comment->generateLabel(); ?></h3>
  <?php echo $this->comment->generateWithError(); ?> 
<?php if ($this->comment->help): ?>
  <p class="tl_help"><?php echo $this->comment->help; ?></p>
<?php endif; ?>
</div>

<div class="tl_box tl_history block">
  <h3><?php echo $this->historyLabel; ?></h3>
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
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="<?php echo $this->submit; ?>" />
</div>

</div>
</form>
