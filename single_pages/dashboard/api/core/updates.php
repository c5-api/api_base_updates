<?php defined('C5_EXECUTE') or die('Access Denied.');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Updates'), t('Check for updates for base api packages'), false, false);?>
	<div class="ccm-pane-body" style="padding-bottom: 0px">
		<div class="alert alert-info"><?php echo t('Note: Packages will be downloaded, but not <a href="%s">installed</a> or <a href="%s">updated</a>.', View::url('/dashboard/extend/install/'), View::url('/dashboard/extend/update/'))?></div>
		
		<h3><?php echo t('Updates')?></h3>
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
				echo '<tr><td colspan="3">'.t('There are no available updates at this time.').'</td></tr>';
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
							<a href="<?php echo $this->action('get', $handle);?>"><?php echo t('Download')?></a>
						</td>
					</tr>
				<?php }
			}?>
			</tbody>
		</table>

		<h3><?php echo t('Addons')?></h3>
		<table class="table">
			<thead>
				<td>
					<?php echo t('Package Name');?>
				</td>
				<td>
					<?php echo t('Version');?>
				</td>
				<td>
					<?php echo t('Url');?>
				</td>
				<td>
					<?php echo t('Download');?>
				</td>

			</thead>
			<tbody>
			<?php
			if(!count($all)) {
				echo '<tr><td colspan="4">'.t('There are no other packages available at this time.').'</td></tr>';
			} else {
				foreach($all as $handle => $arr) { 
					?>
					<tr>
						<td>
							<?php echo $arr->name;?>
						</td>
						<td>
							<?php echo $arr->version;?>
						</td>
						<td>
							<a target="_blank" href="<?php echo $arr->url;?>"><?php echo t('Info')?></a>
						</td>
						<td>
							<a href="<?php echo $this->action('get', $handle);?>"><?php echo t('Download')?></a>
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