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

	private function download($handle = false) {
		if(!$handle) {
			$this->redirect('/dashboard/api/core/updates');
		}
		$file = Loader::helper('file');
		$url = 'https://github.com/c5-api/'.$handle.'/zipball/master';
		$pkg = $file->getContents($url);
		if (empty($pkg)) {
			return Package::E_PACKAGE_DOWNLOAD;
		}
		if ($pkg == Package::E_PACKAGE_INVALID_APP_VERSION) {
			return Package::E_PACKAGE_INVALID_APP_VERSION;
		}

		$handle = time();
		// Use the same method as the Archive library to build a temporary file name.
		$tmpFile = $file->getTemporaryDirectory() . '/' . $handle . '.zip';
		$fp = fopen($tmpFile, "wb");
		if ($fp) {
			fwrite($fp, $pkg);
			fclose($fp);
		} else {
			return Package::E_PACKAGE_SAVE;
		}

		return $handle;
	}

	public function get($handle = false) {
		$fh = Loader::helper('file');
		$file = $this->download($handle);
		$error = false;
		if (empty($file) || $file == Package::E_PACKAGE_DOWNLOAD) {
			$error = array(Package::E_PACKAGE_DOWNLOAD);
		} else if ($file == Package::E_PACKAGE_SAVE) {
			$error = array($file);
		} else if ($file == Package::E_PACKAGE_INVALID_APP_VERSION) {
			$error = array($file);
		}
		try {
			$install = $this->unzip($file);
		} catch (Exception $e) {
			$error = array($e->getMessage());
		}
		if(!$error) {
			$cont = $fh->getDirectoryContents($install);
			$fh->copyAll($install.'/'.$cont[0], DIR_PACKAGES.'/'.$handle);
		}

		if($error) {
			$this->error->add($error[0]);
		}
		$this->view();
	}

	private function unzip($directory) {
		$file = $directory . '.zip';
		$fh = Loader::helper('file');
		if (function_exists('zip_open')) {
			try {
				$zip = new ZipArchive;
				if ($zip->open($fh->getTemporaryDirectory() . '/' . $file) === TRUE) {
					$zip->extractTo($fh->getTemporaryDirectory() . '/' . $directory . '/');
					$zip->close();	
					return $fh->getTemporaryDirectory() . '/' . $directory;
				}			
			} catch(Exception $e) {}
 		} 

		$ret = @shell_exec(DIR_FILES_BIN_UNZIP . ' ' . $fh->getTemporaryDirectory() . '/' . $file . ' -d ' . $fh->getTemporaryDirectory() . '/' . $directory . '/');
		$files = $fh->getDirectoryContents($fh->getTemporaryDirectory() . '/' . $directory);
		echo $directory;
		if (count($files) == 0) {
			throw new Exception(t('There was an error unpacking the file. You may have chosen an invalid package, or you do not have zip installed.'));
		} else {
			return $fh->getTemporaryDirectory() . '/' . $directory;
		}

	}

}