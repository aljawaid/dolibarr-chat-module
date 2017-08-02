<?php
/* Copyright (C) 2017	Denna Anass	<anass_denna@hotmail.fr>
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
 *       \file       htdocs/chat/ajax/ajax.php
 *       \brief      File to do ajax actions
 */

// Load Dolibarr environment
if (false === (@include '../main.inc.php')) {  // From htdocs directory
	require '../../main.inc.php'; // From "custom" directory
}

global $db, $langs, $user;

dol_include_once('/chat/class/chat.class.php');

// Get parameters
$action	= GETPOST('action','alpha');
$filter_user = GETPOST('filter_user','alpha');

// Access control
if ($user->socid > 0 || !$user->rights->chat->lire) {
	// External user
	accessforbidden();
}

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

// Actions
if (isset($action) && ! empty($action))
{
	if ($action == 'fetch_msgs')
	{
            $object = new Chat($db);
            
            // récupération des messages
            $result = $object->fetch_messages($user);

            if ($result)
            {
                include_once DOL_DOCUMENT_ROOT.'/chat/tpl/message.tpl.php';
            }
        } // fin if ($action == 'fetch_msgs')
        else if ($action == 'fetch_users')
	{
            $object = new Chat($db);
            
            // récupération des utilisateurs
            $result = $object->fetch_users($user, 1, $filter_user, 1);

            if ($result)
            {
                include_once DOL_DOCUMENT_ROOT.'/chat/tpl/user.tpl.php';
            }
        } // fin if ($action == 'fetch_users')
}
