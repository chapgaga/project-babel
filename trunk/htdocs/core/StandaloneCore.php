<?php
/* Project Babel
 *
 * Author: Xin, Liu (Livid)
 * File: /htdocs/core/StandaloneCore.php
 * Usage: Standalone Logic
 * Format: 1 tab indent(4 spaces), LF, UTF-8, no-BOM
 *  
 * Subversion Keywords:
 *
 * $Id: StandaloneCore.php 76 2007-02-07 00:24:19Z livid $
 * $LastChangedDate: 2007-02-07 08:24:19 +0800 (Wed, 07 Feb 2007) $
 * $LastChangedRevision: 76 $
 * $LastChangedBy: livid $
 * $URL: http://svn.cn.v2ex.com/svn/babel/trunk/htdocs/core/StandaloneCore.php $
 *
 * Copyright (C) 2006 Livid Liu <v2ex.livid@mac.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

if (V2EX_BABEL == 1) {
	/* The most important file */
	require('core/Settings.php');
	
	/* 3rdParty PEAR cores */
	ini_set('include_path', BABEL_PREFIX . '/libs/pear' . PATH_SEPARATOR . ini_get('include_path'));
	require_once('Cache/Lite.php');
	require_once('HTTP/Request.php');
	require_once('Crypt/Blowfish.php');
	
	/* 3rdparty Zend Framework cores */
	ini_set('include_path', BABEL_PREFIX . '/libs/zf/' . ZEND_FRAMEWORK_VERSION . PATH_SEPARATOR . ini_get('include_path'));
	require_once('Zend/Json.php');
	require_once('Zend/Cache.php');

	/* built-in cores */
	require('core/Vocabularies.php');
	require('core/Utilities.php');
	require('core/UserCore.php');
	require('core/NodeCore.php');
	require('core/TopicCore.php');
	require('core/ZenCore.php');
	require('core/GeoCore.php');
	require('core/ChannelCore.php');
	require('core/URLCore.php');
	require('core/ImageCore.php');
	require('core/ValidatorCore.php');
} else {
	die('<strong>Project Babel</strong><br /><br />Made by V2EX | software for internet');
}

/* S Standalone class */

class Standalone {
	public $User;

	public $db;
	
	/* S module: constructor and destructor */

	public function __construct() {
		
		check_env();
		
		$this->db = mysql_connect(BABEL_DB_HOSTNAME . ':' . BABEL_DB_PORT, BABEL_DB_USERNAME, BABEL_DB_PASSWORD);
		mysql_select_db(BABEL_DB_SCHEMATA);
		mysql_query("SET NAMES utf8");
		mysql_query("SET CHARACTER SET utf8");
		mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'");
		session_set_cookie_params(2592000);
		session_start();
		$this->User = new User('', '', $this->db);
		$this->Validator =  new Validator($this->db, $this->User);
		if (!isset($_SESSION['babel_ua'])) {
			$_SESSION['babel_ua'] = $this->Validator->vxGetUserAgent();
		}
		$this->URL = new URL();
		global $CACHE_LITE_OPTIONS_SHORT;
		$this->cs = new Cache_Lite($CACHE_LITE_OPTIONS_SHORT);
		global $CACHE_LITE_OPTIONS_LONG;
		$this->cl = new Cache_Lite($CACHE_LITE_OPTIONS_LONG);
	}
	
	public function __destruct() {
		mysql_close($this->db);
	}
	
	/* E module: constructor and destructor */
	
	/* S public modules */

	public function vxGoHome() {
		header('Location: /');
	}
	
	public function vxRecvPortrait() {
		if ($this->User->vxIsLogin()) {
			if (isset($_FILES['usr_portrait'])) {
				$ul = $_FILES['usr_portrait'];
				
				if (substr($ul['type'], 0, 5) == 'image') {
					switch ($ul['type']) {
						case 'image/jpeg':
						case 'image/jpg':
						case 'image/pjpeg':
							$ext = '.jpg';
							break;
						case 'image/gif':
							$ext = '.gif';
							break;
						case 'image/png':
						case 'image/x-png':
							$ext = '.png';
							break;
						default:
							header('Content-type: text/html; charset=UTF-8');
							echo("<script>alert('你传的不是照片吧？');location.href='" . $this->URL->vxGetUserModify() . "'</script>");
							die('REDIRECTING...');
							break;
					}
					move_uploaded_file($ul["tmp_name"], BABEL_PREFIX . '/tmp/' . $this->User->usr_id . $ext);

					if (isset($_POST['fx'])) {
						$fx = strtolower(trim($_POST['fx']));
						if (IM_ENABLED) {
							switch ($fx) {
								default:
									break;
								case 'lividark':
									Image::vxLividark(BABEL_PREFIX . '/tmp/' . $this->User->usr_id . $ext);
									break;
								case 'memory':
									Image::vxMemory(BABEL_PREFIX . '/tmp/' . $this->User->usr_id . $ext);
									break;
							}
						}
					}
					
					Image::vxGenUserPortraits($ext, $this->User->usr_id, $this->db);
					
					unlink(BABEL_PREFIX . '/tmp/' . $this->User->usr_id . $ext);
					if ($this->User->usr_portrait == '') {
						$sql = "UPDATE babel_user SET usr_portrait = usr_id WHERE usr_id = {$this->User->usr_id} LIMIT 1";
						mysql_query($sql, $this->db);
					}
					header('Content-type: text/html; charset=UTF-8');
					echo("<script>alert('你的头像已经成功上传！');location.href='" . $this->URL->vxGetUserModify() . "'</script>");
				} else {
					header('Content-type: text/html; charset=UTF-8');
					echo("<script>alert('你传的不是照片吧？');location.href='" . $this->URL->vxGetUserModify() . "'</script>");
					die('REDIRECTING...');
				}
			} else {
				header('Location: ' . $this->URL->vxGetUserModify());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetUserModify()));
		}
	}
	
	public function vxRecvSavepoint() {
		if ($this->User->vxIsLogin()) {
			if (isset($_POST['url'])) {
				$url = trim($_POST['url']);
				if (strlen($url) == 0) {
					return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
				}
				if (substr(strtolower($url), 0, 7) == 'http://') {
					$url = substr($url, 7, strlen($url) - 7);
				}
				$url = mysql_real_escape_string(strip_quotes($url), $this->db);
				$sql = "SELECT svp_id FROM babel_savepoint WHERE svp_uid = {$this->User->usr_id} AND svp_url = '{$url}'";
				$rs = mysql_query($sql, $this->db);
				if (mysql_num_rows($rs) == 0) {
					mysql_free_result($rs);
					$sql = "SELECT svp_id FROM babel_savepoint WHERE svp_uid = {$this->User->usr_id}";
					$rs = mysql_query($sql, $this->db);
					if (mysql_num_rows($rs) < BABEL_SVP_LIMIT) {
						mysql_free_result($rs);
						$sql = "INSERT INTO babel_savepoint (svp_uid, svp_url, svp_created, svp_lastupdated) VALUES({$this->User->usr_id}, '{$url}', " . time() . ", " . time() . ")";
						mysql_query($sql, $this->db);
						if (mysql_affected_rows($this->db) == 1) {
							return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(1));
						} else {
							return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(0));
						}
					} else {
						mysql_free_result($rs);
						return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(3));
					}
				} else {
					mysql_free_result($rs);
					return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(2));
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
		}
	}
	
	public function vxSavepointErase() {
		if ($this->User->vxIsLogin()) {
			if (isset($_GET['savepoint_id'])) {
				$savepoint_id = intval($_GET['savepoint_id']);
				$sql = "SELECT svp_id, svp_uid FROM babel_savepoint WHERE svp_id = {$savepoint_id}";
				$rs = mysql_query($sql, $this->db);
				if (mysql_num_rows($rs) == 1) {
					
					$S = mysql_fetch_object($rs);
					mysql_free_result($rs);
					if ($S->svp_uid == $this->User->usr_id) {
						$S = null;
						$sql = "DELETE FROM babel_savepoint WHERE svp_id = {$savepoint_id} LIMIT 1";					
						mysql_query($sql, $this->db);
						if (mysql_affected_rows($this->db) == 1) {
							$this->cs->remove('babel_user_savepoints_' . strval($this->User->usr_id));
							return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(6));
						} else {
							return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(7));
						}
					} else {
						$S = null;
						return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(5));
					}
				} else {
					mysql_free_result($rs);
					return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(4));
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome(4));
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetUserOwnHome()));
		}
	}
	
	public function vxRecvZENProject() {
		if ($this->User->vxIsLogin()) {
			if (isset($_POST['zpr_title'])) {
				$zpr_title = make_single_safe($_POST['zpr_title']);
				if (mb_strlen($zpr_title, 'UTF-8') > 80) {
					$_SESSION['babel_zen_message'] = '项目标题太长了';
					return $this->URL->vxToRedirect($this->URL->vxGetZEN());
				} else {
					if (mb_strlen($zpr_title, 'UTF-8') == 0) {
						$_SESSION['babel_zen_message'] = '你忘记填写新项目的标题了';
						return $this->URL->vxToRedirect($this->URL->vxGetZEN());
					} else {
						$sql = "SELECT COUNT(*) FROM babel_zen_project WHERE zpr_uid = {$this->User->usr_id}";
						$rs = mysql_query($sql, $this->db);
						$count = mysql_result($rs, 0, 0);
						mysql_free_result($rs);
						if ($count > (BABEL_ZEN_PROJECT_LIMIT - 1)) {
							$_SESSION['babel_zen_message'] = '目前我们的系统只能支持每个会员创建最多 ' . BABEL_ZEN_PROJECT_LIMIT . ' 个项目，我们正在积极地扩展系统的能力，以支持存储更多的项目';
						} else {
							if (get_magic_quotes_gpc()) {
								$zpr_title = stripslashes($zpr_title);
							}
							$zpr_title = mysql_real_escape_string($zpr_title, $this->db);
							$t = time();
							$sql = "INSERT INTO babel_zen_project(zpr_uid, zpr_private, zpr_title, zpr_progress, zpr_created, zpr_lastupdated, zpr_lasttouched, zpr_completed) VALUES({$this->User->usr_id}, 0, '{$zpr_title}', 0, {$t}, {$t}, 0, 0)";
							mysql_query($sql, $this->db);
							if (mysql_affected_rows($this->db) == 1) {
								$_SESSION['babel_zen_message'] = '新项目添加成功';
							} else {
								$_SESSION['babel_zen_message'] = '新项目添加失败';
							}
						}
						return $this->URL->vxToRedirect($this->URL->vxGetZEN());
					}
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetZEN());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetZEN()));
		}
	}
	
	public function vxEraseZENProject() {
		if (isset($_GET['zen_project_id'])) {
			$zen_project_id = intval($_GET['zen_project_id']);
			$sql = "SELECT zpr_id, zpr_uid FROM babel_zen_project WHERE zpr_id = {$zen_project_id}";
			$rs = mysql_query($sql, $this->db);
			if (!$Project = mysql_fetch_object($rs)) {
				$zen_project_id = 0;
			}
		} else {
			$zen_project_id = 0;
		}
		if ($this->User->vxIsLogin()) {
			if ($zen_project_id != 0) {
				if ($Project->zpr_uid == $this->User->usr_id) {
					$sql = "DELETE FROM babel_zen_project WHERE zpr_id = {$zen_project_id}";
					mysql_query($sql, $this->db);
					if (mysql_affected_rows($this->db) == 1) {
						$sql = "DELETE FROM babel_zen_task WHERE zta_pid = {$zen_project_id}";
						mysql_query($sql, $this->db);
						$_SESSION['babel_zen_message'] = '项目删除成功';
					} else {
						$_SESSION['babel_zen_message'] = '项目删除失败';
					}
				} else {
					$_SESSION['babel_zen_message'] = '你不能也无法删除别人的项目';
				}
			} else {
				$_SESSION['babel_zen_message'] = '你要删除的项目不存在';
			}
			return $this->URL->vxToRedirect($this->URL->vxGetZEN());
		} else {
			if ($zen_project_id != 0) {
				return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetEraseZENProject($zen_project_id)));
			} else {
				$_SESSION['babel_zen_message'] = '你要删除的项目不存在';
				return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetZEN()));
			}
		}
	}
	
	public function vxRecvZENTask() {
		if ($this->User->vxIsLogin()) {
			if (isset($_POST['zta_title']) && isset($_GET['zen_project_id'])) {
				$zta_title = make_single_safe($_POST['zta_title']);
				$zen_project_id = intval($_GET['zen_project_id']);
				
				$sql = "SELECT zpr_id FROM babel_zen_project WHERE zpr_id = {$zen_project_id} AND zpr_uid = {$this->User->usr_id}";
				$rs = mysql_query($sql);
				if (mysql_num_rows($rs) == 1) {
					mysql_free_result($rs);
					if (mb_strlen($zta_title, 'UTF-8') > 80) {
						$_SESSION['babel_zen_message'] = '任务标题太长了';
						return $this->URL->vxToRedirect($this->URL->vxGetZEN());
					} else {
						if (mb_strlen($zta_title, 'UTF-8') == 0) {
							$_SESSION['babel_zen_message'] = '你忘记填写新任务的标题了';
							return $this->URL->vxToRedirect($this->URL->vxGetZEN());
						} else {
							$sql = "SELECT COUNT(*) FROM babel_zen_task WHERE zta_pid = {$zen_project_id} AND zta_progress = 0";
							$rs = mysql_query($sql, $this->db);
							$count = mysql_result($rs, 0, 0);
							mysql_free_result($rs);
							if ($count > (BABEL_ZEN_TASK_LIMIT - 1)) {
								$_SESSION['babel_zen_message'] = '目前我们的系统只能支持每个会员为单独一个项目创建最多 ' . BABEL_ZEN_TASK_LIMIT . ' 个待办任务，我们正在积极地扩展系统的能力，以支持存储更多的任务';
							} else {
								if (get_magic_quotes_gpc()) {
									$zta_title = stripslashes($zta_title);
								}
								$zta_title = mysql_real_escape_string($zta_title, $this->db);
								$t = time();
								$sql = "INSERT INTO babel_zen_task(zta_uid, zta_pid, zta_title, zta_progress, zta_created, zta_lastupdated, zta_completed) VALUES({$this->User->usr_id}, {$zen_project_id}, '{$zta_title}', 0, {$t}, {$t}, 0)";
								mysql_query($sql, $this->db);
								if (mysql_affected_rows($this->db) == 1) {
									$sql = "UPDATE babel_zen_project SET zpr_lasttouched = {$t}, zpr_progress = 0 WHERE zpr_id = {$zen_project_id}";
									mysql_unbuffered_query($sql, $this->db);
									$_SESSION['babel_zen_message'] = '新任务添加成功';
								} else {
									$_SESSION['babel_zen_message'] = '新任务添加失败';
								}
							}
							return $this->URL->vxToRedirect($this->URL->vxGetZEN());
						}
					}
				} else {
					mysql_free_result($rs);
					$_SESSION['babel_zen_message'] = '指定的项目不存在，无法添加任务';
					return $this->URL->vxToRedirect($this->URL->vxGetZEN());
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetZEN());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetZEN()));
		}
	}
	
	public function vxChangeZENTaskDone() {
		if ($this->User->vxIsLogin()) {
			if (isset($_GET['zen_task_id'])) {
				$zen_task_id = intval($_GET['zen_task_id']);
				$sql = "SELECT zta_id, zta_pid FROM babel_zen_task WHERE zta_id = {$zen_task_id} AND zta_uid = {$this->User->usr_id}";
				$rs = mysql_query($sql);
				if (mysql_num_rows($rs) == 1) {
					$Task = mysql_fetch_object($rs);
					mysql_free_result($rs);
					$t = time();
					$sql = "UPDATE babel_zen_task SET zta_progress = 1, zta_completed = {$t} WHERE zta_id = {$zen_task_id}";
					mysql_unbuffered_query($sql);
					$_SESSION['babel_zen_message'] = '一个任务已经完成！';
					$sql = "SELECT zta_id FROM babel_zen_task WHERE zta_pid = {$Task->zta_pid} AND zta_progress = 0";
					$rs = mysql_query($sql, $this->db);
					if (mysql_num_rows($rs) == 0) {
						mysql_free_result($rs);
						$sql = "UPDATE babel_zen_project SET zpr_progress = 1, zpr_completed = {$t} WHERE zpr_id = {$Task->zta_pid} LIMIT 1";
						mysql_unbuffered_query($sql);
						$_SESSION['babel_zen_message'] = '恭喜，你完成了一个项目！';
					} else {
						mysql_free_result($rs);
						$sql = "UPDATE babel_zen_project SET zpr_lasttouched = {$t} WHERE zpr_id = {$Task->zta_pid} LIMIT 1";
						mysql_unbuffered_query($sql);
					}
					return $this->URL->vxToRedirect($this->URL->vxGetZEN());
				} else {
					mysql_free_result($rs);
					$_SESSION['babel_zen_message'] = '指定的任务不存在，无法改变任务进度';
					return $this->URL->vxToRedirect($this->URL->vxGetZEN());
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetZEN());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetZEN()));
		}
	}
	
	public function vxChangeZENProjectPermission() {
		if ($this->User->vxIsLogin()) {
			if (isset($_GET['zen_project_id'])) {
				$_zen_project_id = intval($_GET['zen_project_id']);
				$sql = "SELECT zpr_id, zpr_uid, zpr_private FROM babel_zen_project WHERE zpr_id = {$_zen_project_id} AND zpr_uid = {$this->User->usr_id}";
				$rs = mysql_query($sql);
				if (mysql_num_rows($rs) == 1) {
					$Project = mysql_fetch_object($rs);
					mysql_free_result($rs);
					$_t = time();
					if ($Project->zpr_private == 1) {
						$sql = "UPDATE babel_zen_project SET zpr_private = 0 WHERE zpr_id = {$_zen_project_id} LIMIT 1";
						$_SESSION['babel_zen_message'] = '项目已经设置为公开';
					} else {
						$sql = "UPDATE babel_zen_project SET zpr_private = 1 WHERE zpr_id = {$_zen_project_id} LIMIT 1";
						$_SESSION['babel_zen_message'] = '项目已经设置为隐藏';
					}
					mysql_unbuffered_query($sql);
					return $this->URL->vxToRedirect($this->URL->vxGetZEN());
				} else {
					mysql_free_result($rs);
					$_SESSION['babel_zen_message'] = '指定的项目不存在，无法改变';
					return $this->URL->vxToRedirect($this->URL->vxGetZEN());
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetZEN());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetZEN()));
		}
	}
	
	public function vxEraseZENTask() {
		if (isset($_GET['zen_task_id'])) {
			
			$zen_task_id = intval($_GET['zen_task_id']);
			$sql = "SELECT zta_id, zta_pid, zta_uid FROM babel_zen_task WHERE zta_id = {$zen_task_id}";
			
			$rs = mysql_query($sql, $this->db);
			if (!$Task = mysql_fetch_object($rs)) {
				$zen_task_id = 0;
			}
		} else {
			$zen_task_id = 0;
		}
		if ($this->User->vxIsLogin()) {
			if ($zen_task_id != 0) {
				if ($Task->zta_uid == $this->User->usr_id) {
					$sql = "DELETE FROM babel_zen_task WHERE zta_id = {$zen_task_id}";
					mysql_query($sql, $this->db);
					if (mysql_affected_rows($this->db) == 1) {
						$_SESSION['babel_zen_message'] = '任务删除成功';
					} else {
						$_SESSION['babel_zen_message'] = '任务删除失败';
					}
					$sql = "SELECT zta_id FROM babel_zen_task WHERE zta_pid = {$Task->zta_pid} AND zta_progress = 0";
					$rs_todo = mysql_query($sql, $this->db);
					$sql = "SELECT zta_id FROM babel_zen_task WHERE zta_pid = {$Task->zta_pid} AND zta_progress = 1";
					$rs_done = mysql_query($sql, $this->db);
					if ((mysql_num_rows($rs_todo) == 0) && (mysql_num_rows($rs_done) > 0)) {
						$sql = "UPDATE babel_zen_project SET zpr_progress = 1 WHERE zpr_id = {$Task->zta_pid}";
						mysql_unbuffered_query($sql);
					}
					mysql_free_result($rs_todo);
					mysql_free_result($rs_done);
				} else {
					$_SESSION['babel_zen_message'] = '你不能也无法删除别人的任务';
				}
			} else {
				$_SESSION['babel_zen_message'] = '你要删除的任务不存在';
			}
			return $this->URL->vxToRedirect($this->URL->vxGetZEN());
		} else {
			if ($zen_task_id != 0) {
				return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetEraseZENProject($zen_project_id)));
			} else {
				$_SESSION['babel_zen_message'] = '你要删除的任务不存在';
				return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetZEN()));
			}
		}
	}
	
	public function vxUndoneZENTask() {
		if (isset($_GET['zen_task_id'])) {
			
			$zen_task_id = intval($_GET['zen_task_id']);
			$sql = "SELECT zta_id, zta_pid, zta_uid FROM babel_zen_task WHERE zta_id = {$zen_task_id}";
			
			$rs = mysql_query($sql, $this->db);
			if (!$Task = mysql_fetch_object($rs)) {
				$zen_task_id = 0;
			}
		} else {
			$zen_task_id = 0;
		}
		if ($this->User->vxIsLogin()) {
			if ($zen_task_id != 0) {
				if ($Task->zta_uid == $this->User->usr_id) {
					$sql = "UPDATE babel_zen_task SET zta_progress = 0 WHERE zta_id = {$zen_task_id}";
					mysql_query($sql, $this->db);
					if (mysql_affected_rows($this->db) == 1) {
						$_SESSION['babel_zen_message'] = '任务回到待办状态';
					} else {
						$_SESSION['babel_zen_message'] = '任务状态没有改变';
					}
					$sql = "UPDATE babel_zen_project SET zpr_progress = 0 WHERE zpr_id = {$Task->zta_pid}";
					mysql_unbuffered_query($sql, $this->db);
				} else {
					$_SESSION['babel_zen_message'] = '你不能也无法改变别人的任务进度';
				}
			} else {
				$_SESSION['babel_zen_message'] = '你要改变的任务不存在';
			}
			return $this->URL->vxToRedirect($this->URL->vxGetZEN());
		} else {
			if ($zen_task_id != 0) {
				return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetEraseZENProject($zen_project_id)));
			} else {
				$_SESSION['babel_zen_message'] = '你要改变的任务不存在';
				return $this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetZEN()));
			}
		}
	}
	
	public function vxDisableUserID() {
		if ($this->User->vxIsLogin() && $this->User->usr_id == 1 && $this->User->usr_nick == 'Livid') {
			if (isset($_GET['user_id'])) {
				$user_id = intval($_GET['user_id']);
				if ($user_id > 0 && $user_id != 1) {
					$sql = "SELECT usr_nick FROM babel_user WHERE usr_id = {$user_id}";
					$_rs = mysql_query($sql);
					if ($_u = mysql_fetch_object($_rs)) {
						$_rs = null;
						$sql = "UPDATE babel_user SET usr_password = 'DISABLED' WHERE usr_id = {$user_id}";
						mysql_unbuffered_query($sql);
						return $this->URL->vxToRedirect($this->URL->vxGetUserHome($_u->usr_nick));
					} else {
						$_rs = null;
						return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
					}
				} else {
					return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
		}
	}
	
	public function vxEraseZeroTopicUserID() {
		if ($this->User->vxIsLogin() && $this->User->usr_id == 1) {
			if (isset($_GET['user_id'])) {
				$user_id = intval($_GET['user_id']);
				if ($user_id > 0 && $user_id != 1) {
					$sql = "SELECT usr_nick FROM babel_user WHERE usr_id = {$user_id}";
					$_rs = mysql_query($sql);
					if ($_u = mysql_fetch_object($_rs)) {
						$_rs = null;
						$sql = "DELETE FROM babel_topic WHERE tpc_uid = {$user_id} AND tpc_posts = 0";
						mysql_unbuffered_query($sql);
						return $this->URL->vxToRedirect($this->URL->vxGetUserHome($_u->usr_nick));
					} else {
						$_rs = null;
						return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
					}
				} else {
					return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
				}
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetUserOwnHome());
		}
	}
	
	public function vxRemoveMessage() {
		if ($this->User->vxIsLogin()) {
			if (isset($_GET['message_id'])) {
				$message_id = intval($_GET['message_id']);
				$sql = "SELECT msg_id, msg_sid, msg_rid, msg_rdeleted, msg_sdeleted FROM babel_message WHERE msg_id = {$message_id}";
				$rs = mysql_query($sql, $this->db);
				if ($Message = mysql_fetch_object($rs)) {
					$_action = false;
					$_return = '';
					if (isset($_SESSION['babel_page_message'])) {
						$_p = intval($_SESSION['babel_page_message']);
					} else {
						$_p = 1;
					}
					if (intval($Message->msg_sid) == intval($this->User->usr_id)) {
						if (intval($Message->msg_rdeleted) == 1) {
							$_action = 'erase';
						} else {
							$_action = 'msg_sdeleted';
						}
						$_return = '/message/sent/' . $_p . '.vx';
					}
					if (intval($Message->msg_rid) == intval($this->User->usr_id)) {
						if (intval($Message->msg_sdeleted) == 1) {
							$_action = 'erase';
						} else {
							$_action = 'msg_rdeleted';
						}
						$_return = '/message/inbox/' . $_p . '.vx';
					}
					if ($_action) {
						if ($_action == 'erase') {
							$sql = "DELETE FROM babel_message WHERE msg_id = {$message_id} LIMIT 1";
						} else {
							$sql = "UPDATE babel_message SET {$_action} = 1 WHERE msg_id = {$message_id} LIMIT 1";
						}
						mysql_unbuffered_query($sql);
					}
					if ($_return == '') {
						if (isset($_SERVER['HTTP_REFERER'])) {
							if (trim($_SERVER['HTTP_REFERER']) != '') {
								$_return = $_SERVER['HTTP_REFERER'];
							} else {
								$_return = $this->URL->vxGetMessageHome();
							}
						} else {
							$_return = $this->URL->vxGetMessageHome();
						}
					}
					mysql_free_result($rs);
				} else {
					if (isset($_SERVER['HTTP_REFERER'])) {
						if (trim($_SERVER['HTTP_REFERER']) != '') {
							$_return = $_SERVER['HTTP_REFERER'];
						} else {
							$_return = $this->URL->vxGetMessageHome();
						}
					} else {
						$_return = $this->URL->vxGetMessageHome();
					}
					mysql_free_result($rs);
				}
				return $this->URL->vxToRedirect($_return);
			} else {
				return $this->URL->vxToRedirect($this->URL->vxGetMessageHome());
			}
		} else {
			return $this->URL->vxToRedirect($this->URL->vxGetMessageHome());
		}
	}
	
	public function vxTopicMoveTo() {
		if ($this->User->vxIsLogin() && $this->User->usr_id == 1) {
			if (isset($_GET['topic_id']) && isset($_GET['board_id'])) {
				$topic_id = intval($_GET['topic_id']);
				$board_id = intval($_GET['board_id']);
				if ($this->Validator->vxExistTopic($topic_id) && $this->Validator->vxExistNode($board_id)) {
					$sql = "UPDATE babel_topic SET tpc_pid = {$board_id}, tpc_lasttouched = UNIX_TIMESTAMP() WHERE tpc_id = {$topic_id} LIMIT 1";
					mysql_unbuffered_query($sql, $this->db);
					return $this->URL->vxToRedirect($this->URL->vxGetTopicView($topic_id));
				} else {
					die();
				}
			} else {
				die();
			}
		} else {
			die();
		}
	}
	
	/* S module: JSON Home Tab Latest */
	
	public function vxJSONHomeTabLatest() {
		$_data = new stdClass();
		$sql = 'SELECT nod_id, nod_name, nod_title, nod_topics FROM babel_node WHERE nod_level > 1 ORDER BY nod_topics DESC LIMIT 8';
		$rs = mysql_query($sql, $this->db);
		$_data->boards = array();
		$i = 0;
		while ($Node = mysql_fetch_object($rs)) {
			$i++;
			$_data->boards[$i] = new stdClass();
			$_data->boards[$i]->nod_id = $Node->nod_id;
			$_data->boards[$i]->nod_name = $Node->nod_name;
			$_data->boards[$i]->nod_title = $Node->nod_title;
			$_data->boards[$i]->nod_title_plain = make_plaintext($Node->nod_title);
			$_data->boards[$i]->nod_topics = $Node->nod_topics;
			$_data->boards[$i]->color = rand_color();
			$Node = null;
		}
		mysql_free_result($rs);
		$sql = 'SELECT usr_id, usr_nick, usr_gender, usr_portrait, tpc_id, tpc_title, tpc_posts, tpc_created, tpc_lasttouched, nod_id, nod_title, nod_name FROM babel_user, babel_topic, babel_node WHERE tpc_uid = usr_id AND tpc_pid = nod_id AND tpc_flag IN (0, 2) AND tpc_pid NOT IN ' . BABEL_NODES_POINTLESS . ' ORDER BY tpc_lasttouched DESC LIMIT 31';
		$rs = mysql_query($sql, $this->db);
		$_data->topics = array();
		$i = 0;
		while ($Topic = mysql_fetch_object($rs)) {
			$i++;
			$_data->topics[$i] = new stdClass();
			$_data->topics[$i]->usr_id = $Topic->usr_id;
			$_data->topics[$i]->usr_nick = $Topic->usr_nick;
			$_data->topics[$i]->usr_nick_plain = make_plaintext($Topic->usr_nick);
			$_data->topics[$i]->usr_gender = $Topic->usr_gender;
			$_data->topics[$i]->usr_portrait = $Topic->usr_portrait;
			$_data->topics[$i]->usr_portrait_img = $Topic->usr_portrait ? CDN_IMG . 'p/' . $Topic->usr_portrait . '_n.jpg' : CDN_IMG . 'p_' . $Topic->usr_gender . '_n.gif';
			$_data->topics[$i]->tpc_id = $Topic->tpc_id;
			$_data->topics[$i]->tpc_title = $Topic->tpc_title;
			$_data->topics[$i]->tpc_title_plain = make_plaintext($Topic->tpc_title);
			$_data->topics[$i]->tpc_posts = $Topic->tpc_posts;
			$_data->topics[$i]->tpc_created = $Topic->tpc_created;
			$_data->topics[$i]->tpc_created_relative = make_descriptive_time($Topic->tpc_created);
			$_data->topics[$i]->tpc_lasttouched = $Topic->tpc_lasttouched;
			$_data->topics[$i]->tpc_lasttouched_relative = make_descriptive_time($Topic->tpc_lasttouched);
			$_data->topics[$i]->nod_id = $Topic->nod_id;
			$_data->topics[$i]->nod_title = $Topic->nod_title;
			$_data->topics[$i]->nod_title_plain = make_plaintext($Topic->nod_title);
			$_data->topics[$i]->nod_name = $Topic->nod_name;
			if ($Topic->tpc_posts > 5) {
				$_data->topics[$i]->color = rand_color();
			} else {
				$_data->topics[$i]->color = rand_gray(2, 4);
			}
			$Topic = null;
		}
		mysql_free_result($rs);
		$_SESSION['babel_home_tab'] = 'latest';
		header('Content-type: text/plain; charset=utf-8');
		header('Cache-control: no-cache, must-revalidate');
		if (function_exists('json_encode')) {
			$encoded = json_encode($_data);
		} else {
			$encoded = Zend_Json::encode($_data);
		}
		echo $encoded;
	}
	
	public function vxJSONHomeTabSection() {
		if (!isset($_GET['section_id'])) {
			$this->vxJSONHomeTabLatest();
		} else {
			$section_id = intval($_GET['section_id']);
			$_data = new stdClass();
			$Node = new Node($section_id, $this->db);
			if ($Node->nod_id != 0) {
				$_data->node = new stdClass();
				$_data->node->nod_id = $Node->nod_id;
				$_data->node->nod_name = $Node->nod_name;
				$_data->node->nod_title = $Node->nod_title;
				$_data->node->nod_title_plain = make_plaintext($Node->nod_title);
			}
			$Node = null;
			$sql = 'SELECT nod_id, nod_name, nod_title, nod_topics FROM babel_node WHERE nod_sid = ' . $section_id . ' ORDER BY nod_topics DESC LIMIT 8';
			$rs = mysql_query($sql, $this->db);
			if (mysql_num_rows($rs) > 0) {
				$_SESSION['babel_home_tab'] = 'section:' . $_data->node->nod_id . ':' . $_data->node->nod_name;
			} else {
				$_SESSION['babel_home_tab'] = 'latest';
			}
			
			$_data->boards = array();
			$i = 0;
			while ($Node = mysql_fetch_object($rs)) {
				$i++;
				$_data->boards[$i] = new stdClass();
				$_data->boards[$i]->nod_id = $Node->nod_id;
				$_data->boards[$i]->nod_name = $Node->nod_name;
				$_data->boards[$i]->nod_title = $Node->nod_title;
				$_data->boards[$i]->nod_title_plain = make_plaintext($Node->nod_title);
				$_data->boards[$i]->nod_topics = $Node->nod_topics;
				$_data->boards[$i]->color = rand_color();
				$Node = null;
			}
			mysql_free_result($rs);
			$sql = 'SELECT usr_id, usr_nick, usr_gender, usr_portrait, tpc_id, tpc_title, tpc_posts, tpc_created, tpc_lasttouched, nod_id, nod_title, nod_name FROM babel_user, babel_topic, babel_node WHERE tpc_uid = usr_id AND tpc_pid = nod_id AND nod_sid = ' . $section_id . ' AND tpc_flag IN (0, 2) AND tpc_pid NOT IN ' . BABEL_NODES_POINTLESS . ' ORDER BY tpc_lasttouched DESC LIMIT 31';
			$rs = mysql_query($sql, $this->db);
			$_data->topics = array();
			$i = 0;
			while ($Topic = mysql_fetch_object($rs)) {
				$i++;
				$_data->topics[$i] = new stdClass();
				$_data->topics[$i]->usr_id = $Topic->usr_id;
				$_data->topics[$i]->usr_nick = $Topic->usr_nick;
				$_data->topics[$i]->usr_nick_plain = make_plaintext($Topic->usr_nick);
				$_data->topics[$i]->usr_gender = $Topic->usr_gender;
				$_data->topics[$i]->usr_portrait = $Topic->usr_portrait;
				$_data->topics[$i]->usr_portrait_img = $Topic->usr_portrait ? CDN_IMG . 'p/' . $Topic->usr_portrait . '_n.jpg' : CDN_IMG . 'p_' . $Topic->usr_gender . '_n.gif';
				$_data->topics[$i]->tpc_id = $Topic->tpc_id;
				$_data->topics[$i]->tpc_title = $Topic->tpc_title;
				$_data->topics[$i]->tpc_title_plain = make_plaintext($Topic->tpc_title);
				$_data->topics[$i]->tpc_posts = $Topic->tpc_posts;
				$_data->topics[$i]->tpc_created = $Topic->tpc_created;
				$_data->topics[$i]->tpc_created_relative = make_descriptive_time($Topic->tpc_created);
				$_data->topics[$i]->tpc_lasttouched = $Topic->tpc_lasttouched;
				$_data->topics[$i]->tpc_lasttouched_relative = make_descriptive_time($Topic->tpc_lasttouched);
				$_data->topics[$i]->nod_id = $Topic->nod_id;
				$_data->topics[$i]->nod_title = $Topic->nod_title;
				$_data->topics[$i]->nod_title_plain = make_plaintext($Topic->nod_title);
				$_data->topics[$i]->nod_name = $Topic->nod_name;
				if ($Topic->tpc_posts > 5) {
					$_data->topics[$i]->color = rand_color();
				} else {
					$_data->topics[$i]->color = rand_gray(2, 4);
				}
				$Topic = null;
			}
			mysql_free_result($rs);
			header('Content-type: text/plain; charset=utf-8');
			header('Cache-control: no-cache, must-revalidate');
			if (function_exists('json_encode')) {
				$encoded = json_encode($_data);
			} else {
				$encoded = Zend_Json::encode($_data);
			}
			echo $encoded;
		}
	}
	
	/* E module: JSON Section Latest */
	
	public function vxUserSettle() {
		if ($this->User->vxIsLogin()) {
			if (isset($_GET['geo'])) {
				$geo = strtolower(make_single_safe($_GET['geo']));
				$this->Geo = new Geo($geo);
				if ($this->Geo->geo->geo) {
					$sql = "UPDATE babel_user SET usr_geo = '{$geo}' WHERE usr_id = {$this->User->usr_id}";
					mysql_query($sql, $this->db);
					$this->URL->vxToRedirect($this->URL->vxGetUserMove());
				} else {
					$this->URL->vxToRedirect($this->URL->vxGetUserMove());
				}
			} else {
				$this->URL->vxToRedirect($this->URL->vxGetUserMove());
			}
		} else {
			$this->URL->vxToRedirect($this->URL->vxGetLogin($this->URL->vxGetUserMove()));
		}
	}
	
	/* E public modules */
	
}

/* E Standalone class */
?>
