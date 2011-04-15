<?php if (!$this->tableless): ?>
  <tr class="<?php echo $this->rowClass; ?>">
    <td colspan="2" class="colspan explanation"><?php echo $this->generate(); ?></td>
  </tr>
<?php else: ?>
  <div class="<?php echo $this->rowClass; ?> explanation">
    <?php echo $this->generate(); ?> 
  </div>
<?php endif; ?>