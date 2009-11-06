<?php if (!$this->tableless): ?>
  <tr class="<?php echo $this->rowClass; ?>">
    <td colspan="2"><div class="colspan"><?php echo $this->message; ?></div></td>
  </tr>
<?php else: ?>
  <div class="<?php echo $this->rowClass; ?>">
    <?php echo $this->message; ?> 
  </div>
<?php endif; ?>