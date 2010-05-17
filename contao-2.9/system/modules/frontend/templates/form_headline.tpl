<?php if (!$this->tableless): ?>
  <tr class="<?php echo $this->rowClass; ?>">
    <td colspan="2" class="colspan headline"><?php echo $this->generate(); ?></td>
  </tr>
<?php else: ?>
  <div class="<?php echo $this->rowClass; ?> headline">
    <?php echo $this->generate(); ?> 
  </div>
<?php endif; ?>