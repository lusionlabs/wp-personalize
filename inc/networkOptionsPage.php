<?php
$displayName      = WWP_PLUGIN_DISPLAY_NAME;
$titleName        = WWP_PLUGIN_TITLE_NAME . ' (' . __( 'Network', WWP_PLUGIN_LANG_DOMAIN ) . ')';
$titleSetName     = WWP_PLUGIN_TITLE_NAME . ' (' . __( 'Single Site Settings', WWP_PLUGIN_LANG_DOMAIN ) . ')';
$createNetworkTxt = '(' . __( 'Network', WWP_PLUGIN_LANG_DOMAIN ) . ')';
?>

<div id="wpp-dialog-confirm" title="<?php _e( 'Info', WWP_PLUGIN_LANG_DOMAIN ); ?>" style="display: none;">
  <p>
	  <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
	  <div id="wpp-dialog-confirm-body"></div>
  </p>
</div>

<h3 class="wpp-section-top-title">
	<i class="whhg-code wpp-section-top-icon"></i>
	<?php echo $displayName; ?>
</h3>

<div class="wpp-section-settings-container">
	<table class="wpp-section-table">
		<thead>
			<tr>
				<th><?php echo $titleSetName; ?></th>
				<th><?php _e( 'Settings', WWP_PLUGIN_LANG_DOMAIN ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<?php _e( 'Locations Choosable', WWP_PLUGIN_LANG_DOMAIN ); ?>
				</td>
				<td>
					<?php foreach ( $locationArr as $key => $value ) : ?>
						<?php $locationChecked = ( ! isset( $scriptSetArr['location'][ $key ] ) or $scriptSetArr['location'][ $key ] == 'true' ) ? ' checked' : ''; ?>
						<input name="loccaion[]" class="check-location" type="checkbox" value="<?php echo $key; ?>"<?php echo $locationChecked; ?> />
						<?php echo $value; ?><br/>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Types Choosable', WWP_PLUGIN_LANG_DOMAIN ); ?>
				</td>
				<td>
					<?php foreach ( $typeArr as $key => $value ) : ?>
						<?php $typeChecked = ( ! isset( $scriptSetArr['type'][ $key ] ) or $scriptSetArr['type'][ $key ] == 'true' ) ? ' checked' : ''; ?>
						<input name="type[]" class="check-type" type="checkbox" value="<?php echo $key; ?>"<?php echo $typeChecked; ?> />
						<?php echo $value; ?><br/>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Areas Choosable', WWP_PLUGIN_LANG_DOMAIN ); ?>
				</td>
				<td>
					<?php foreach ( $areaArr as $key => $value ) : ?>
						<?php $areaChecked = ( ! isset( $scriptSetArr['area'][ $key ] ) or $scriptSetArr['area'][ $key ] == 'true' ) ? ' checked' : ''; ?>
						<input name="area[]" class="check-area" type="checkbox" value="<?php echo $key; ?>"<?php echo $areaChecked; ?> />
						<?php echo $value; ?><br/>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<td>

				</td>
				<td>
					<button class="wpp-btn wpp-btn-sm wpp-btn-primary btn-update-settings" type="button" id="wpp-update-settings">
						<?php _e( 'Update', WWP_PLUGIN_LANG_DOMAIN ); ?>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<?php
require_once 'wpPersonlizeEditor.php';
?>
