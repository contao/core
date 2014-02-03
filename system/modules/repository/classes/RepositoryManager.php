<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   Repository
 * @author    Peter Koch, IBK Software AG
 * @license   See accompaning file LICENSE.txt
 * @copyright Peter Koch 2008-2010
 */


/**
 * Implements the extension manager
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @author     Leo Feyer <https://contao.org>
 * @package    Repository
 */
class RepositoryManager extends RepositoryBackendModule
{
	/**
	 * Generate module:
	 * - Display a wildcard in the back end
	 * - Declare actionlist with templates and compilers in the front end
	 * @return string
	 */
	public function generate()
	{
		if (Input::get('update') != 'database' && !extension_loaded('soap')) {
			System::loadLanguageFile('tl_repository');
			$theme = new RepositoryBackendTheme();
			return '
<div id="tl_buttons" class="buttonwrapper">
	'. $theme->createMainButton('dbcheck16', $this->createUrl(array('update'=>'database')), $GLOBALS['TL_LANG']['tl_repository']['updatedatabase']) .'
</div>
<p class="tl_empty">'.$GLOBALS['TL_LANG']['tl_repository']['missingSoapModule'].'</p>';
		} // if

		$this->actions = array(
			//	  act[0]			strTemplate					compiler
			array('',				'repository_mgrlist',		'listinsts'	),
			array('edit',			'repository_mgredit',		'edit'		),
			array('install',		'repository_mgrinst',		'install'	),
			array('upgrade',		'repository_mgrupgd',		'upgrade'	),
			array('update',			'repository_mgrupdt',		'update'	),
			array('uninstall',		'repository_mgruist',		'uninstall'	)
		);

		// Switch to maintenance mode (see #4561)
		if (Input::post('repository_action') == 'install' || Input::post('repository_action') == 'uninstall') {
			$this->Config->update("\$GLOBALS['TL_CONFIG']['maintenanceMode']", true);
			$this->Config->save();
		}

		return parent::generate();
	} // generate

	/**
	 * List the installed extensions
	 */
	protected function listinsts()
	{
		$rep = &$this->Template->rep;

		// return from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			// nothing checked?
			$ids0 = Input::post('selectedids');
			if (!is_array($ids0)) { $this->redirect($rep->homeLink); return; }

			// valid ids?
			$ids = array();
			foreach ($ids0 as $id)
				if (is_numeric($id) && $id >= 0) {
					$id = (int)$id;
					$ok = true;
					foreach ($ids as $i) if ($i == $id) { $ok = false; break; }
					if ($ok) $ids[] = $id;
				} // if
			if (count($ids) > 0) { $this->redirect($this->createUrl(array('upgrade'=>implode(',',$ids)))); return; }
		} // if

		$rep->extensions = $this->getInstalledExtensions();
		$rep->installLink = $this->createUrl(array('install'=>'extension'));
		$rep->updateLink = $this->createUrl(array('update'=>'database'));
	} // listinsts

	/**
	 * Edit extension settings
	 * @param string
	 */
	protected function edit($aName)
	{
		$rep = &$this->Template->rep;
		$db = &$this->Database;

		$ext = $db->prepare("select * from `tl_repository_installs` where `extension`=?")->execute($aName);
		if (!$ext->next()) $this->redirect($rep->homeLink);

		// returning from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_cancelbutton'])) $this->redirect($rep->homeLink);
			$params = array(
				'lickey'	=> trim(Input::post('repository_lickey')),
				'alpha'		=> (int)Input::post('repository_alpha') > 0,
				'beta'		=> (int)Input::post('repository_beta') > 0,
				'rc'		=> (int)Input::post('repository_rc') > 0,
				'stable'	=> (int)Input::post('repository_stable') > 0,
				'delprot'	=> (int)Input::post('repository_delprot') > 0,
				'updprot'	=> (int)Input::post('repository_updprot') > 0
			);
			$db->prepare("update `tl_repository_installs` %s where `extension`=?")
				->set($params)
				->execute($aName);
			$this->redirect($rep->homeLink);
		} // if

		$rep->f_lickey = $ext->lickey;
		$rep->f_alpha = (int)$ext->alpha > 0;
		$rep->f_beta = (int)$ext->beta > 0;
		$rep->f_rc = (int)$ext->rc > 0;
		$rep->f_stable = (int)$ext->stable > 0;
		$rep->f_delprot = (int)$ext->delprot > 0;
		$rep->f_updprot = (int)$ext->updprot > 0;
	} // edit

	/**
	 * Install new extension
	 * @param string
	 */
	protected function install($aParams)
	{
		$text = &$GLOBALS['TL_LANG']['tl_repository'];
		$rep = &$this->Template->rep;
		$db = &$this->Database;

		$rep->inst_extension	= 0;
		$rep->inst_version		= 1;
		$rep->inst_lickey		= 2;
		$rep->inst_actions		= 3;
		$rep->inst_showlog		= 4;
		$rep->inst_error		= 5;

		$rep->f_stage = $rep->inst_extension;
		$rep->f_extension = '';
		$rep->f_version = '';
		$rep->f_allversions = array();
		$rep->f_enterkey = false;
		$rep->f_lickey = '';

		// return from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_cancelbutton'])) $this->redirect($rep->homeLink);
			$ok = true;
			$rep->f_stage = (int)Input::post('repository_stage');

			if ($rep->f_stage >= $rep->inst_extension) {
				// get and check extension
				$rep->f_extension = trim(Input::post('repository_extension'));
				if ($rep->f_extension != '') {
					$exts = $this->getExtensionList(array(
						'languages' => $this->languages,
						'match'		=> 'ignorecase',
						'names'		=> $rep->f_extension,
						'sets'		=> 'history,details'
					));
					// replace the case-insensitive user input with the real name (see #4689)
					if (count($exts)>0) {
						$rep->f_extension = $exts[0]->name;
						$rep->f_changelog = $exts[0]->releasenotes;
					}
				} else
					$exts = array();
				$ok = count($exts)>0;
				if ($ok) {
					foreach ($exts[0]->allversions as $ver)
						array_unshift($rep->f_allversions, $ver->version);
					$rep->f_enterkey = $exts[0]->type!='free';
				} else {
					$rep->f_extension_msg = 'extensionnotfound';
					$rep->f_stage = $rep->inst_extension;
				} // if
			} // if

			if ($rep->f_stage >= $rep->inst_version) {
				// get and check version
				$rep->f_version = trim(Input::post('repository_version'));
				$ok = false;
				foreach ($exts[0]->allversions as $ver)
					if ($ver->version == $rep->f_version) {
						$ok = true;
						break;
					} // if
				if (!$ok)
					$rep->f_stage = $rep->inst_version;
			} // if

			if ($rep->f_stage >= $rep->inst_lickey && $rep->f_enterkey) {
				// get license key
				$rep->f_lickey = trim(Input::post('repository_lickey'));
				if ($rep->f_lickey == '') {
					$rep->f_lickey_msg = 'lickeyrequired';
					$ok = false;
					$rep->f_stage = $rep->inst_lickey;
				} // if
			} // if

			if ($rep->f_stage >= $rep->inst_actions) {
				// get enable states
				$enableActions = Input::post('repository_enable');
				if (!is_array($enableActions)) $this->redirect($rep->homeLink);
			} // if

			if ($rep->f_stage==$rep->inst_actions && count($enableActions)>0) {
				// install!!!!
				$act = '';
				$actions = array();
				$this->addActions($rep->f_extension, $rep->f_version, $actions, $act);
				if ($act=='ok') {
					$rep->log = '<div class="title">'.$text['installlogtitle'].'</div>'."\n";
					$lickey = $rep->f_lickey;
					$checkdb = false;
					foreach ($actions as $act) {
						if (!in_array($act->extension, $enableActions)) continue;
						$updinst = $error = false;
						$record = '';
						switch ($act->action) {
							case 'validate':
								$rep->log .=
									'<div class="subtitle">'.
										sprintf($text['validatingext'], $act->extension, Repository::formatVersion($act->version), $act->build).
									"</div>\n";
								$error = $this->updateExtension($act->extension, $act->version, $lickey);
								$checkdb = $updinst = true;
								break;
							case 'install':
								$rep->log .=
									'<div class="subtitle">'.
										sprintf($text['installingext'], $act->extension, Repository::formatVersion($act->version), $act->build).
									"</div>\n";
								$st = $act->version % 10;
								$params = array(
									'tstamp'	=> time(),
									'extension'	=> $act->extension,
									'version'	=> $act->version,
									'build'		=> $act->build,
									'alpha'		=> $st <= 2 ? '1' : 0,
									'beta'		=> $st <= 5 ? '1' : 0,
									'rc'		=> $st <= 8 ? '1' : 0,
									'stable'	=> '1',
									'error'		=> '1'
								);
								$q = $db->prepare("insert into `tl_repository_installs` %s")->set($params)->execute();
								$error = $this->installExtension($act->extension, $act->version, $lickey);
								$checkdb = $updinst = true;
								$record = 'install';
								break;
							case 'update':
								$rep->log .=
									'<div class="subtitle">'.
										sprintf($text['updatingext'], $act->extension, Repository::formatVersion($act->version), $act->build).
									"</div>\n";
								$error = $this->updateExtension($act->extension, $act->version, $lickey);
								$checkdb = $updinst = true;
								$record = 'update';
								break;
							default:;
						} // if
						if ($updinst) {
							$params = array(
								'tstamp'	=> time(),
								'version'	=> $act->version,
								'build'		=> $act->build,
								'error'		=> $error ? '1' : ''
							);
							if ($lickey != '') $params['lickey'] = $lickey;
							$db->prepare("update `tl_repository_installs` %s where `extension`=?")->set($params)->execute($act->extension);
						} // if
						if ($record != '')
							$this->recordAction(array(
								'name'		=> $act->extension,
								'version'	=> $act->version,
								'action'	=> $record
							));
						$lickey = '';
						if ($error) break;
					} // foreach
					// PATCH: purge the internal cache
					$this->import('Automator');
					$this->Automator->purgeInternalCache();
				} // if
			} // if

			if ($rep->f_stage == $rep->inst_showlog)
				$this->redirect($this->createUrl(array('update'=>'database')));

			if ($ok) $rep->f_stage++;
		} else {
			// parse name.version
			$matches = array();
			if (preg_match('#^([a-zA-Z0-9_-]+)\.([0-9]+)$#', $aParams, $matches)) {
				$rep->f_extension = $matches[1];
				$rep->f_version = $matches[2];
				$rep->f_stage = $rep->inst_lickey;

				// get all versions
				$options = array(
					'languages' => $this->languages,
					'match'		=> 'exact',
					'names'		=> $rep->f_extension,
					'sets'		=> 'history,details'
				);
				$exts = $this->getExtensionList($options);
				$rep->f_changelog = $exts[0]->releasenotes;
				if (count($exts)>0) {
					foreach ($exts[0]->allversions as $ver)
						array_unshift($rep->f_allversions, $ver->version);
					$rep->f_enterkey = $exts[0]->type!='free';
				} else {
					$rep->f_extension_msg = 'extensionnotfound';
					$rep->f_stage = $rep->inst_extension;
				} // if
			} // if
		} // if

		if ($rep->f_stage==$rep->inst_lickey) {
			if (!$rep->f_enterkey) {
				// skip license key if not commercial or private
				$rep->f_stage++;
			} else {
				if ($rep->f_lickey=='') {
					// get license key
					$q = $db->prepare("select * from `tl_repository_installs` where `extension`=?")
							->execute($rep->f_extension);
					if ($q->next()) $rep->f_lickey = $q->lickey;
				} // if
			} // if
		} // if

		if ($rep->f_stage >= $rep->inst_actions) {
			$act = '';
			$rep->actions = array();
			$this->addActions($rep->f_extension, $rep->f_version, $rep->actions, $act);
			if ($act != 'ok') $rep->f_stage = $rep->inst_error;
			if (is_array($enableActions))
				foreach ($rep->actions as &$act)
					$act->enabled = in_array($act->extension, $enableActions);
		} // if

		if ($rep->f_stage < $rep->inst_error) $rep->f_submit = 'continue';
	} // install

	/**
	 * Upgrade extensions
	 * @param string
	 */
	protected function upgrade($aParams)
	{
		$rep = &$this->Template->rep;

		// return from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_continuebutton'])) {
				$this->redirect($this->createUrl(array('update'=>'database')));
				return;
			} // if
		} // if

		$text = &$GLOBALS['TL_LANG']['tl_repository'];
		$db = &$this->Database;
		$exts = $this->getInstalledExtensions($aParams);
		$rep->log = '';
		foreach ($exts as $ext) {
			if ((int)$ext->updprot==0) {
				$label =
					($ext->version != $ext->upd_version || $ext->upd_build != $ext->upd_build)
					? $text['updatingext']
					: $text['validatingext'];
				$rep->log .=
					'<div class="subtitle">'.
						sprintf($label, $ext->extension, Repository::formatVersion($ext->upd_version), $ext->upd_build).
					"</div>\n";
				$act = '';
				$actions = array();
				$this->addActions($ext->extension, $ext->upd_version, $actions, $act);
				if ($act=='ok') {
					$rep->log .= '<div class="title">'.$text['installlogtitle'].'</div>'."\n";
					$lickey = $ext->lickey;
					$checkdb = false;
					foreach ($actions as $act) {
						$updinst = $error = false;
						$record = '';
						switch ($act->action) {
							case 'validate':
								$rep->log .=
									'<div class="subtitle">'.
										sprintf($text['validatingext'], $act->extension, Repository::formatVersion($act->version), $act->build).
									"</div>\n";
								$error = $this->updateExtension($act->extension, $act->version, $lickey);
								$checkdb = $updinst = true;
								break;
							case 'install':
								$rep->log .=
									'<div class="subtitle">'.
										sprintf($text['installingext'], $act->extension, Repository::formatVersion($act->version), $act->build).
									"</div>\n";
								$st = $act->version % 10;
								$params = array(
									'tstamp'	=> time(),
									'extension'	=> $act->extension,
									'version'	=> $act->version,
									'build'		=> $act->build,
									'alpha'		=> $st <= 2 ? '1' : 0,
									'beta'		=> $st <= 5 ? '1' : 0,
									'rc'		=> $st <= 8 ? '1' : 0,
									'stable'	=> '1',
									'error'		=> '1'
								);
								$q = $db->prepare("insert into `tl_repository_installs` %s")->set($params)->execute();
								$error = $this->installExtension($act->extension, $act->version, $lickey);
								$checkdb = $updinst = true;
								$record = 'install';
								break;
							case 'update':
								$rep->log .=
									'<div class="subtitle">'.
										sprintf($text['updatingext'], $act->extension, Repository::formatVersion($act->version), $act->build).
									"</div>\n";
								$error = $this->updateExtension($act->extension, $act->version, $lickey);
								$checkdb = $updinst = true;
								$record = 'update';
								break;
							default:;
						} // if
						if ($updinst) {
							$params = array(
								'tstamp'	=> time(),
								'version'	=> $act->version,
								'build'		=> $act->build,
								'error'		=> $error ? '1' : ''
							);
							if ($lickey != '') $params['lickey'] = $lickey;
							$db->prepare("update `tl_repository_installs` %s where `extension`=?")->set($params)->execute($act->extension);
						} // if
						if ($record != '')
							$this->recordAction(array(
								'name'		=> $act->extension,
								'version'	=> $act->version,
								'action'	=> $record
							));
						$lickey = '';
						if ($error) break;
					} // foreach
					// PATCH: purge the internal cache
					$this->import('Automator');
					$this->Automator->purgeInternalCache();
				} // if
			} // if
		} // foreach
	} // upgrade

	/**
	 * Check / update database
	 */
	protected function update()
	{
		$rep = &$this->Template->rep;
		// return from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_cancelbutton'])) $this->redirect($rep->homeLink);
			$sql = Input::post('sql');
			if (!empty($sql) && is_array($sql)) {
				foreach ($sql as $key) {
					if (isset($_SESSION['sql_commands'][$key])) {
						$this->Database->query(str_replace('DEFAULT CHARSET=utf8;', 'DEFAULT CHARSET=utf8 COLLATE ' . $GLOBALS['TL_CONFIG']['dbCollation'] . ';', $_SESSION['sql_commands'][$key]));
					} // if
				} // foreach
			} // if
			$_SESSION['sql_commands'] = array();
		} // if
		$this->handleRunOnce(); // PATCH
		$this->import('Database\\Installer', 'Installer');
		$rep->dbUpdate = $this->Installer->generateSqlForm();
		if ($rep->dbUpdate != '') {
			$rep->f_submit = 'update';
			$rep->f_cancel = 'cancel';
		} else
			$rep->f_cancel = 'ok';
	} // update

	/**
	 * Uninstall an extension
	 * @param string
	 */
	protected function uninstall($aName)
	{
		$rep = &$this->Template->rep;
		$db = &$this->Database;

		$rep->uist_confirm	= 0;
		$rep->uist_showlog	= 1;
		$rep->uist_error	= 2;

		// return from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_cancelbutton'])) $this->redirect($rep->homeLink);
			$rep->f_stage = (int)Input::post('repository_stage');
			$rep->f_extension = trim(Input::post('repository_extension'));

			if ($rep->f_stage == $rep->uist_showlog)
				$this->redirect($this->createUrl(array('update'=>'database')));

			if ($rep->f_stage == $rep->uist_confirm) {
				// uninstall files
				$rep->log = '';
				if ($this->uninstallExtension($rep->f_extension))
					$rep->f_stage = $rep->uist_error;
				else
					$rep->f_stage = $rep->uist_showlog;
				// PATCH: purge the internal cache
				$this->import('Automator');
				$this->Automator->purgeInternalCache();
			} // if
		} else {
			$rep->f_stage = $rep->uist_confirm;
			$rep->f_extension = $aName;
		} // if

		// find dependent installed extensions
		$rep->deps = array();
		$q = $db->prepare("select * from `tl_repository_installs` where `extension`!=? order by `extension`")
				->execute($rep->f_extension);
		while ($q->next()) {
			$elist = $this->getExtensionList(array(
				'match' 	=> 'exact',
				'names' 	=> $q->extension,
				'versions'	=> $q->version,
				'languages'	=> $this->languages,
				'sets'		=> 'dependencies'
			));
			if (count($elist)>0) {
				$re = &$elist[0];
				if (property_exists($re, 'dependencies'))
					foreach ($re->dependencies as $dep)
						if ($dep->extension == $rep->f_extension) {
							$rep->deps[] = (object)array(
								'extension'	=> $q->extension,
								'version'	=> $q->version,
								'build'		=> $q->build
							);
						} // if
				unset($re);
			} // if
		} // while

		switch ($rep->f_stage) {
			case $rep->uist_confirm:
				$rep->f_submit = 'ok';
				break;
			case $rep->uist_showlog:
				$rep->f_submit = 'continue';
		} // switch
	} // uninstall

	/**
	 * Update the files of an extension.
	 * @param string $aName Name of the extension to install/update.
	 * @param int $aVersion The extension version to install/update to.
	 * @param string $aKey License key for commercial or private extensions.
	 * @return boolean
	 * @throws Exception
	 */
	private function updateExtension($aName, $aVersion, $aKey)
	{
		$text = &$GLOBALS['TL_LANG']['tl_repository'];
		$rep = &$this->Template->rep;
		$db = &$this->Database;
		$this->import('Files');

		$err = false;
		try {
			// get file list from repository
			$options = array('name' => $aName, 'version' => $aVersion);
			if ($aKey != '') $options['key'] = $aKey;
			$files = $this->getFileList($options);

			// initialize counters
			$sum_inst = $sum_updt = $sum_ok = $sum_del = 0;

			// get pid of installation record and flag old files for deletion
			$q = $db->prepare("select `id` from `tl_repository_installs` where `extension`=?")->execute($aName);
			if (!$q->next()) throw new Exception($text['extinstrecntf']);
			$instId = $q->id;
			$db->prepare("update `tl_repository_instfiles` set `flag`='D' where `pid`=? and `filetype`='F'")->execute($instId);

			foreach ($files as $file) {
				// get relative file name
				$filerel = '';
				if (mb_substr($file->path, 0, 8)=='TL_ROOT/')
					$filerel = mb_substr($file->path,8);
				else
					if (mb_substr($file->path, 0, 9)=='TL_FILES/') {
						$filerel = $this->tl_files . mb_substr($file->path,9);
					} // if

				// handle file
				if ($filerel != '') {
					$fileabs = $this->tl_root.$filerel;
					$save = false;
					if (file_exists($fileabs)) {
						if (filesize($fileabs)==$file->size && hash_file('md5', $fileabs)==$file->hash)
							$sum_ok++;
						else {
							$save = true;
							$sum_updt++;
						} // if
					} else {
						$save = true;
						$sum_inst++;
					} // if

					// save new directories by this file
					$dir = dirname($filerel);
					$enddirs = array('', '.', '/');
					while (!in_array($dir, $enddirs) && !file_exists($this->tl_root.$dir)) {
						$q = $db->prepare(
									"update `tl_repository_instfiles` set `tstamp`=?".
									" where `pid`=? and `filetype`='D' and `filename`=?"
								  )
								->execute(array(time(), $instId, $dir));
						if ($q->affectedRows == 0)
							$db	->prepare("insert into `tl_repository_instfiles` %s")
								->set(array(
									'pid'		=> $instId,
									'tstamp'	=> time(),
									'filename'	=> $dir,
									'filetype'	=> 'D'
								  ))
								->execute();
						$dir = dirname($dir);
					} // while

					// save new or changed file - by Request
					if ($save) {
						// HOOK: proxy module
						if ($GLOBALS['TL_CONFIG']['useProxy']) {
							$req = new ProxyRequest();
						} else {
							$req = new Request();
						}
						$req->send($file->url);
						if ($req->hasError()) throw new Exception($req->error);
						$f = new File($filerel, true);
						if (!$f->write($req->response))
							throw new Exception(sprintf($text['fileerrwrite'], $filerel));
						$f->close();
						$req = null; // release mem
					} // if

					// clear deletion flag, add new file record if not exists
					$q = $db->prepare(
								"update `tl_repository_instfiles` set `tstamp`=?, `flag`=''".
								" where `pid`=? and `filetype`='F' and `filename`=?"
							  )
							->execute(array(time(), $instId, $filerel));
					if ($q->affectedRows == 0)
						$db	->prepare("insert into `tl_repository_instfiles` %s")
							->set(array(
								'pid'		=> $instId,
								'tstamp'	=> time(),
								'filename'	=> $filerel,
								'filetype'	=> 'F'
							  ))
							->execute();
				} // if
			} // foreach

			// delete obsolete files
			$q = $db->prepare("select * from `tl_repository_instfiles` where `pid`=? and `filetype`='F' and `flag`='D'")->execute($instId);
			while ($q->next()) {
				$this->Files->delete($q->filename);
				$db->prepare("delete from `tl_repository_instfiles` where `id`=?")->execute($q->id);
				$sum_del++;
			} // while

			// log counters and success message
			if ($sum_inst>0) $rep->log .= '<div>'.sprintf($text['filesinstalled'],$sum_inst)."</div>\n";
			if ($sum_updt>0) $rep->log .= '<div>'.sprintf($text['filesupdated'],$sum_updt)."</div>\n";
			if ($sum_ok>0) $rep->log .= '<div>'.sprintf($text['filesunchanged'],$sum_ok)."</div>\n";
			if ($sum_del>0) $rep->log .= '<div>'.sprintf($text['filesdeleted'],$sum_del)."</div>\n";
			$rep->log .= '<div class="color_green">'.$text['actionsuccess']."</div>\n";
			$this->log('Extension "'. $aName .'" has been updated to version "'. Repository::formatVersion($aVersion) .'"', __METHOD__, TL_REPOSITORY);
		} // try
		catch (Exception $exc) {
			$rep->log .=
				"<div class=\"color_red\">\n".
				str_replace("\n", "<br/>\n", $exc->getMessage()) . "<br/>\n".
				"</div>\n";
			error_log(sprintf('Extension Manager: %s in %s on line %s', $exc->getMessage(), $exc->getFile(), $exc->getLine()));
			$err = true;
		} // catch
		return $err;
	} // updateExtension

	/**
	 * Install the files of an extension.
	 * @param string $aName Name of the extension to install/update.
	 * @param int $aVersion The extension version to install/update to.
	 * @param string $aKey License key for commercial or private extensions.
	 * @return boolean
	 * @throws Exception
	 */
	private function installExtension($aName, $aVersion, $aKey)
	{
		$text = &$GLOBALS['TL_LANG']['tl_repository'];
		$rep = &$this->Template->rep;
		$db = &$this->Database;
		$this->import('Files');

		$err = false;
		try {
			// get package info from repository
			$options = array('name' => $aName, 'version' => $aVersion, 'mode'=>'install');
			if ($aKey != '') $options['key'] = $aKey;
			$pkg = $this->getPackage($options);

			// create tmp name and copy package
			$zipname = 'system/tmp/' . $pkg->path;
			$this->Files->delete($zipname);

			// fetch package - using Request class
			// HOOK: proxy module
			if ($GLOBALS['TL_CONFIG']['useProxy']) {
				$req = new ProxyRequest();
			} else {
				$req = new Request();
			}
			$req->send($pkg->url);
			if ($req->hasError()) throw new Exception($req->error);
			$dstfile = new File($zipname, true);
			if (!$dstfile->write($req->response))
				throw new Exception(sprintf($text['fileerrwrite'], $zipname));
			$dstfile->close();
			$req = null; // release mem

			try {
				// initialize counters
				$sum_inst = $sum_updt = 0;

				// open zip archive
				$zip = new ZipReader($zipname);

				try {
					// get pid of installation record
					$q = $db->prepare("select `id` from `tl_repository_installs` where `extension`=?")->execute($aName);
					if (!$q->next()) throw new Exception($text['extinstrecntf']);
					$instId = $q->id;

					// process files in TL_ROOT and TL_FILES
					while ($zip->next()) {
						$filerel = '';
						if (mb_substr($zip->file_name, 0, 8)=='TL_ROOT/')
							$filerel = mb_substr($zip->file_name,8);
						else
							if (mb_substr($zip->file_name, 0, 9)=='TL_FILES/') {
								$filerel = $this->tl_files . mb_substr($zip->file_name,9);
							} // if

						if ($filerel != '') {
							$fileabs = $this->tl_root.$filerel;
							if (file_exists($fileabs)) $sum_updt++; else $sum_inst++;

							// save new directories by this file
							$dir = dirname($filerel);
							$enddirs = array('', '.', '/');
							while (!in_array($dir, $enddirs) && !file_exists($this->tl_root.$dir)) {
								$q = $db->prepare(
											"update `tl_repository_instfiles` set `tstamp`=?".
											" where `pid`=? and `filetype`='D' and `filename`=?"
										  )
										->execute(array(time(), $instId, $dir));
								if ($q->affectedRows == 0)
									$db	->prepare("insert into `tl_repository_instfiles` %s")
										->set(array(
											'pid'		=> $instId,
											'tstamp'	=> time(),
											'filename'	=> $dir,
											'filetype'	=> 'D'
										  ))
										->execute();
								$dir = dirname($dir);
							} // while

							// save new or changed file
							$f = new File($filerel, true);
							if (!$f->write($zip->unzip()))
								throw new Exception(sprintf($text['fileerrwrite'], $filerel));
							$f->close();

							// add new file record
							$db	->prepare("insert into `tl_repository_instfiles` %s")
								->set(array(
									'pid'		=> $instId,
									'tstamp'	=> time(),
									'filename'	=> $filerel,
									'filetype'	=> 'F'
								  ))
								->execute();
						} // if
					} // for
					$zip = null; // destruct = close
				} // try
				catch (Exception $exc) {
					$zip = null;
					throw $exc;
				} // catch

				// log counter and success message
				if ($sum_inst>0) $rep->log .= '<div>'.sprintf($text['filesinstalled'],$sum_inst)."</div>\n";
				if ($sum_updt>0) $rep->log .= '<div>'.sprintf($text['filesupdated'],$sum_updt)."</div>\n";
				$rep->log .= '<div class="color_green">'.$text['actionsuccess']."</div>\n";

				// cleanup
				$this->Files->delete($zipname);
			} // try
			catch (Exception $exc) {
				$this->Files->delete($zipname);
				throw $exc;
			} // catch
			$this->log('Extension "'. $aName .'" version "'. Repository::formatVersion($aVersion) .'" has been installed', __METHOD__, TL_REPOSITORY);
		} // try
		catch (Exception $exc) {
			$rep->log .=
				"<div class=\"color_red\">\n".
				str_replace("\n", "<br/>\n", $exc->getMessage()) . "<br/>\n".
				"</div>\n";
			error_log(sprintf('Extension Manager: %s in %s on line %s', $exc->getMessage(), $exc->getFile(), $exc->getLine()));
			$err = true;
		} // catch
		return $err;
	} // installExtension

	/**
	 * Uninstall the files of an extension.
	 * @param string $aName Name of the extension to install/update.
	 * @return boolean
	 * @throws Exception
	 */
	private function uninstallExtension($aName)
	{
		$text = &$GLOBALS['TL_LANG']['tl_repository'];
		$rep = &$this->Template->rep;
		$db = &$this->Database;
		$this->import('Files');

		$err = false;
		try {
			// get pid of installation record
			$q = $db->prepare("select * from `tl_repository_installs` where `extension`=?")->execute($aName);
			if (!$q->next()) throw new Exception($text['extinstrecntf']);
			$instId = $q->id;
			$version = $q->version;

			// delete the files
			$rep->log .= '<div class="subtitle">'.$text['deletingfiles']."</div>\n";
			$q = $db->prepare("select * from `tl_repository_instfiles` where `pid`=? and `filetype`='F' order by `filename`")
					->execute($instId);
			while ($q->next()) {
				$rep->log .= '<div>'.$q->filename.' ';
				$del = true;
				if (file_exists($this->tl_root.$q->filename)) {
					if ($this->Files->delete($q->filename))
						$rep->log .= '<span class="color_green">'.$text['success'];
					else {
						$rep->log .= '<span class="color_red">'.$text['failed'];
						$err = true;
						$del = false;
					} // if
				} else
					$rep->log .= '<span class="color_blue">'.$text['notfound'];
				$rep->log .= "</span></div>\n";
				if ($del)
					$db->prepare("delete from `tl_repository_instfiles` where `id`=?")->execute($q->id);
			} // while

			// delete the directories
			if (!$err) {
				$rep->log .= '<div class="subtitle">'.$text['deletingdirs']."</div>\n";
				$q = $db->prepare("select * from `tl_repository_instfiles` where `pid`=? and `filetype`='D' order by `filename` desc")
						->execute($instId);
				while ($q->next()) {
					$rep->log .= '<div>'.$q->filename.' ';
					if (file_exists($this->tl_root.$q->filename)) {
						$f = new Folder($q->filename);
						$f->delete();
						$rep->log .= '<span class="color_green">'.$text['success'];
					} else
						$rep->log .= '<span class="color_blue">'.$text['notfound'];
					$rep->log .= "</span></div>\n";
					$db->prepare("delete from `tl_repository_instfiles` where `id`=?")->execute($q->id);
				} // while
			} // if

			// statistics
			$this->recordAction(array(
				'name'		=> $aName,
				'version'	=> $version,
				'action'	=> 'uninstall'
			));

			$rep->log .= "<div>&nbsp;</div>\n";
			if ($err) {
				$db->prepare("update `tl_repository_installs` set `error`='1' where `id`=?")->execute($instId);
				$rep->log .= '<div class="color_red">'.$text['actionfailed'].'</div>';
			} else {
				$db->prepare("delete from `tl_repository_installs` where `id`=?")->execute($instId);
				$rep->log .= '<div class="color_green">'.$text['actionsuccess'].'</div>';
			} // if
			$this->log('Extension "'. $aName .'" has been uninstalled', __METHOD__, TL_REPOSITORY);
		} // try
		catch (Exception $exc) {
			$rep->log .=
				"<div class=\"color_red\">\n".
				$exc->getMessage() . "<br/>\n".
				"</div>\n";
			error_log(sprintf('Extension Manager: %s in %s on line %s', $exc->getMessage(), $exc->getFile(), $exc->getLine()));
			$err = true;
		} // catch
		return $err;
	} // uninstallExtension

	/**
	 * Create comma separated list of states
	 * @param integer
	 * @param integer
	 * @param integer
	 * @param integer
	 * @return string
	 */
	private function stateList($aAlpha, $aBeta, $aRc, $aStable)
	{
		$states = array();
		if ((int)$aAlpha>0) $states[] = 'alpha';
		if ((int)$aBeta>0) $states[] = 'beta';
		if ((int)$aRc>0) $states[] = 'rc';
		if ((int)$aStable>0) $states[] = 'stable';
		return implode(',', $states);
	} // stateList

	/**
	 * Collect all acctions necessary to install/update an extension.
	 * @param string
	 * @param string
	 * @param array
	 * @param string
	 * @param boolean
	 * @param string
	 * @param integer
	 * @param integer
	 * @param integer
	 */
	private function addActions($aName, $aVersion, &$aActions, &$aAction, $aDeps=true, $aParent='', $aParentVersion=0, $aMinversion=0, $aMaxversion=0)
	{
		$db = &$this->Database;

		// prepare action record
		$action = (object)array(
			'extension'	=> $aName,
			'version'	=> 0,
			'build'		=> 0,
			'action'	=> 'none',
			'enabled'	=> true,
			'locked'	=> false,
			'status'	=> array()
		);
		$aActions[] = $action;

		$params = array(
			'match' 	=> 'exact',
			'names' 	=> $aName,
			'versions' 	=> $aVersion,
			'languages'	=> $this->languages
		);
		if ($aDeps) $params['sets'] = 'dependencies';
		$exts = $this->getExtensionList($params);

		if (count($exts)<1) {
			$action->status[] = (object)array('color'=>'red', 'text'=>'extnotfound');
			$aAction = 'failed';
		} else {
			$ext = &$exts[0];
			$action->version = $ext->version;
			$action->build = $ext->build;

			// allready installed?
			$q = $db->prepare("select * from `tl_repository_installs` where `extension`=?")
					->execute($aName);
			if ($q->next()) {
				if ((int)$q->error>0)
					$action->status[] = (object)array('color'=>'red', 'text'=>'errorinstall');
				if ($q->version != $ext->version || $q->build != $ext->build) {
					$action->action = 'update';
				} else {
					$action->action = 'validate';
					$action->enabled = false;
				} // if
				if ($aAction=='') $aAction = 'ok';
				if ($q->updprot) {
					$action->enabled = false;
					$action->locked = true;
				} // if
			} else {
				$action->action = 'install';
				if ($aAction=='') $aAction = 'ok';
			} // if

			// parent compatibility check
			$extver = $ext->version;
			$minver = $aMinversion>0 ? $aMinversion : $extver;
			$maxver = $aMaxversion>0 ? $aMaxversion : $extver;
			if ($extver<$minver || $extver>$maxver) {
				$extver = intval($extver / 10000);
				$minver = intval($minver / 10000);
				$maxver = intval($maxver / 10000);
				if ($extver<$minver || $extver>$maxver)
					$action->status[] = (object)array(
						'color'	=> 'darkorange',
						'text'	=> 'notapproved',
						'par1'	=> $aParent,
						'par2'	=> Repository::formatVersion($aParentVersion)
					);
				else
					$action->status[] = (object)array(
						'color'	=> 'green',
						'text'	=> 'shouldwork',
						'par1'	=> $aParent,
						'par2'	=> Repository::formatVersion($aParentVersion)
					);
			} // if

			// contao compatibility check
			$tlversion = Repository::encodeVersion(VERSION.'.'.BUILD);
			$minver = $ext->coreminversion>0 ? $ext->coreminversion : $tlversion;
			$maxver = $ext->coremaxversion>0 ? $ext->coremaxversion : $tlversion;
			if ($tlversion<$minver || $tlversion>$maxver) {
				$tlversion = intval($tlversion / 10000);
				$minver = intval($minver / 10000);
				$maxver = intval($maxver / 10000);
				if ($tlversion<$minver || $tlversion>$maxver)
					$action->status[] = (object)array(
						'color'	=> 'darkorange',
						'text'	=> 'notapproved',
						'par1'	=> 'Contao',
						'par2'	=> VERSION.'.'.BUILD
					);
				else
					$action->status[] = (object)array(
						'color'	=> 'green',
						'text'	=> 'shouldwork',
						'par1'	=> 'Contao',
						'par2'	=> VERSION.'.'.BUILD
					);
			} // if

			if (count($action->status)==0)
				$action->status[] = (object)array('color'=>'green', 'text'=>'uptodate');

			// add dependencies
			if ($aDeps && property_exists($ext, 'dependencies'))
				foreach ($ext->dependencies as $dep) {
					$take = true;
					foreach ($aActions as $a)
						if ($a->extension == $dep->extension) {
							$take = false;
							break;
						} // if
					if ($take) {
						// find highest version that might be compatible
						$deps = $this->getExtensionList(array(
							'match' 	=> 'exact',
							'names' 	=> $dep->extension,
							'languages'	=> $this->languages,
							'sets'		=> 'history'
						));
						$depver = $dep->maxversion;
						if (count($deps)>0) {
							foreach ($deps[0]->allversions as $ver) {
								$v = $ver->version;
								if ($v>$depver && intval($v/10000)==intval($depver/10000))
									$depver = $v;
							} // foreach
						} // if
						$this->addActions(
							$dep->extension,
							$depver,
							$aActions,
							$aAction,
							$aDeps,
							$aName,
							$ext->version,
							$dep->minversion,
							$dep->maxversion
						);
					} // if
				} // foreach
		} // if
	} // addActions

	/**
	 * Get installed extensions list.
	 * @param string
	 * @return array Array with the extension records.
	 */
	private function getInstalledExtensions($aIds = '')
	{
		$db = &$this->Database;

		// query installed extensions
		$exts = array();
		$q = $db->execute(
			($aIds=='')
				? "select * from `tl_repository_installs` order by `extension`"
				: "select * from `tl_repository_installs` where `id` in ($aIds) order by `extension`"
		);
		while ($q->next()) $exts[] = (object)$q->row();

		// find each highest compatible version
		foreach ($exts as &$ext) {
			$ext->tl_incompatible = false;
			$ext->tl_shouldwork = false;
			$ext->dep_missing = array();
			$ext->dep_incompatible = array();
			$ext->dep_shouldwork = array();
			$ext->upd_version = $ext->version;
			$ext->upd_build = $ext->build;

			// query current release
			$elist = $this->getExtensionList(array(
				'match' 	=> 'exact',
				'names' 	=> $ext->extension,
				'versions'	=> $ext->version,
				'languages'	=> $this->languages,
				'sets'		=> 'dependencies,history,details'
			));
			$ext->found = count($elist)>0;
			if ($ext->found) {
				$extrec = &$elist[0];
				if (isset($extrec->manual)) $ext->manualLink = $extrec->manual;
				if (isset($extrec->forum)) $ext->forumLink = $extrec->forum;

				// contao compatibility check
				$tlversion = Repository::encodeVersion(VERSION.'.'.BUILD);
				$minver = $extrec->coreminversion>0 ? $extrec->coreminversion : $tlversion;
				$maxver = $extrec->coremaxversion>0 ? $extrec->coremaxversion : $tlversion;
				if ($tlversion<$minver || $tlversion>$maxver) {
					$tlversion = intval($tlversion / 10000);
					$minver = intval($minver / 10000);
					$maxver = intval($maxver / 10000);
					if ($tlversion<$minver || $tlversion>$maxver)
						$ext->tl_incompatible = true;
					else
						$ext->tl_shouldwork = true;
				} // if

				// dependencies compatibility check
				if (property_exists($ext, 'dependencies')) {
					foreach ($ext->dependencies as &$dep) {
						$found = false;
						foreach ($exts as $e)
							if ($e->extension == $dep->extension) {
								$found = true;
								$extver = $e->version;
								$minver = $dep->minversion>0 ? $dep->minversion : $extver;
								$maxver = $dep->maxversion>0 ? $dep->maxversion : $extver;
								if ($extver<$minver || $extver>$maxver) {
									$extver = intval($extver / 10000);
									$minver = intval($minver / 10000);
									$maxver = intval($maxver / 10000);
									if ($extver<$minver || $extver>$maxver)
										$ext->dep_incompatible[] = array('extension'=>$e->extension, 'version'=>$e->version);
									else
										$ext->dep_shouldwork[] = array('extension'=>$e->extension, 'version'=>$e->version);
								} // if
								break;
							} // if
						if (!$found) $ext->dep_missing[] = $dep->extension;
					} // foreach
				} // if
			} else {
				// find any other version
				$elist = $this->getExtensionList(array(
					'match' 	=> 'exact',
					'names' 	=> $ext->extension,
					'languages'	=> $this->languages,
					'sets'		=> 'dependencies,history'
				));
			} // if
			if (count($elist)<1) continue; // no other tests possible

			// get all available versions in descending order
			$vers = array();
			foreach ($elist[0]->allversions as $ver)
				array_unshift($vers, (object)array('version' => $ver->version, 'build' => $ver->build));

			// find highest compatible version
			foreach ($vers as $ver) {
				// status check
				$compatible = false;
				switch ($ver->version % 10) {
					case 0: case 1: case 2: $compatible = (int)$ext->alpha>0; break;
					case 3: case 4: case 5: $compatible = (int)$ext->beta>0; break;
					case 6: case 7: case 8: $compatible = (int)$ext->rc>0; break;
					default: $compatible = (int)$ext->stable>0;
				} // switch
				if (!$compatible) continue;

				// PATCH: ignore older releases
				if ($ver->version < $elist[0]->version) continue;

				// get record of this version
				$rec = null;
				if ($ver->version == $elist[0]->version)
					$rec = &$elist[0];
				else {
					$olist = $this->getExtensionList(array(
						'match' 	=> 'exact',
						'names' 	=> $ext->extension,
						'versions'	=> $ver->version,
						'languages'	=> $this->languages,
						'sets'		=> 'dependencies'
					));
					if (count($olist) > 0) $rec = &$olist[0];
				} // if
				if ($rec == null) continue;

				// contao compatibility check
				$tlversion = intval(Repository::encodeVersion(VERSION.'.'.BUILD)/10000);
				$minver = $rec->coreminversion>0 ? intval($rec->coreminversion/10000) : $tlversion;
				$maxver = $rec->coremaxversion>0 ? intval($rec->coremaxversion/10000) : $tlversion;
				if ($tlversion<$minver || $tlversion>$maxver) continue;

				// dependencies compatibility check
				if (property_exists($rec, 'dependencies')) {
					foreach ($rec->dependencies as &$dep) {
						foreach ($exts as $e)
							if ($e->extension == $dep->extension) {
								$extver = intval($e->version/10000);
								$minver = $dep->minversion>0 ? intval($dep->minversion/10000) : $extver;
								$maxver = $dep->maxversion>0 ? intval($dep->maxversion/10000) : $extver;
								if ($extver<$minver || $extver>$maxver) $compatible = false;
								break;
							} // if
					} // foreach
				} // if
				if (!$compatible) continue;

				// $rec is the highest compatible version
				if ($rec->version != $ext->version || $rec->build != $ext->build) {
					$ext->upd_version = $rec->version;
					$ext->upd_build = $rec->build;
				} // if
				break;
			} // foreach version
		} // foreach

		// find display status for each extension
		foreach ($exts as &$ext) {
			$ext->status = array();

			// code red
			if ((int)$ext->error>0)
				$ext->status[] = (object)array(
					'color'	=> 'red',
					'text'	=> 'errorinstall'
				);
			if (!$ext->found)
				$ext->status[] = (object)array(
					'color'	=> 'red',
					'text'	=> 'vernotfound'
				);
			foreach ($ext->dep_missing as $d)
				$ext->status[] = (object)array(
					'color'	=> 'red',
					'text'	=> 'depmissing',
					'par1'	=> $d
				);

			// code yellow
			if ($ext->tl_incompatible)
				$ext->status[] = (object)array(
					'color'	=> 'darkorange',
					'text'	=> 'notapproved',
					'par1'	=> 'Contao',
					'par2'	=> VERSION.'.'.BUILD
				);
			foreach ($ext->dep_incompatible as $d)
				$ext->status[] = (object)array(
					'color'	=> 'darkorange',
					'text'	=> 'notapprovedwith',
					'par1'	=> $d->extension,
					'par2'	=> Repository::formatVersion($d->version)
				);

			// code blue
			if ($ext->upd_version!=$ext->version || $ext->upd_build!=$ext->build)
				$ext->status[] = (object)array(
					'text'	=> 'newversion',
					'color'	=> 'blue',
					'par1'	=> Repository::formatVersion($ext->upd_version),
					'par2'	=> $ext->upd_build
				);

			// code light green
			if ($ext->tl_shouldwork)
				$ext->status[] = (object)array(
					'color'	=> 'green',
					'text'	=> 'shouldwork',
					'par1'	=> 'Contao',
					'par2'	=> VERSION.'.'.BUILD
				);
			foreach ($ext->dep_shouldwork as $d)
				$ext->status[] = (object)array(
					'color'	=> 'green',
					'text'	=> 'shouldwork',
					'par1'	=> $dep->extension,
					'par2'	=> Repository::formatVersion($dep->version)
				);

			// code dark green
			if (count($ext->status)==0)
				$ext->status[] = (object)array(
					'color' => 'green',
					'text'	=> 'uptodate'
				);

			if ($ext->found) $ext->catalogLink = $this->createPageUrl('repository_catalog', array('view'=>$ext->extension));
			$ext->editLink = $this->createUrl(array('edit'=>$ext->extension));
			if ((int)$ext->updprot==0) $ext->updateLink = $this->createUrl(array('install'=>$ext->extension.'.'.$ext->upd_version));
			if ((int)$ext->delprot==0) $ext->uninstallLink =  $this->createUrl(array('uninstall'=>$ext->extension));
		} // while
		return $exts;
	} // getInstalledExtensions

	/**
	 * Get file list from repository. Either via SOAP or directly when on same site.
	 * @param array $aOptions The options as defined in the SOAP interface spec.
	 * @return array Array with the file records.
	 */
	private function getFileList($aOptions)
	{
		switch ($this->mode) {
			case 'local':
				return $this->RepositoryServer->getFileList((object)$aOptions);
			case 'soap':
				return $this->client->getFileList($aOptions);
			default:
				return array();
		} // if
	} // getFileList

	/**
	 * Get package from repository. Either via SOAP or directly when on same site.
	 * @param array $aOptions The options as defined in the SOAP interface spec.
	 * @return object stdClass with the package information.
	 */
	private function getPackage($aOptions)
	{
		switch ($this->mode) {
			case 'local':
				return $this->RepositoryServer->getPackage((object)$aOptions);
			case 'soap':
				return $this->client->getPackage($aOptions);
			default:
				return array();
		} // if
	} // getPackage

	/**
	 * Record an install/update/uninstall action in the repository.
	 * @param array $aOptions The options as defined in the SOAP interface spec.
	 * @return array
	 */
	private function recordAction($aOptions)
	{
		switch ($this->mode) {
			case 'local':
				return $this->RepositoryServer->recordAction((object)$aOptions);
			case 'soap':
				return $this->client->recordAction($aOptions);
			default:
				return array();
		} // if
	} // recordAction

} // class RepositoryManager
