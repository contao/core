<?php if ($this->multiple): ?>

  <h3><?php echo $this->generateLabel(); if ($this->required): ?><span style="color:#ff0000;">*</span><?php endif; echo $this->wizard; ?></h3><?php endif; ?> 
  <?php echo $this->generateWithError(); ?>
