<?php defined('C5_EXECUTE') or die("Access Denied.");

class ApiBaseUpdatesPackage extends Package {

	protected $pkgHandle = 'api_base_updates';
	protected $appVersionRequired = '5.6.0';
	protected $pkgVersion = '0.91';

	public function getPackageName() {
		return t("Api:Base:Updates");
	}

	public function getPackageDescription() {
		return t("Check for updates for base addons");
	}

	public function on_start() {
		define('C5_API_UPDATE_URL', 'http://c5api.com/index.php/tools/updates');
	}

	public function install() {
		$this->on_start();
		$installed = Package::getByHandle('api');
		if(!is_object($installed)) {
			throw new Exception(t('Please install the "API" package before installing %s', $this->getPackageName()));
		}

		parent::install();

		$pkg = Package::getByHandle($this->pkgHandle);
		$p = SinglePage::add('/dashboard/api/core/updates',$pkg);
		$p->update(array('cName'=> t('Updates')));

		
	}
	
	public function uninstall() {
		parent::uninstall();
	}

}