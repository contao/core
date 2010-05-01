
  <h3><?php echo $this->generateLabel(); if ($this->required): ?><span style="color:#ff0000;">*</span><?php endif; echo $this->xlabel; ?></h3>
  <?php echo $this->generateWithError(!$GLOBALS['TL_CONFIG']['oldBeTheme']); ?>
