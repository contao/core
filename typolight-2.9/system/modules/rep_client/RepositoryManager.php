<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Repository
 * @license    LGPL
 * @filesource
 */


/**
 * TYPOlight Repository :: Backend module for extension management
 *
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */


/**
 * Implements the extension manager.
 */
class RepositoryManager extends RepositoryBackendModule
{
	/**
	 * Generate module:
	 * - Display a wildcard in the back end
	 * - Declare actionlist with templates and compilers in the front end
	 */
	public function generate()
	{
		if ($this->Input->get('update') != 'database' && !extension_loaded('soap')) {
			$this->loadLanguageFile('tl_repository');
			$theme = new RepositoryBackendTheme();
			return '
<div id="tl_buttons" class="buttonwrapper">
	'. $theme->createMainButton('dbcheck16', $this->createUrl(array('update'=>'database')), $GLOBALS['TL_LANG']['tl_repository']['updatedatabase']) .' 
</div>
<p class="tl_empty">SOAP extension not loaded (configure PHP with --enable-soap).</p>';
		} // if
		$this->actions = array(
			//	  act[0]			strTemplate					compiler
			array('',				'repository_mgrlist',		'listinsts'	),
			array('edit',			'repository_mgredit',		'edit'		),
			array('install',		'repository_mgrinst',		'install'	),
			array('update',			'repository_mgrupdt',		'update'	),
			array('uninstall',		'repository_mgruist',		'uninstall'	)
		);
		return parent::generate();
	} // generate
	
	/**
	 * List the installed extensions
	 */
	protected function listinsts()
	{
		$rep = &$this->Template->rep;
		$db = &$this->Database;
		
		// query extensions
		$rep->extensions = array();
		$ext = $db->execute("select * from `tl_repository_installs` order by `extension`");
		while ($ext->next()) {
			$e = (object)$ext->row();
			$e->status = array();
			if ((int)$e->error>0) $e->status[] = (object)array('color' => 'red', 'text' => 'errorinstall');

			// query current release
			$elist = $this->getExtensionList(array(
				'match' 	=> 'exact',
				'names' 	=> $e->extension,
				'versions'	=> $e->version,
				'languages'	=> $this->languages,
				'sets'		=> 'dependencies'
			));
			
			$found = false;
			if (count($elist) == 0) {
				$e->status[] = (object)array('color' => 'red', 'text' => 'vernotfound');
			} else {
				$found = true;
				$re = &$elist[0];
				
				// dependencies check
				if (property_exists($re, 'dependencies')) {
					foreach ($re->dependencies as &$dep) {
						$q = $db->prepare("select * from `tl_repository_installs` where `extension`=?")
								->execute($dep->extension);
						if (!$q->next()) 
							$e->status[] = (object)array(
								'color'	=> 'red', 
								'text'	=> 'depmissing', 
								'par1'	=> $dep->extension
							);
						else
							if (($dep->minversion>0 && $q->version<$dep->minversion) ||
								($dep->maxversion>0 && $q->version>$dep->maxversion)) {
								$e->status[] = (object)array(
									'color'	=> 'darkorange', 
									'text'	=> 'notapprovedwith', 
									'par1'	=> $dep->extension,
									'par2'	=> Repository::formatVersion($q->version)
								);
							} // if
					} // foreach
				} // if
				
				// typolight compatibility check
				$tlversion = Repository::encodeVersion(VERSION.'.'.BUILD);
				if (($re->coreminversion>0 && $tlversion<$re->coreminversion) ||
					($re->coremaxversion>0 && $tlversion>$re->coremaxversion) )
					$e->status[] = (object)array(
						'color'	=> 'darkorange', 
						'text'	=> 'notapproved', 
						'par1'	=> 'TYPOlight',
						'par2'	=> VERSION.'.'.BUILD
					);
					
				unset($re);
			} // if
			
			$updversion = $e->version;
			
			// new version search
			$states = $this->stateList($e->alpha, $e->beta, $e->rc, $e->stable);
			if ($states != '') {
				// search for new release
				$elist = $this->getExtensionList(array(
					'match' 	=> 'exact',
					'names' 	=> $e->extension,
					'states'	=> $states,
					'languages'	=> $this->languages,
					'sets'		=> 'details'
				));
				if (count($elist) > 0) {
					// new version/build check
					$found = true;
					$re = &$elist[0];
					if ($re->version > $e->version ||
						($re->version == $e->version && $re->build > $e->build)) {
						$e->status[] = (object)array('text'=>'newversion', 'color'=>'blue', 'par1'=>Repository::formatVersion($re->version), 'par2'=>$re->build);
						$updversion = $re->version;
					} // if
					if (isset($re->manual)) $e->manualLink = $re->manual; 
					if (isset($re->forum)) $e->forumLink = $re->forum; 
					unset($re);
				} // if
			} // if
			
			if (count($e->status)==0) {
				$e->status[] = (object)array('text' => 'uptodate', 'color' => 'green');
			} // if
			
			if ($found) $e->catalogLink = $this->createPageUrl('repository_catalog', array('view'=>$e->extension));
			$e->editLink = $this->createUrl(array('edit'=>$e->extension));
			$e->updateLink = $this->createUrl(array('install'=>$e->extension.'.'.$updversion));
			if ((int)$e->delprot==0) $e->uninstallLink =  $this->createUrl(array('uninstall'=>$e->extension));
			$rep->extensions[] = $e;
		} // while
		
		$rep->installLink = $this->createUrl(array('install'=>'extension'));
		$rep->updateLink = $this->createUrl(array('update'=>'database'));
	} // listinsts

	/**
	 * Edit extension settings
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
				'lickey'	=> trim($this->Input->post('repository_lickey')),
				'alpha'		=> (int)$this->Input->post('repository_alpha') > 0,
				'beta'		=> (int)$this->Input->post('repository_beta') > 0,
				'rc'		=> (int)$this->Input->post('repository_rc') > 0,
				'stable'	=> (int)$this->Input->post('repository_stable') > 0,
				'delprot'	=> (int)$this->Input->post('repository_delprot') > 0
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
	} // edit
	
	/**
	 * Install new extension
	 */
	protected function install($aParams)
	{
		$text = &$GLOBALS['TL_LANG']['tl_repository']; 
		$rep = &$this->Template->rep;
		$db = &$this->Database;
		
		$rep->inst_stages		= 0;
		$rep->inst_extension	= 1;
		$rep->inst_lickey		= 2;
		$rep->inst_actions		= 3;
		$rep->inst_showlog		= 4;
		$rep->inst_error		= 5;
		
		$rep->f_stage = $rep->inst_stages;
		$rep->f_alpha = $rep->f_beta = $rep->f_rc = false;
		$rep->f_stable = true;
		$rep->f_extension = '';
		$rep->f_lickey = '';
		
		// return from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_cancelbutton'])) $this->redirect($rep->homeLink);
			$ok = true;
			$rep->f_stage = (int)$this->Input->post('repository_stage');

			// get and validate states
			$rep->f_alpha	= (int)$this->Input->post('repository_alpha') > 0;
			$rep->f_beta	= (int)$this->Input->post('repository_beta') > 0;
			$rep->f_rc		= (int)$this->Input->post('repository_rc') > 0;
			$rep->f_stable	= (int)$this->Input->post('repository_stable') > 0;
			$states = $this->stateList($rep->f_alpha, $rep->f_beta, $rep->f_rc, $rep->f_stable);		
			if ($states == '') {
				$ok = false;
				$rep->f_stage = $rep->inst_stages;
			} // if

			if ($rep->f_stage >= $rep->inst_extension) {
				// get and check extension
				$rep->f_extension = trim($this->Input->post('repository_extension'));
				if ($rep->f_extension != '') {
					$exts = $this->getExtensionList(array(
						'languages' => $this->languages,
						'match'		=> 'exact',
						'names'		=> $rep->f_extension,
						'states'	=> $states,
						'sets'		=> 'history'
					));
				} else
					$exts = array();
				$ok = count($exts)>0;
				if (!$ok) $rep->f_stage = $rep->inst_extension;
			} // if
			
			if ($rep->f_stage >= $rep->inst_lickey) {
				// get license key
				$rep->f_lickey = trim($this->Input->post('repository_lickey'));
				if ($rep->f_lickey=='' 
					&& ($exts[0]->type=='private' ||
						($exts[0]->type=='commercial' && !intval($exts[0]->demo)) ) ) {
					$rep->f_lickey_msg = 'lickeyrequired';
					$ok = false;
					$rep->f_stage = $rep->inst_lickey;
				} // if
			} // if

			if ($rep->f_stage >= $rep->inst_actions) {
				// get enable states
				$enableActions = $this->Input->post('repository_enable');
				if (!is_array($enableActions)) $this->redirect($rep->homeLink);
			} // if
				
			if ($rep->f_stage==$rep->inst_actions && count($enableActions)>0) {
				// install!!!!
				$act = false;
				$actions = array();
				$this->addActions($rep->f_extension, $states, $actions, $act);
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
				} // if
			} // if
			
			if ($rep->f_stage == $rep->inst_showlog)
				$this->redirect($this->createUrl(array('update'=>'database')));
			
			if ($ok) $rep->f_stage++;
		} else {
			// parse name.version
			$matches = array();
			if (preg_match('#^([a-zA-Z0-9_-]+)\.([0-9]+)$#', $aParams, $matches)) {
				$rep->f_stage = $rep->inst_lickey;
				$s = $matches[2] % 10;
				if ($s <= 2) $rep->f_alpha = true;
				if ($s <= 5) $rep->f_beta = true;
				if ($s <= 8) $rep->f_rc = true;
				$rep->f_extension = $matches[1];
				
				// get license key and default states
				$q = $db->prepare("select * from `tl_repository_installs` where `extension`=?")
						->execute($rep->f_extension);
				if ($q->next()) {
					$rep->f_lickey = $q->lickey;
					if ((int)$q->alpha>0) $rep->f_alpha = true;
					if ((int)$q->beta>0) $rep->f_beta = true;
					if ((int)$q->rc>0) $rep->f_rc = true;
				} // if
			} // if
		} // if
		
		// get extension list
		if ($rep->f_stage >= $rep->inst_extension) {
			$states = $this->stateList($rep->f_alpha, $rep->f_beta, $rep->f_rc, $rep->f_stable);
			$options = array(
				'languages' => $this->languages,
				'states'	=> $states
			);
			if ($rep->f_stage > $rep->inst_extension) {
				$options['match'] = 'exact';
				$options['names'] = $rep->f_extension;
			} // if
			$exts = $this->getExtensionList($options);
			$rep->extensions = array();
			foreach ($exts as $e) $rep->extensions[] = $e->name;
			if (count($rep->extensions)>0) natcasesort($rep->extensions);
		} // if
		
		// skip license key if not commercial or private
		if ($rep->f_stage==$rep->inst_lickey && $exts[0]->type!='commercial' && $exts[0]->type!='private') 
			$rep->f_stage++;
		
		if ($rep->f_stage >= $rep->inst_actions) {
			$act = '';
			$rep->actions = array();
			$this->addActions($rep->f_extension, $states, $rep->actions, $act);
			if ($act != 'ok') $rep->f_stage = $rep->inst_error;
			if (is_array($enableActions))
				foreach ($rep->actions as &$act)
					$act->enabled = in_array($act->extension, $enableActions);
		} // if

		if ($rep->f_stage < $rep->inst_error) $rep->f_submit = 'continue';
	} // install

	/**
	 * Check / update database
	 */
	protected function update()
	{
		$rep = &$this->Template->rep;
		// return from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_cancelbutton'])) $this->redirect($rep->homeLink);
			$sql = deserialize($this->Input->post('sql'));
			if (is_array($sql)) {
				foreach ($sql as $key) {
					if (isset($_SESSION['sql_commands'][$key])) {
						$this->Database->query(str_replace('DEFAULT CHARSET=utf8;', 'DEFAULT CHARSET=utf8 COLLATE ' . $GLOBALS['TL_CONFIG']['dbCollation'] . ';', $_SESSION['sql_commands'][$key]));
					} // if
				} // foreach
			} // if
			$_SESSION['sql_commands'] = array();
		} // if
		$this->import('DatabaseInstaller');
		$rep->dbUpdate = $this->DatabaseInstaller->makeSqlForm();
		if ($rep->dbUpdate != '') {
			$rep->f_submit = 'update';
			$rep->f_cancel = 'cancel';
		} else
			$rep->f_cancel = 'ok';
	} // update

	/**
	 * Uninstall an extension
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
			$rep->f_stage = (int)$this->Input->post('repository_stage');
			$rep->f_extension = trim($this->Input->post('repository_extension'));
			
			if ($rep->f_stage == $rep->uist_showlog)
				$this->redirect($this->createUrl(array('update'=>'database')));
				
			if ($rep->f_stage == $rep->uist_confirm) {
				// uninstall files
				$rep->log = '';
				if ($this->uninstallExtension($rep->f_extension))
					$rep->f_stage = $rep->uist_error;
				else
					$rep->f_stage = $rep->uist_showlog;
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
						$f = new File($filerel);
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
			$this->log('Extension "'. $aName .'" has been updated to version "'. Repository::formatVersion($aVersion) .'"', 'RepositoryManager::updateExtension()', TL_REPOSITORY);
		} // try
		catch (Exception $exc) {
			$rep->log .= 
				"<div class=\"color_red\">\n".
				str_replace("\n", "<br/>\n", $exc->getMessage()) . "<br/>\n" . 
				$exc->getFile() . '[' .$exc->getLine() . ']'.
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
			$dstfile = new File($zipname);
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
							$f = new File($filerel);
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
			$this->log('Extension "'. $aName .'" version "'. Repository::formatVersion($aVersion) .'" has been installed', 'RepositoryManager::installExtension()', TL_REPOSITORY);
		} // try
		catch (Exception $exc) {
			$rep->log .= 
				"<div class=\"color_red\">\n".
				str_replace("\n", "<br/>\n", $exc->getMessage()) . "<br/>\n" . 
				$exc->getFile() . '[' .$exc->getLine() . ']'.
				"</div>\n";
			error_log(sprintf('Extension Manager: %s in %s on line %s', $exc->getMessage(), $exc->getFile(), $exc->getLine()));
			$err = true;
		} // catch
		return $err;
	} // installExtension
	
	/**
	 * Uninstall the files of an extension.
	 * @param string $aName Name of the extension to install/update.
	 * @param int $aVersion The extension version to install/update to.
	 * @param string $aKey License key for commercial or private extensions.
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
			$this->log('Extension "'. $aName .'" has been uninstalled', 'RepositoryManager::uninstallExtension()', TL_REPOSITORY);
		} // try
		catch (Exception $exc) {
			$rep->log .= 
				"<div class=\"color_red\">\n".
				$exc->getMessage() . "<br/>\n" . 
				$exc->getFile() . '[' .$exc->getLine() . ']'.
				"</div>\n";
			error_log(sprintf('Extension Manager: %s in %s on line %s', $exc->getMessage(), $exc->getFile(), $exc->getLine()));
			$err = true;
		} // catch
		return $err;
	} // uninstallExtension
	
	/**
	 * Create comma separated list of states
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
	 */
	private function addActions($aName, $aStates, &$aActions, &$aAction, $aParent='', $aParentVersion=0, $aMinversion=0, $aMaxversion=0)
	{
		$db = &$this->Database;
		
		// prepare action record
		$action = (object)array(
			'extension'	=> $aName,
			'version'	=> 0,
			'build'		=> 0,
			'action'	=> 'none',
			'enabled'	=> true,
			'status'	=> array()
		);
		$aActions[] = $action;

		// finding the extension:
		//
		// - First try with the parent extension states in $aStates, and in case the 
		//	 extension is allready installed add the update states of the installed 
		//	 extension.
		//
		// - While extension is not found, add states stable, rc, beta and alfa one 
		//   after the other an retry each time a new state was added
		
		$states = $aStates; // inherit parent flags
						
		// get update flags of installed extension
		$q = $db->prepare("select * from `tl_repository_installs` where `extension`=?")
				->execute($aName);		
		if ($q->next()) {
			// add update flags of installed extension
			$sa = explode(',',$states);
			if ((int)$q->alpha>0 && !in_array('alpha', $sa)) $sa[] = 'alpha';
			if ((int)$q->beta>0 && !in_array('beta', $sa)) $sa[] = 'beta';
			if ((int)$q->rc>0 && !in_array('rc', $sa)) $sa[] = 'rc';
			if ((int)$q->stable>0 && !in_array('stable', $sa)) $sa[] = 'stable';
			$states = implode(',',$sa);
		} // if
		
		// get most current release
		do {
			$exts = $this->getExtensionList(array(
				'match' 	=> 'exact',
				'names' 	=> $aName,
				'languages'	=> $this->languages,
				'states'	=> $states,
				'sets'		=> 'dependencies'
			));
			$retry = false;
			if (count($exts)<1) {
				$sa = explode(',',$states);
				foreach (array('stable','rc','beta','alpha') as $s)
					if (!in_array($s, $sa)) {
						$sa[] = $s;
						$states = implode(',',$sa);
						$retry = true;
						break;
					} // if
			} // if
		} while ($retry);
		
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
				if ((int)$q->error>0) $action->status[] = (object)array('color'=>'red', 'text'=>'errorinstall');
				if ($q->version < $ext->version || $q->build < $ext->build) {
					$action->action = 'update';
				} else {
					if ((int)$q->error==0) $action->status[] = (object)array('color'=>'green', 'text'=>'uptodate');
					$action->action = 'validate';
					$action->enabled = false;
				} // if
				if ($aAction=='') $aAction = 'ok';
			} else {
				if ($aParent!='' && property_exists($ext, 'soapwsdl')) {
					$action->status[] = (object)array(
						'color'=>'red', 
						'text'=>'extneedkey',
					);
					$aAction = 'failed';
				} else {
					$action->action = 'install';
					if ($aAction=='') $aAction = 'ok';
				} // if
			} // if
			
			// parent compatibility check
			if (($aMinversion>0 && $ext->version<$aMinversion) ||
				($aMaxversion>0 && $ext->version>$aMaxversion) )
				$action->status[] = (object)array(
					'color'	=> 'darkorange', 
					'text'	=> 'notapproved', 
					'par1'	=> $aParent, 
					'par2'	=> Repository::formatVersion($aParentVersion)
				);
				
			// typolight compatibility check
			$tlversion = Repository::encodeVersion(VERSION.'.'.BUILD);
			if (($ext->coreminversion>0 && $tlversion<$ext->coreminversion) ||
				($ext->coremaxversion>0 && $tlversion>$ext->coremaxversion) )
				$action->status[] = (object)array(
					'color'	=> 'darkorange', 
					'text'	=> 'notapproved',
					'par1'	=> 'TYPOlight',
					'par2'	=> VERSION.'.'.BUILD
				);
				
			// add dependencies
			if (property_exists($ext, 'dependencies')) 
				foreach ($ext->dependencies as $dep) {
					$take = true;
					foreach ($aActions as $a)
						if ($a->extension == $dep->extension) {
							$take = false;
							break;
						} // if
					if ($take) {
						$this->addActions(
							$dep->extension, 
							$aStates, 
							$aActions, 
							$aAction,
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

?>