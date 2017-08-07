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
 *       \file       htdocs/chat/tpl/popup.tpl.php
 *       \brief      Template of chat popup
 */

$mod_path= $GLOBALS['mod_path'];
$langs = $GLOBALS['langs'];

?>
<div id="chat_popup">
    <div class="panel panel-default">
        <div class="panel-heading" id="accordion">
            <img class="align-middle" title="" alt="" src="<?php echo DOL_URL_ROOT.$mod_path.'/chat/img/chat-16.png'; ?>" /> Chat
            <div class="btn-group pull-right">
                <a type="button" href="<?php echo DOL_URL_ROOT.$mod_path.'/chat/index.php?mainmenu=chat&leftmenu='; ?>" class="btn btn-default btn-xs">
                    <img class="align-middle" title="" alt="" src="<?php echo DOL_URL_ROOT.$mod_path.'/chat/img/extend.png'; ?>" />
                </a>
            </div>
        </div>
    <div class="panel-collapse collapse" id="collapseOne">
        <div id="chat_container" class="panel-body msg-wrap">
            <?php
                $hide_options = true;
                include_once DOL_DOCUMENT_ROOT.$mod_path.'/chat/tpl/message.tpl.php';
            ?>
        </div>
        <div class="panel-footer">
            <div class="input-group">
                <input id="msg_input" type="text" class="form-control input-sm" placeholder="<?php echo $langs->trans("TypeAMessagePlaceHolder"); ?>" />
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="send_btn">
                        <img class="align-middle" title="" alt="" src="<?php echo DOL_URL_ROOT.$mod_path.'/chat/img/send.png'; ?>" />
                    </button>
                </span>
            </div>
        </div>
    </div>
    </div>
</div>
<?php
