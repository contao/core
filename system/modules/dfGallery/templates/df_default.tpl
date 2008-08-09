<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?> 
<gallery>
  <config>
    <title><?php echo $this->dfTitle; ?></title>
    <slideshow_interval><?php echo $this->dfInterval; ?></slideshow_interval>
    <pause_slideshow><?php echo $this->dfPause; ?></pause_slideshow>
  </config>
  <albums>
    <album title="<?php echo $this->dfTitle; ?>" description="" type="typolight" url="<?php echo $this->singleSRC; ?>"></album>
  </albums>
  <language>
    <string id="please wait" value="<?php echo $this->pleaseWait; ?>" />
    <string id="loading" value="<?php echo $this->loading; ?>" />
    <string id="previous page" value="<?php echo $this->previous; ?>" />
    <string id="page % of %" value="<?php echo $this->totalPages; ?>" />
    <string id="next page" value="<?php echo $this->next; ?>" />
  </language>
</gallery>