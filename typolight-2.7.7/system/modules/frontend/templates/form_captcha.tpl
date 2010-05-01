<?php if (!$this->tableless): ?>
  <tr class="<?php echo $this->rowClass; ?>">
    <td class="col_0 col_first"><?php echo $this->generateLabel(); ?><?php if ($this->mandatory): ?><span class="mandatory">*</span><?php endif; ?></td>
    <td class="col_1 col_last"><?php echo $this->generateWithError(); ?> <?php echo $this->generateQuestion(); ?></td>
  </tr>
<?php else: ?>
  <?php echo $this->generateLabel(); ?> 
  <?php echo $this->generateWithError(); ?> <?php echo $this->generateQuestion(); ?><br />
<?php endif; ?>