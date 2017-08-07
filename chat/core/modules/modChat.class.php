<?php
/* Copyright (C) 2016-2017 Denna Anass <anass_denna@hotmail.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\defgroup   chat     Module de chat
 *	\brief      Module pour gerer ...
 *	\file       htdocs/chat/core/modules/modChat.class.php
 *	\ingroup    chat
 *	\brief      Fichier de description et activation du module chat
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Class to describe and enable module Expedition
 */
class modChat extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		global $conf;

		$this->db = $db;
		$this->numero = 500001;
		// key to reference module (for permissions, menus, etc.)
		$this->rights_class = 'chat';

		// Can be one of 'crm', 'financial', 'hr', 'projects', 'products', 'ecm', 'technic', 'other'
		$this->family = "other";
		$this->module_position = 100;
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion de chat";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = '1.1.0';

		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 0;
		$this->picto = "chat@chat";

		// Module parts (css, js, ...)
		$this->module_parts = array(
			'css' => array(
				'chat/css/popup.css.php'
			),
			'js' => array(
				'chat/js/chat.js.php'
			)
		);

		// Data directories to create when module is enabled
		$this->dirs = array("/chat/attachments");

		// Config pages
		$this->config_page_url = array("setup.php@chat");

		// Dependencies
		$this->depends = array();
		$this->requiredby = array();
		$this->conflictwith = array();
		$this->langfiles = array('chat@chat');

		// Constants
		$this->const = array(
                        0 => array(
                                'CHAT_AUTO_REFRESH_TIME', // Constant name
                                'chaine', // Constant type
                                '5', // Constant value
                                'Chat auto refresh time in seconds', // Constant description
                                true, // Constant visibility
                                'current', // Constant entity 'current' or 'allentities'
                                false // Delete constant when module is disabled
                        ),
                        1 => array(
                                'CHAT_MAX_MSG_NUMBER', // Constant name
                                'chaine', // Constant type
                                '50', // Constant value
                                'Chat maximum messages number', // Constant description
                                true, // Constant visibility
                                'current', // Constant entity 'current' or 'allentities'
                                false // Delete constant when module is disabled
                        ),
                        2 => array(
                                'CHAT_SHOW_IMAGES_PREVIEW', // Constant name
                                'chaine', // Constant type
                                '1', // Constant value
                                'Show or not images preview on chat', // Constant description
                                true, // Constant visibility
                                'current', // Constant entity 'current' or 'allentities'
                                false // Delete constant when module is disabled
                        )
                );
		
		// Boxes
		$this->boxes = array();

		// Permissions
		$this->rights = array();

		$r=0;
		
		$this->rights[$r][0] = 500002;
		$this->rights[$r][1] = 'Lire les messages et télécharger les pièces jointes du chat';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'lire';
                $r++;
                
                $this->rights[$r][0] = 500003;
		$this->rights[$r][1] = 'Supprimer ses messages du chat';
		$this->rights[$r][2] = 'd';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'delete';
                $this->rights[$r][5] = 'mine';
                $r++;
                
                $this->rights[$r][0] = 500004;
		$this->rights[$r][1] = 'Supprimer n\'importe quel message du chat';
		$this->rights[$r][2] = 'd';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'delete';
                $this->rights[$r][5] = 'all';
                $r++;

		// Main menu entries
		$this->menu = array();
		$r=0;

		// Top Menu entry:
		$this->menu[$r]=array(	'fk_menu'=>0,
                                        'type'=>'top',
                                        'titre'=>'ChatTopMenu',
                                        'mainmenu'=>'chat',
                                        'leftmenu'=>'chat',
                                        'url'=>'/chat/index.php',
                                        'langs'=>'chat@chat',
                                        'position'=>100,
                                        'enabled'=>'1',
                                        'perms'=>'$user->rights->chat->lire',
                                        'target'=>'',
                                        'user'=>2);
		$r++;

		// Exports
		//--------
		
	}


	/**
	 * Function called when module is enabled.
	 * The init function add constants, boxes, permissions and menus
	 * (defined in constructor) into Dolibarr database.
	 * It also creates data directories
	 *
	 * @param string $options Options when enabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		$sql = array();

		$result = $this->loadTables();

		return $this->_init($sql, $options);
	}

	/**
	 * Create tables, keys and data required by module
	 * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * and create data commands must be stored in directory /chat/sql/
	 * This function is called by this->init
	 *
	 * @return int <=0 if KO, >0 if OK
	 */
	private function loadTables()
	{
		return $this->_load_tables('/chat/sql/');
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * @param string $options Options when enabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}
}
