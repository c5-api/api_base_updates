<?php defined('C5_EXECUTE') or die('Access Denied');

class DashboardApiCoreUpdatesController extends DashboardBaseController {
	
	public function view() {
		$fh = Loader::helper('file');
		$cont = $fh->getContents(C5_API_UPDATE_URL);
		$n = array();
		if($cont) {
			$json = Loader::helper('json');
			$arr = $json->decode($cont);

			$list = PackageList::get()->getPackages();

			foreach($list as $pkg) {
				$handle = $pkg->getPackageHandle();
				if(isset($arr->$handle)) {
					$version = $pkg->pkgVersion;
					$m = $arr->$handle;
					if(version_compare($m->version, $version, '>')) {
						$n[$handle] = $m;
					}
				}
			}

			$this->set('list', $n);
		} else {
			$this->set('error', t('Unable to check for updates.'));
		}

	}

}