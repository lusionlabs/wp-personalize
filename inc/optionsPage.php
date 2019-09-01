<?php 
$displayName 			= WWP_PLUGIN_DISPLAY_NAME;
$titleName				= WWP_PLUGIN_TITLE_NAME;
$createNetworkTxt = "";
?>

<div id="wpp-dialog-confirm" title="<?php _e('Info', WWP_PLUGIN_LANG_DOMAIN); ?>" style="display: none;">
  <p>
  	<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  	<div id="wpp-dialog-confirm-body"></div>
  </p>
</div>

<h3 class="wpp-section-top-title">
	<i class="whhg-code wpp-section-top-icon"></i>
	<?php echo $displayName; ?>
</h3>

<?php 
include_once('wpPersonlizeEditor.php'); 
?>