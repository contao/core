
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<table cellspacing="0" cellpadding="0" summary="Image gallery">
<tbody>
<?php foreach ($this->body as $class=>$row): ?>
<tr class="<?php echo $class; ?>">
<?php foreach ($row as $col): ?>
<?php if ($col['hasImage']): ?>
  <td class="<?php echo $col['class']; ?>" style="width:<?php echo $col['colWidth']; ?>;"><div class="image_container"<?php if ($col['margin']): ?> style="<?php echo $col['margin']; ?>"<?php endif; ?>><?php if ($col['link']): ?><a href="<?php echo $col['link']; ?>" title="<?php echo $col['alt']; ?>"><?php endif; ?><img src="<?php echo $col['src']; ?>"<?php echo $col['imgSize']; ?> alt="<?php echo $col['alt']; ?>" /><?php if ($col['link']): ?></a><?php endif; if ($col['caption']): ?><div class="caption"><?php echo $col['caption']; ?></div><?php endif; ?></div></td>
<?php else: ?>
  <td class="<?php echo $col['class']; ?> empty">&nbsp;</td>
<?php endif; ?>
<?php endforeach; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php echo $this->pagination; ?>

</div>
