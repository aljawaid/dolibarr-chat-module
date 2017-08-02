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
 * \file    lib/chat.lib.php
 * \ingroup chat
 * \brief   Example module library.
 *
 * Put detailed description here.
 */

/**
 * Prepare admin pages header
 *
 * @return array
 */
function chatAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("chat@chat");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/chat/admin/setup.php", 1);
	$head[$h][1] = $langs->trans("Settings");
	$head[$h][2] = 'settings';
	$h++;
	$head[$h][0] = dol_buildpath("/chat/admin/about.php", 1);
	$head[$h][1] = $langs->trans("About");
	$head[$h][2] = 'about';
	$h++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//	'entity:+tabname:Title:@chat:/chat/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//	'entity:-tabname:Title:@chat:/chat/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'chat');

	return $head;
}

function urllink($content='') {
	$content = preg_replace('#(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])#i', '<a href="$0" target="_blank" title="$0">$0</a>', $content);
	
        // Si on capte un lien tel que www.test.com, il faut rajouter le http://
	if(preg_match('#<a href="www\.(.+)" target="_blank" title="www\.(.+)">(.+)<\/a>#i', $content)) {
		$content = preg_replace('#<a href="www\.(.+)" target="_blank" title="www\.(.+)">(.+)<\/a>#i', '<a href="http://www.$1" target="_blank" title="www.$1">www.$1</a>', $content);
	}

	$content = stripslashes($content);
	return $content;
}

function cleanattachmentname($attachmentname)
{
        // on retire la date de création (première partie) de chaque nom de fichier
        // à quoi ça peux servir cette date? => à différencier entre les fichiers qui ont le même nom et éviter que l'un écrase l'autre
        return substr($attachmentname, strpos($attachmentname, "_") + 1);
}

function getSmilies()
{
    $smilies=array(
                '&gt;:(' => "<img class='smiley' src='img/smilies/nervous.png'>",
                ':\'(' => "<img class='smiley' src='img/smilies/cry.png'>",
                '3:)' => "<img class='smiley' src='img/smilies/evil.png'>",
                'o:)' => "<img class='smiley' src='img/smilies/angel.png'>",
                ':*' => "<img class='smiley' src='img/smilies/kiss.png'>",
                '&lt;3' => "<img class='smiley' src='img/smilies/heart.png'>",
                '^_^' => "<img class='smiley' src='img/smilies/great.png'>",
                '-_-' => "<img class='smiley' src='img/smilies/malicious.png'>",
                'o.O' => "<img class='smiley' src='img/smilies/surprised.png'>",
                '&gt;:o' => "<img class='smiley' src='img/smilies/anger.png'>",
		':)' => "<img class='smiley' src='img/smilies/happy.png'>",
		':(' => "<img class='smiley' src='img/smilies/sad.png'>",
                ':P' => "<img class='smiley' src='img/smilies/joke.png'>",
                ';)' => "<img class='smiley' src='img/smilies/wink.png'>",
                ':D' => "<img class='smiley' src='img/smilies/laugh.png'>",
                ':o' => "<img class='smiley' src='img/smilies/wondering.png'>",
                '8|' => "<img class='smiley' src='img/smilies/cool.png'>",
                ':v' => "<img class='smiley' src='img/smilies/pacman.png'>",
                ':3' => "<img class='smiley' src='img/smilies/cat.png'>",
                '(y)' => "<img class='smiley' src='img/smilies/like.png'>",
                ':poop:' => "<img class='smiley' src='img/smilies/poop.png'>"
	);
    
    return $smilies;
}

function parseSmiley($text)
{
	$smilies = getSmilies();

	return str_replace(array_keys($smilies), array_values($smilies), $text);
}

function printSmileyList()
{
        $smilies = getSmilies();
        $keys = array_keys($smilies);
        $smilies_number = count($smilies);
        
        $out = "<table>";
        
        $i = 0;
        while ($i < $smilies_number) {
            $out.= "<tr>";
            
            $td = 0;
            while ($i < $smilies_number && $td < 5) // 5 smilies per line
            {
                $out.= "<td>".str_replace("class='smiley'", "class=\"smiley\" title=\"".$keys[$i]."\"", $smilies[$keys[$i]])."</td>";
                
                $td++;
                $i++;
            }
            
            $out.= "</tr>";
        }
        
        $out.= "</table>";
        
        return $out;
}
