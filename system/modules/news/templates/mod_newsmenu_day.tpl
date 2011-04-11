
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<table class="minicalendar">
<thead>
  <tr>
    <th class="head previous"><a href="<?php echo $this->prevHref; ?>" title="<?php echo $this->prevTitle; ?>"><?php echo $this->prevLabel; ?></a></th>
    <th colspan="5" class="head current"><?php echo $this->current; ?></th>
    <th class="head next"><a href="<?php echo $this->nextHref; ?>" title="<?php echo $this->nextTitle; ?>"><?php echo $this->nextLabel; ?></a></th>
  </tr>
  <tr>
<?php foreach ($this->days as $day): ?>
    <th class="label"><?php echo utf8_substr($day, 0, 2); ?></th>
<?php endforeach; ?>
  </tr>
</thead>
<tbody>
<?php foreach ($this->weeks as $class=>$week): ?>
  <tr class="<?php echo $class; ?>">
<?php foreach ($week as $day): ?>
<?php if ($day['href']): ?>
    <td class="<?php echo $day['class']; ?>"><a href="<?php echo $day['href']; ?>" title="<?php echo $day['title']; ?>"><?php echo $day['label']; ?></a></td>
<?php else: ?>
    <td class="<?php echo $day['class']; ?>"><?php echo $day['label']; ?></td>
<?php endif; ?>
<?php endforeach; ?>
  </tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
<!-- indexer::continue -->
