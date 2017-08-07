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

require_once 'chatMessage.class.php';

/**
 * Put your class' description here
 */
class Chat // extends CommonObject
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
        /** @var mixed An example property */
	public $users = array();
        /** @var mixed An example property */
	public $messages = array();

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
	public function fetch_users($user, $self_exclusion = 0, $filter_user = '', $check_online = 0)
	{
		global $conf, $langs;
                
		$sql = "SELECT u.rowid, u.lastname, u.firstname, u.admin, u.gender, u.photo, u.datelastlogin";
                if ($check_online)
                {
                    $sql.= ", (SELECT count(*) FROM ".MAIN_DB_PREFIX."chat_online WHERE online_user = u.rowid) as is_online";
                }
                $sql.= " FROM ".MAIN_DB_PREFIX."user as u";
                if(! empty($conf->multicompany->enabled) && $conf->entity == 1 && (! empty($conf->multicompany->transverse_mode) || (! empty($user->admin) && empty($user->entity))))
                {
                        $sql.= " WHERE u.entity IS NOT NULL";
                }
                else
                {
                        $sql.= " WHERE u.entity IN (".getEntity('user',1).")";
                }
                if ($self_exclusion)
                {
                        $sql.= " AND u.rowid != ".$user->id;
                }
                if (! empty($filter_user))
                {
                        $sql.= " AND (u.lastname LIKE '%".$filter_user."%'";
                        $sql.= " OR u.firstname LIKE '%".$filter_user."%')";
                }

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) 
                {
                        if ($this->db->num_rows($resql))
                        {
                            while ($obj = $this->db->fetch_object($resql))
                            {
                                    $this->users[$obj->rowid] = $obj;
                                    //...
                            }
                        }
                        
			$this->db->free($resql);

			return 1;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}
        
        /**
	 * Load object in memory from database
	 *
	 * @param int $id Id object
	 * @return int <0 if KO, >0 if OK
	 */
	public function is_online_user($user)
	{
		global $conf, $langs;
                
                // on vérifie si l'utilisateur est enregistré ou non dans la table chat_online
                $sql = "SELECT c.online_user";
                $sql.= " FROM ".MAIN_DB_PREFIX."chat_online as c";
                $sql.= " WHERE c.online_user = ".$user->id;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) 
                {
                        $num = $this->db->num_rows($resql);
                        
                        $this->db->free($resql);
                        
                        return $num;
		}
                else
                {
			return -1;
		}
	}
        
        /**
	 * Load object in memory from database
	 *
	 * @param int $id Id object
	 * @return int <0 if KO, >0 if OK
	 */
	public function add_online_user($user)
	{
		global $langs;
                
                $now = dol_now();
                $default_status = 0;
                $ip = $_SERVER["REMOTE_ADDR"];
                
		// Insert request
		$sql = "INSERT INTO " . MAIN_DB_PREFIX . "chat_online(";
		$sql.= " online_ip,";
                $sql.= " online_user,";
                $sql.= " online_time,";
		$sql.= " online_status";

		$sql.= ") VALUES (";
		$sql.= " '" . $ip . "',";
		$sql.= " '" . $user->id . "',";
                $sql.= " '" . $this->db->idate($now)."',";
		$sql.= " '" . $default_status . "'";

		$sql.= ")";

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}
                
                if (! $error) {
			//$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . "chat_online");

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

			return 1;
		}
	}
        
        /**
	 * Load object in memory from database
	 *
	 * @param int $id Id object
	 * @return int <0 if KO, >0 if OK
	 */
	public function update_online_user($user)
	{
		global $langs;
                
                $now = dol_now();
                
		// Update request
		$sql = "UPDATE " . MAIN_DB_PREFIX . "chat_online SET";
                $sql.= " online_time = '".$this->db->idate($now)."'";
                $sql.= " WHERE online_user = ".$user->id;
                
		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_MODIFY',$this,$user,$langs,$conf);
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

			return 1;
		}
	}
        
        /**
	 * Delete object in database
	 *
	 * @param User $user User that delete
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, >0 if OK
	 */
	public function delete_offline_users($notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;
                
                $now = dol_now();
                $refresh_time = ! empty($conf->global->CHAT_AUTO_REFRESH_TIME) ? $conf->global->CHAT_AUTO_REFRESH_TIME : 5;
                $timeout = $now - $refresh_time;

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
			$sql = "DELETE FROM " . MAIN_DB_PREFIX . "chat_online";
			$sql.= " WHERE online_time < '" . $this->db->idate($timeout)."'";

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

	/**
	 * Load object in memory from database
	 *
	 * @param int $id Id object
	 * @return int <0 if KO, >0 if OK
	 */
	public function fetch_messages($user)
	{
		global $conf, $langs;
                
                // si l'utilisateur est inscrit on actualise la date de la dernière vérification effectuée
                if ($this->is_online_user($user))
                {
                    $this->update_online_user($user);
                }
                else // si nn on l'inscrit en tant qu'utilisateur en ligne
                {
                    $this->add_online_user($user);
                }
                
                // et on supprime les utilisateurs qui ne sont plus en ligne
                $this->delete_offline_users();
                
                // récupération de la limite des messages à afficher
                $limit = ! empty($conf->global->CHAT_MAX_MSG_NUMBER) ? $conf->global->CHAT_MAX_MSG_NUMBER : 50;
                
		$sql = "SELECT m.rowid as id, m.fk_user";
                $sql.= ", (SELECT count(rowid) FROM ".MAIN_DB_PREFIX."chat_msg WHERE fk_user_to IS NULL OR fk_user = ".$user->id." OR fk_user_to = ".$user->id.") as msg_number";
                //$sql.= ", (SELECT count(*) FROM ".MAIN_DB_PREFIX."chat_online WHERE online_user = m.fk_user) as is_online";
                $sql.= ", m.post_time, m.text, m.fk_user_to, m.status";
                $sql.= ", a.name as attachment_name, a.type as attachment_type, a.size as attachment_size";
                $sql.= " FROM ".MAIN_DB_PREFIX."chat_msg as m";
                $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."chat_msg_attachment as a ON m.fk_attachment = a.rowid";
                $sql.= " WHERE m.fk_user_to IS NULL OR m.fk_user = ".$user->id." OR m.fk_user_to = ".$user->id;
                $sql.= " ORDER BY m.post_time DESC";
                $sql.= " LIMIT ".$limit;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql)
                {
			$num = $this->db->num_rows($resql);
			$i = 0;
                        
                        // on récupère tout les utilisateurs
                        $this->users = array();
                        $result = $this->fetch_users($user);
                        
                        if ($result)
                        { // si c'est bon on récupère les messages
                            while ($i < $num)
                            {
                                    $obj = $this->db->fetch_object($resql);

                                    $this->messages[$i] = $obj;
                                    $this->messages[$i]->user = $this->users[$obj->fk_user];
                                    if ($obj->fk_user_to > 0)
                                    {
                                        $this->messages[$i]->user_to = $this->users[$obj->fk_user_to];
                                    }
                                    //...

                                    $i++;
                            }
                        }
                        
			$this->db->free($resql);

			return 1;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}
}
