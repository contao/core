
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<table cellspacing="0" cellpadding="0" class="calendar" summary="">
<thead>
  <tr>
    <th colspan="2" class="head previous"><?php echo $this->previous; ?></th>
    <th colspan="3" class="head current"><?php echo $this->current; ?></th>
    <th colspan="2" class="head next"><?php echo $this->next; ?></th>
  </tr>
  <tr>
<?php foreach ($this->days as $day): ?>
    <th class="label"><?php echo $day; ?></th>
<?php endforeach; ?>
  </tr>
</thead>
<tbody>
<?php foreach ($this->weeks as $class=>$week): ?>
  <tr class="<?php echo $class; ?>">
<?php foreach ($week as $day): ?>
    <td class="<?php echo $day['class']; ?>">
      <div class="header"><?php echo $day['label']; ?></div>
<?php foreach ($day['events'] as $event): ?>
      <div class="event cal_<?php echo $event['parent']; ?>"><a href="<?php echo $event['href']; ?>" title="<?php echo $event['title']; ?> (<?php if ($event['day']): echo $event['day']; ?>, <?php endif; echo $event['date']; if ($event['time']): ?>, <?php echo $event['time']; endif; ?>)"<?php echo $event['target']; ?>><?php echo $event['link']; ?></a></div>
<?php endforeach; ?>
    </td>
<?php endforeach; ?>
  </tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
<!-- indexer::continue -->
