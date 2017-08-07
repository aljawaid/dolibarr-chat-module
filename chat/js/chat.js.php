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
 * \file    js/myjs.js.php
 * \ingroup mymodule
 * \brief   Example JavaScript.
 *
 * Put detailed description here.
 */

// Load Dolibarr environment
global $mod_path;
$mod_path = "";
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
	$mod_path = "/custom";
}

global $conf;

header('Content-Type: text/javascript');

$is_chat_index_page = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) == DOL_URL_ROOT.$mod_path.'/chat/index.php' ? true : false;

if (! empty($conf->use_javascript_ajax) && ! $is_chat_index_page)
{

?>

$(document).ready(function() {

<?php

print "$.get( '".DOL_URL_ROOT.$mod_path.'/chat/ajax/ajax.php'."', {
                        action: \"get_popup_html\"
                },
                function(response) {
                        $('body').append(response);
                        
                        chatScroll();
                        
                        $('#accordion').click(function(e) {
                            if($(e.target).is('div')) { // if click from #accordion div
                                if ($('#collapseOne').hasClass('in')) {
                                    $('#collapseOne').slideUp().removeClass('in');
                                }
                                else {
                                    $('#collapseOne').slideDown().addClass('in');
                                    chatScroll();
                                }
                            }
                        });
                        
                        $('#send_btn').click(function(e) {
                            if ($('#msg_input').val() != '') { // if message field is not empty
                                $.post( '".DOL_URL_ROOT.$mod_path.'/chat/ajax/ajax.php'."', {
                                        action: \"send_msg\",
                                        msg: $('#msg_input').val()
                                },
                                function(response, status) {
                                        //alert(\"Response: \" + response + \"\\nStatus: \" + status);
                                        $('#msg_input').val('');
                                        getMessages();
                                });
                            }
                            else {
                                $('#msg_input').focus();
                            }
                        });
                        
                        $('#msg_input').keydown(function(event) {
                            if (event.keyCode == 13 && $('#msg_input').val() != '') {
                                $('#send_btn').click().focus();
                                return false;
                             }
                        });
                });
                
                function chatScroll() {
                    $(\"#chat_container\").scrollTop($(\"#chat_container\")[0].scrollHeight);
                }
                
                function getMessages() {
                    $.get( '".DOL_URL_ROOT.$mod_path.'/chat/ajax/ajax.php'."', {
                            action: \"fetch_msgs\",
                            hide_options: true
                    },
                    function(response) {
                            // s'il y'a des nouveaux messages (ou message(s) supprimé(s))
                            if ($(response).filter('#msg_number').val() != $('#msg_number').val())
                            {
                                $('#chat_container').html(response);
                                chatScroll();
                            }
                    });
                }
                
                function fetchMessages() {
                    setTimeout( function(){
                    
                            getMessages();

                            fetchMessages();
                    }, ".(! empty($conf->global->CHAT_AUTO_REFRESH_TIME) ? $conf->global->CHAT_AUTO_REFRESH_TIME * 1000 : 5000 ).");
                }

                fetchMessages();
                
                ";

?>

});

<?php

} // fin if (! empty($conf->use_javascript_ajax))
