<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <31/12/2016 - 03/01/2017>  <Denna Anass - anass_denna@hotmail.fr>
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
 * \file    index.php
 * \ingroup chat
 * \brief   Example PHP page.
 *
 * Put detailed description here.
 */

if (! defined('REQUIRE_JQUERY_LAYOUT'))  define('REQUIRE_JQUERY_LAYOUT','1');
//if (! defined('REQUIRE_JQUERY_BLOCKUI')) define('REQUIRE_JQUERY_BLOCKUI', 1);

// Load Dolibarr environment
global $mod_path;
$mod_path = "";
if (false === (@include '../main.inc.php')) {  // From htdocs directory
	require '../../main.inc.php'; // From "custom" directory
        $mod_path = "/custom";
}

dol_include_once($mod_path.'/chat/class/chat.class.php');
require_once DOL_DOCUMENT_ROOT.$mod_path.'/chat/lib/chat.lib.php';

global $db, $langs, $user;

// Load translation files required by the page
$langs->load("chat@chat");

// Get parameters
$action = GETPOST('action', 'alpha');

$text = GETPOST('text', 'alpha');
$user_to_id = GETPOST('user_to_id', 'int');
$filter_user = GETPOST('filter_user', 'alpha');

$msg_id = GETPOST('msg_id', 'int');
$enter_to_send = GETPOST('enter_to_send', 'alpha');

// Access control
if ($user->socid > 0 || !$user->rights->chat->lire) {
	// External user
	accessforbidden();
}

// global vars
$userstatic = new User($db);

// Load object
$object = new Chat($db);

/*
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 */

if ($action == 'send') {
    
        if (empty($text))
        {
            setEventMessage($langs->trans("PleaseTypeAMessage"), 'warnings');
        }
        else if (! empty($user_to_id) && $user_to_id == $user->id)
        {
            setEventMessage($langs->trans("CannotSendMessageToYourself"), 'warnings');
        }
        else
        {
            $now = dol_now();

            $myobject = new ChatMessage($db);
            $myobject->post_time = $now;
            $myobject->text = $text;
            $myobject->fk_user_to = $user_to_id;
            
            $attachment_id = 0;
            
            if (!empty($_FILES['attachment']['name'])) {
                    $attachment = new ChatMessageAttachment($db);
                    // on ajoute la date au nom du fichier pour différencier entre les fichiers qui ont le même nom et éviter que l'un écrase l'autre
                    $attachment->name = dol_sanitizeFileName(dol_now().'_'.$_FILES['attachment']['name']);
                    $attachment->type = $_FILES['attachment']['type'];
                    $attachment->size = $_FILES['attachment']['size'];
                    $attachment_id = $attachment->add($user);
            }
            
            $myobject->fk_attachment = $attachment_id;

            $result = $myobject->send($user);
            if ($result > 0) {
                    // Creation OK
                    $user_to_id = "";
            } else {
                    // Creation KO
                    $mesg = $myobject->error;
                    //dol_print_error($db);
                    setEventMessages($mesg, $myobject->errors, 'errors');
            }
        }
        
        $action = empty($user_to_id) ? "" : "private_msg";
}

else if ($action == 'delete') {
    if ($msg_id > 0)
    {
        $error = 0;
        
        $myobject = new ChatMessage($db);
        $result = $myobject->fetch($msg_id);
        
        if ($result > 0)
        {
            $is_mine = $myobject->fk_user == $user->id;
            
            if ($user->rights->chat->delete->all || ($is_mine && $user->rights->chat->delete->mine))
            {
                // we delete message
                $result = $myobject->delete($user);

                if ($result > 0) {
                        // Delete OK
                } else {
                        $error ++;
                        // Delete KO
                        $mesg = $myobject->error;
                        //dol_print_error($db);
                        setEventMessages($mesg, $myobject->errors, 'errors');
                }
                
                // next, we delete attachment if exists
                if (! $error && $myobject->fk_attachment > 0)
                {
                    $attachment = new ChatMessageAttachment($db);
                    $result = $attachment->fetch($myobject->fk_attachment);

                    if ($result > 0)
                    {
                        $result = $attachment->delete($user);
                        
                        if ($result > 0) {
                            // Delete attachment OK
                        } else {
                            // Delete attachment KO
                            $mesg = $attachment->error;
                            //dol_print_error($db);
                            setEventMessages($mesg, $attachment->errors, 'errors');
                        }
                    }
                }
                
            }
            else
            {
                setEventMessage($langs->trans("CannotDeleteMessage"), 'warnings');
            }
        }
    }
}

/*
 * VIEW
 *
 * Put here all code to build page
 */

// Define height of file area (depends on $_SESSION["dol_screenheight"])
$maxheightwin=(isset($_SESSION["dol_screenheight"]) && $_SESSION["dol_screenheight"] > 466)?($_SESSION["dol_screenheight"]-186):660;	// Also into index_auto.php file

$morejs=array();

$moreheadcss="
<!-- dol_screenheight=".$_SESSION["dol_screenheight"]." -->
<link rel=\"stylesheet\" type=\"text/css\" href=\"".DOL_URL_ROOT.$mod_path."/chat/css/chat.css.php\">
<style type=\"text/css\">
    #containerlayout {
        height:     ".$maxheightwin."px;
        margin:     0 auto;
        width:      100%;
        min-width:  700px;
        _width:     700px; /* min-width for IE6 */
    }
</style>";

$moreheadjs=empty($conf->use_javascript_ajax)?"":"
<script type=\"text/javascript\">
    jQuery(document).ready(function () {
        jQuery('#containerlayout').layout({
        	name: \"ecmlayout\"
        ,   paneClass:    \"ecm-layout-pane\"
        ,   resizerClass: \"ecm-layout-resizer\"
        ,   togglerClass: \"ecm-layout-toggler\"
        ,   center__paneSelector:   \"#ecm-layout-center\"
        ,   west__paneSelector:     \"#ecm-layout-west\"
        ,   resizable: true
        ,   west__size:         340
        ,   west__minSize:      280
        ,   west__slidable:     true
        ,   west__resizable:    true
        ,   west__togglerLength_closed: '100%'
        ,   useStateCookie:     true
            });
        
        function chatScroll() {
            $(\"#chat_container\").scrollTop($(\"#chat_container\")[0].scrollHeight);
        }
        
        chatScroll();
        
        function fetchMessages() {
            setTimeout( function(){
                    $.get( '".DOL_URL_ROOT.$mod_path.'/chat/ajax/ajax.php'."', {
                            action: \"fetch_msgs\"
                    },
                    function(response) {
                            // s'il y'a des nouveaux messages (ou message(s) supprimé(s))
                            if ($(response).filter('#msg_number').val() != $('#msg_number').val())
                            {
                                $('#chat_container').html(response);
                                chatScroll();
                            }
                    });
                    
                    fetchUsers(); // on n'oublie pas de rafraîchir la liste des utilisateurs aussi
                    
                    fetchMessages();
            }, ".(! empty($conf->global->CHAT_AUTO_REFRESH_TIME) ? $conf->global->CHAT_AUTO_REFRESH_TIME * 1000 : 5000 ).");
        }
        
        function fetchUsers() {
                    $.get( '".DOL_URL_ROOT.$mod_path.'/chat/ajax/ajax.php'."', {
                            action: \"fetch_users\",
                            filter_user: \"".$filter_user."\"
                    },
                    function(response) {
                            $('#users_container').html(response);
                    });
        }
        
        fetchMessages();
        
        $('#add-attachment').click(function() {
            $('#attachment-input').click();
        });
        
        $('#attachment-input').change(function() {
            $('#add-attachment span').text($(this).val());
        });
        
        $(document).click(function() {
            $('.dropdown-click .dropdown-content').removeClass('show');
        });
        
        $('.drop-btn').click(function(e) {
            e.stopPropagation();
            $('.dropdown-click .dropdown-content').removeClass('show');
            $(this).next().addClass('show');
        });
        
        $('#smiley-dropdown img.smiley').click(function() {
            var new_val = $('textarea.send-message').val() + $(this).attr('title');
            $('textarea.send-message').val(new_val);
        });
        
        $('.send-wrap textarea').keydown(function(event) {
            if (event.keyCode == 13 && $('#enter-to-send').is(':checked')) {
                $('#chatForm').submit();
                return false;
             }
        });
        
    });
</script>";

llxHeader($moreheadcss.$moreheadjs, $langs->trans('ChatIndexPageName'),'','','','',$morejs);

// Page content

if (! empty($conf->use_javascript_ajax)) $classviewhide='hidden';
else $classviewhide='visible';

// Start container of all panels
?>
<div id="containerlayout"> <!-- begin div id="containerlayout" -->
<div id="ecm-layout-west" class="<?php echo $classviewhide; ?>">
<?php
// Start left area

    // formulaire de recherche
?>

    <form id="searchForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div id="custom-search-input">
            <div class="input-group">
                <input name="filter_user" class="search-query form-control" placeholder="<?php echo $langs->trans("SearchPlaceHolder"); ?>" type="text" value="<?php echo $filter_user;  ?>">
                <a href="javascript:void()" onclick="document.getElementById('searchForm').submit();" class="btn btn-danger">
                    <img class="" title="" alt="" src="img/search.png" />
                </a>
            </div>
        </div>
    </form>

    <div id="users_container">
<?php
    
    // récupération des utilisateurs (filtrage inclus)
    $result = $object->fetch_users($user, 1, $filter_user, 1);

    if ($result)
    {
        include_once DOL_DOCUMENT_ROOT.$mod_path.'/chat/tpl/user.tpl.php';
    }
    
// End left panel
?>
    </div> <!-- end div id="users_container" -->
</div>
<div id="ecm-layout-center" class="<?php echo $classviewhide; ?>">
<div class="pane-in ecm-in-layout-center">
<?php
// Start right panel
?>
    <div id="chat_container" class="msg-wrap">
<?php
    
    // récupération des messages
    $result = $object->fetch_messages($user);
    
    if ($result)
    {
        include_once DOL_DOCUMENT_ROOT.$mod_path.'/chat/tpl/message.tpl.php';
    }
?>
    </div> <!-- end div id="chat_container" class="msg-wrap" -->
    
<?php
    
    print '<form id="chatForm" method="POST" action="'.$_SERVER["PHP_SELF"].'" enctype="multipart/form-data">';
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print '<input type="hidden" name="action" value="send">';
    print '<input type="hidden" name="user_to_id" value="'.$user_to_id.'">';
?>
    <div class="send-wrap ">
        <?php
            if ($action == "private_msg" && ! empty($user_to_id))
            {
                $userstatic->fetch($user_to_id);
                
                echo '<div id="private-msg-to-user">'.$langs->trans("PrivateMessageTo").' <span>'.$userstatic->getFullName($langs).'</span></div>';
            }
        ?>
        <textarea <?php echo $action == "private_msg" ? 'id="private-msg-textarea"' : ''; ?> name="text" class="form-control send-message" rows="3" placeholder="<?php echo $langs->trans("TypeAMessagePlaceHolder"); ?>"></textarea>
    </div>

    <div class="btn-panel">
        <!-- Smiley -->
        <div class="dropdown-click">
            <label class="drop-btn btn"><img class="btn-icon" title="" alt="" src="img/smiley.png" /></label>
            <div id="smiley-dropdown" class="dropdown-content dropdown-top">
                <?php echo printSmileyList(); ?>
            </div>
        </div>
        <!-- Settings -->
        <div class="dropdown-click">
            <label class="drop-btn btn"><img class="btn-icon" title="" alt="" src="img/settings.png" /></label>
            <div class="dropdown-content dropdown-top">
                <div class="more-width">
                    <input class="align-middle" type="checkbox" id="enter-to-send" name="enter_to_send" <?php echo empty($enter_to_send) ? "" : "checked"; ?>/>
                    <label for="enter-to-send" class="align-middle cursor-pointer"><?php echo ' '.$langs->trans("EnterToSend"); ?></label>
                </div>
            </div>
        </div>
        <!-- Add File -->
        <span>
            <input type="file" class="hidden" name="attachment" id="attachment-input"/>
            <label class="btn send-message-btn" id="add-attachment">
                <img class="btn-icon" title="" alt="" src="img/attachment.png" />
                <span><?php echo ' '.$langs->trans("AddFiles");?></span>
            </label>
        </span>
        <!-- Send -->
        <a href="javascript:void()" onclick="document.getElementById('chatForm').submit();" class="text-right btn send-message-btn pull-right"><img class="btn-icon" title="" alt="" src="img/send.png" /><?php echo ' '.$langs->trans("SendMessage");?></a>
    </div>
<?php

    print '</form>'."\n";
    
// End right panel
?>
</div>
</div>
</div> <!-- end div id="containerlayout" -->
<?php

// End of page
llxFooter();
