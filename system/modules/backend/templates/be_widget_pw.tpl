
  <h3><?php echo $this->generateLabel(); if ($this->required): ?><span style="color:#ff0000;">*</span><?php endif; echo $this->wizard; ?></h3>
  <?php echo $this->generateWithError(); ?> 
  <h3><?php echo $this->generateConfirmationLabel(); ?></h3>
  <?php echo $this->generateConfirmation(); ?>
