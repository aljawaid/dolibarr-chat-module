<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/myclass.class.php
 * \ingroup chat
 * \brief   Example CRUD (Create/Read/Update/Delete) class.
 *
 * Put detailed description here.
 */

/** Includes */
//require_once DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php";
//require_once DOL_DOCUMENT_ROOT."/societe/class/societe.class.php";
//require_once DOL_DOCUMENT_ROOT."/product/class/product.class.php";

require_once DOL_DOCUMENT_ROOT."/chat/class/chatMessageAttachment.class.php";

/**
 * Put your class' description here
 */
class ChatMessage // extends CommonObject
{

    /** @var DoliDb Database handler */
	private $db;
    /** @var string Error code or message */
	public $error;
    /** @var array Several error codes or messages */
	public $errors = array();
    /** @var string Id to identify managed object */
	//public $element='myelement';
    /** @var string Name of table without prefix where object is stored */
	//public $table_element='mytable';
    /** @var int An example ID */
	public $id;
    /** @var mixed An example property */
	public $entity;
    /** @var mixed An example property */
	public $fk_user;
	/** @var mixed An example property */
	public $post_time;
        /** @var mixed An example property */
	public $text;
        /** @var mixed An example property */
	public $fk_user_to;
        /** @var mixed An example property */
	public $status;
        /** @var mixed An example property */
	public $fk_attachment;

	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;

		return 1;
	}
        
        /**
	 * Load object in memory from database
	 *
	 * @param int $id Id object
	 * @return int <0 if KO, >0 if OK
	 */
	public function fetch($id)
	{
		global $langs;

		$sql = "SELECT m.rowid, m.fk_user, m.fk_attachment, m.post_time, m.text, m.fk_user_to, m.status";
                $sql.= " FROM ".MAIN_DB_PREFIX."chat_msg as m";
                $sql.= " WHERE m.rowid = ".$id;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
                        $num = $this->db->num_rows($resql);
			if ($num) {
				$obj = $this->db->fetch_object($resql);

				$this->id = $obj->rowid;
				$this->fk_user = $obj->fk_user;
                                $this->fk_attachment = $obj->fk_attachment;
                                $this->post_time = $obj->post_time;
                                $this->text = $obj->text;
                                $this->fk_user_to = $obj->fk_user_to;
                                $this->status = $obj->status;
				//...
			}
			$this->db->free($resql);

			return $num;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	/**
	 * Create object into database
	 *
	 * @param User $user User that create
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function send($user, $notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;

		// Clean parameters
		if (isset($this->post_time)) {
			$this->post_time = trim($this->post_time);
		}
		if (isset($this->text)) {
			$this->text = trim($this->text);
		}
                if (isset($this->fk_user_to)) {
			$this->fk_user_to = trim($this->fk_user_to);
		}
                if (isset($this->status)) {
			$this->status = trim($this->status);
		}
                if (isset($this->fk_attachment)) {
			$this->fk_attachment = trim($this->fk_attachment);
		}
                
		// Check parameters
		// Put here code to add control on parameters values
		// Insert request
		$sql = "INSERT INTO " . MAIN_DB_PREFIX . "chat_msg(";
		$sql.= " entity,";
		$sql.= " fk_user,";
                $sql.= " fk_attachment,";
                $sql.= " post_time,";
                $sql.= " text,";
                $sql.= " fk_user_to,";
		$sql.= " status";

		$sql.= ") VALUES (";
		$sql.= " '" . $conf->entity . "',";
		$sql.= " '" . $user->id . "',";
                $sql.= " " . ($this->fk_attachment > 0 ? "'".$this->fk_attachment."'" : "null") . ",";
                $sql.= " " . ($this->post_time > 0 ? "'".$this->db->idate($this->post_time)."'" : "null") . ",";
                $sql.= " '" . $this->db->escape($this->text) . "',";
                $sql.= " " . ($this->fk_user_to > 0 ? "'".$this->fk_user_to."'" : "null") . ",";
		$sql.= " '" . $this->status . "'";

		$sql.= ")";

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . "chat_msg");

			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user User that delete
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, >0 if OK
	 */
	public function delete($user, $notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;

		$this->db->begin();

		if (! $error) {
			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		if (! $error) {
			$sql = "DELETE FROM " . MAIN_DB_PREFIX . "chat_msg";
			$sql.= " WHERE rowid=" . $this->id;

			dol_syslog(__METHOD__ . " sql=" . $sql);
			$resql = $this->db->query($sql);
			if (! $resql) {
				$error ++;
				$this->errors[] = "Error " . $this->db->lasterror();
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}
}
