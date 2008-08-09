
<div class="tl_panel">

<form action="<?php echo $this->request; ?>" id="tl_filter" class="tl_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_filter" />
  <select name="assignedTo" id="assignedTo" class="tl_select"><option value=""><?php echo $this->thAssignedTo; ?></option><?php echo $this->assignedOptions; ?></select>
  <select name="deadline" id="deadline" class="tl_select"><option value=""><?php echo $this->thDeadline; ?></option><?php echo $this->deadlineOptions; ?></select>
  <input type="submit" name="showOnly" id="showOnly" class="tl_submit" alt="narrow results" value="<?php echo $this->filter; ?>" />
</div>
</form>

<form action="<?php echo $this->request; ?>" id="tl_search" class="tl_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_be_search" />
  <select name="tl_field" class="tl_select"><?php echo $this->searchOptions; ?></select>
  <span>=</span>
  <input type="text" name="tl_value" class="tl_text" value="<?php echo $this->keywords; ?>" />
  <input type="submit" name="search" id="search" class="tl_submit" alt="search results" value="<?php echo $this->search; ?>" />
</div>
</form>

<div class="clear"></div>

</div>

<div id="tl_buttons">
<a href="<?php echo $this->createHref; ?>" class="header_new" title="<?php echo $this->createTitle; ?>" accesskey="n" onclick="Backend.getScrollOffset();"><?php echo $this->createLabel; ?></a>
</div>

<div id="tl_taskcenter">
<table cellpadding="0" cellspacing="0" class="sortable" id="taskcenter" summary="">
<thead>
  <tr>
    <th class="col_0 col_first"><?php echo $this->thTitle; ?></th>
    <th class="col_1"><?php echo $this->thAssignedTo; ?></th>
    <th class="col_2"><?php echo $this->thStatus; ?></th>
    <th class="col_3"><?php echo $this->thProgress; ?></th>
    <th class="col_4 asc"><?php echo $this->thDeadline; ?></th>
    <th class="col_5 col_last unsortable">&nbsp;</th>
  </tr>
</thead>
<tbody>
<?php if ($this->tasks): ?>
<?php foreach ($this->tasks as $task): ?>
  <tr class="<?php echo $task['trClass']; ?>">
    <td class="col_0 col_first<?php echo $task['tdClass']; ?>"><?php echo $task['title']; ?></td>
    <td class="col_1<?php echo $task['tdClass']; ?>"><?php echo $task['user']; ?></td>
    <td class="col_2<?php echo $task['tdClass']; ?>"><?php echo $task['status']; ?></td>
    <td class="col_3<?php echo $task['tdClass']; ?>"><?php echo $task['progress']; ?>%</td>
    <td class="col_4<?php echo $task['tdClass']; ?>"><?php echo $task['deadline']; ?></td>
    <td class="col_5 col_last<?php echo $task['tdClass']; ?>"><a href="<?php echo $task['editHref']; ?>" title="<?php echo $task['editTitle']; ?>"><img src="<?php echo $task['editIcon']; ?>" alt="<?php echo $this->editLabel; ?>" /></a><?php if ($task['deleteHref']): ?> <a href="<?php echo $task['deleteHref']; ?>" title="<?php echo $task['deleteTitle']; ?>" onclick="if (!confirm('<?php echo $task['deleteConfirm']; ?>')) return false; Backend.getScrollOffset();"><img src="<?php echo $task['deleteIcon']; ?>" alt="<?php echo $this->deleteLabel; ?>" /></a><?php else: ?> <img src="<?php echo $task['deleteIcon']; ?>" alt="" /><?php endif; ?></td>
  </tr>
<?php endforeach; ?>
<?php else: ?>
  <tr>
    <td colspan="6"><?php echo $this->noTasks; ?></td>
  </tr>
<?php endif; ?>
</tbody>
</table>
</div>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent('domready', function() { new TableSort('taskcenter'); });
//--><!]]>
</script>
