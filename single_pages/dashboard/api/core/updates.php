<?php defined('C5_EXECUTE') or die('Access Denied.');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Updates'), t('Check for updates for base api packages'), false, false);
	echo '<div class="ccm-pane-body" style="padding-bottom: 0px">'; ?>
		<table class="table">
			<thead>
				<td>
					<?php echo t('Package Name');?>
				</td>
				<td>
					<?php echo t('New Version');?>
				</td>
				<td>
					<?php echo t('URL');?>
				</td>

			</thead>
			<tbody>
			<?php
			if(!count($list)) {
				echo '<tr><td>'.t('There are no available updates at this time.').'</td></tr>';
			} else {
				foreach($list as $handle => $arr) { 
					$pkg = Package::getByHandle($handle);
					?>
					<tr>
						<td>
							<?php echo $pkg->getPackageName();?>
						</td>
						<td>
							<?php echo $arr->version;?>
						</td>
						<td>
							<a target="_blank" href="<?php echo $arr->url;?>"><?php echo t('Download')?></a>
						</td>
					</tr>
				<?php }
			}?>
			</tbody>
		</table>
	<?php
	echo '<div class="clearfix"></div></div>';
	echo '<div class="ccm-pane-footer">';

	echo '</div>';


echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);