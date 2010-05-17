
<div class="w50">
  <h3><?php echo $this->generateLabel(); echo $this->xlabel; ?></h3>
  <?php echo $this->generateWithError(!$GLOBALS['TL_CONFIG']['oldBeTheme']); ?> 
</div>
<div class="w50">
  <h3><?php echo $this->generateConfirmationLabel(); ?></h3>
  <?php echo $this->generateConfirmation(); ?> 
</div>