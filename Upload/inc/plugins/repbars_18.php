<?php

 /*     This file is part of Rep Bars

    Rep Bars is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Rep Bars is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Rep Bars.  If not, see <http://www.gnu.org/licenses/>. */

if(!defined("IN_MYBB")) {
    die("Hacking Attempt.");
}

if (defined('IN_ADMINCP')) {
	$plugins->add_hook('admin_user_menu', 'advrepbars_admin_menu');
	$plugins->add_hook('admin_user_action_handler', 'advrepbars_admin_menu_action_handler');	
}

if (isset($mybb->settings['repbar_18_postbit']) && $mybb->settings['repbar_18_postbit'] == 1) {
    $plugins->add_hook("postbit", "repbars_18_parse");
    $plugins->add_hook("postbit_pm", "repbars_18_parse");
    $plugins->add_hook("postbit_announcement", "repbars_18_parse");
    $plugins->add_hook("showthread_start", "repbars_18_loadlang");
    $plugins->add_hook("private_start", "repbars_18_loadlang");
}

if (isset($mybb->settings['repbar_18_profile']) && $mybb->settings['repbar_18_profile'] == 1) {
    $plugins->add_hook("member_profile_start", "repbars_18_loadlang");
    $plugins->add_hook("member_profile_end", "repbars_18_profile");
}



function repbars_18_info() {
    global $lang; 
    $lang->load("repbars_18");

	return array(
		'name'	        =>  htmlspecialchars($lang->repbars_18_title),
		'description'	=>  htmlspecialchars($lang->repbars_18_desc),
		'website'		=>  'http://www.makestation.net',
		'author'		=>  'Darth Apple',
		'authorsite'	=>  'http://www.makestation.net',
		'codename' 		=>  'repbars_18',
		'version'		=>  '1.0',
		"compatibility"	=>  "18*"
	);
}

function repbars_18_install() {
    global $db;

    if (!$db->table_exists("advrepbars_bars"))
    {
        $db->write_query("CREATE TABLE ".TABLE_PREFIX."advrepbars_bars (
            bid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            level int NOT NULL,
            bgcolor varchar(255),
            fontstyle varchar(255),
            disporder int NOT NULL DEFAULT 0,
            dateline int(11) NOT NULL
        );");
    }
}

function repbars_18_is_installed() {
    global $db;

    if ($db->table_exists("advrepbars_bars")) {
        return true;
    } else {
        return false;
    }
}

function repbars_18_uninstall() {
    global $db;

    if ($db->table_exists("advrepbars_bars"))
    {
        $db->write_query("DROP TABLE ".TABLE_PREFIX."advrepbars_bars");
    }
}

function repbars_18_activate() {
	require MYBB_ROOT.'/inc/adminfunctions_templates.php';
    global $db, $lang;
    $lang->load("repbars_18");
    
    find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'groupimage\']}').'#', '{$post[\'groupimage\']} {$post[\'repbars_18\']}');	
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'groupimage\']}').'#', '{$post[\'groupimage\']} {$post[\'repbars_18\']}');	
	find_replace_templatesets("member_profile", '#'.preg_quote('{$groupimage}').'#', '{$groupimage} {$memprofile[\'repbars_18\']}');

    // Insert settings
    $setting_group = array (
        'name' => 'repbars_18', 
        'title' => $db->escape_string($lang->repbars_18_title),
        'description' => $db->escape_string($lang->repbars_18_desc),
        'disporder' => $rows+3,
        'isdefault' => 0
    ); 

    $group['gid'] = $db->insert_query("settinggroups", $setting_group); // inserts new group for settings into the database. 	
    $settings = array();

    $settings[] = array(
        'name' => 'repbar_18_postbit',
        'title' => $db->escape_string($lang->repbars_18_postbit),
        'description' => $db->escape_string($lang->repbars_18_postbit_desc),
        'optionscode' => 'yesno',
        'value' => '1',
        'disporder' => 1,
        'isdefault' => 0,
        'gid' => $group['gid']
    );

    $settings[] = array(
        'name' => 'repbar_18_profile',
        'title' => $db->escape_string($lang->repbars_18_profile),
        'description' => $db->escape_string($lang->repbars_18_profile_desc),
        'optionscode' => 'yesno',
        'value' => '1',
        'disporder' => 1,
        'isdefault' => 0,
        'gid' => $group['gid']
    );

    $settings[] = array(
        'name' => 'repbar_18_min',
        'title' => $db->escape_string($lang->repbars_18_min),
        'description' => $db->escape_string($lang->repbars_18_min_desc),
        'optionscode' => 'numeric',
        'value' => '0',
        'disporder' => 1,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    $settings[] = array(
        'name' => 'repbar_18_max',
        'title' => $db->escape_string($lang->repbars_18_max),
        'description' => $db->escape_string($lang->repbars_18_max_desc),
        'optionscode' => 'numeric',
        'value' => '50',
        'disporder' => 2,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    $settings[] = array(
        'name' => 'repbar_18_background',
        'title' => $db->escape_string($lang->repbars_18_bgcolor),
        'description' => $db->escape_string($lang->repbars_18_bgcolor_desc),
        'optionscode' => 'text',
        'value' => '#22a232',
        'disporder' => 3,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    $settings[] = array(
        'name' => 'repbar_18_textcolor',
        'title' => $db->escape_string($lang->repbars_18_textcolor),
        'description' => $db->escape_string($lang->repbars_18_textcolor_desc),
        'optionscode' => 'text',
        'value' => '#ffffff',
        'disporder' => 4,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    foreach($settings as $array => $setting) {
        $db->insert_query("settings", $setting); // lots of queries
    }
    rebuild_settings();

    // Enter templates. 
    $templates = array();
    $templates['repbars_18_bar'] = '
    {$br_above_label}
    <div style="margin-top: 3px; padding: 0px; padding-right:3px; margin-right: 5px; {$max_width}" title="{$lang->repbars_18_reputation}">
        <div class="rep-meter" style="border-radius: 4px; padding: 2px; padding-right: 5px; border: 1px solid #cccccc; width: 100%; ">
            <div class="rep-meter-inner" style="background-color: {$background}; color: {$color}; width: {$rep}%; text-align: left; padding-left:2px; ">
                {$post[\'reputation\']}
            </div>
        </div>    
    </div>'; 

    foreach($templates as $title => $template_new){
        $template = array('title' => $db->escape_string($title), 'template' => $db->escape_string($template_new), 'sid' => '-1', 'dateline' => TIME_NOW, 'version' => '1800');
        $db->insert_query('templates', $template);
    }
}

function repbars_18_deactivate() {
    global $db; 
    require MYBB_ROOT.'/inc/adminfunctions_templates.php';
    
    // Undo template modifications
	find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'repbars_18\']}').'#', '',0);
    find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'repbars_18\']}').'#', '',0);
	find_replace_templatesets("member_profile", '#'.preg_quote('{$memprofile[\'repbars_18\']}').'#', '',0);

    // Remove settings
    $query = $db->simple_select('settinggroups', 'gid', 'name = "repbars_18"'); // remove settings
    $groupid = $db->fetch_field($query, 'gid');
    $db->delete_query('settings','gid = "'.$groupid.'"');
    $db->delete_query('settinggroups','gid = "'.$groupid.'"');
    rebuild_settings();

    // Remove templates
    $templates = array('repbars_18_bar'); // remove templates
    foreach($templates as $template) {
        $db->delete_query('templates', "title = '{$template}'");
    }
}

function repbars_18_parse(&$post) {
    global $mybb, $templates, $repbars_18, $templates, $lang, $color, $background, $rep, $max_width, $br_above_label;

    $max_width = "";
    $br_above_label = "<br />";    
    $color = htmlspecialchars($mybb->settings['repbar_18_textcolor']);

    if ($post['reputation'] == 0) {
        $background = "#545151"; // Display a gray bar if the user has no reputation. 
    } 
    else {
        $background = htmlspecialchars($mybb->settings['repbar_18_background']);
    }

    // Determine if we have a full bar
    if ($post['reputation'] >= $mybb->settings['repbar_18_max']) {
        $rep = 100; 
    }

    else {
        $rep = $post['reputation'] - $mybb->settings['repbar_18_min'];
        $rep = $rep / ($mybb->settings['repbar_18_max'] - $mybb->settings['repbar_18_min']); 
        $rep = (int) ($rep * 100); // Avoid situations where the CSS has to render widths such as 3.333333333%, etc. 
        
        if ($rep < 5) {
            $rep = 5;  // Minimum bar width is 5% for asthetic/visual purposes. Otherwise, the reputation count won't fit inside the bar. 
        }
        if ($post['reputation'] >= 10 && $rep < 9) {
            $rep = 9; // Bug fix for situations when two digit reputations don't fit inside the bar background. 
        }
    }
    $post['reputation'] = (int) $post['reputation'];
    eval("\$post['repbars_18'] = \"".$templates->get("repbars_18_bar")."\";"); 
}

function repbars_18_profile() {
    global $mybb, $templates, $memprofile, $repbars_18, $templates, $lang, $color, $background, $rep, $max_width, $br_above_label;
    
    $color = htmlspecialchars($mybb->settings['repbar_18_textcolor']);
    $max_width = "max-width: 200px;";
    $br_above_label = "<br />";

    if ($memprofile['reputation'] == 0) {
        $background = "#545151"; // Display a gray bar if the user has no reputation. 
    } 
    else {
        $background = htmlspecialchars($mybb->settings['repbar_18_background']);
    }

    // Determine if we have a full bar
    if ($memprofile['reputation'] >= $mybb->settings['repbar_18_max']) {
        $rep = 100; 
    } 

    else {
        $rep = $memprofile['reputation'] - $mybb->settings['repbar_18_min'];
        $rep = $rep / ($mybb->settings['repbar_18_max'] - $mybb->settings['repbar_18_min']); 
        $rep = (int) ($rep * 100); // Avoid situations where the CSS has to render widths such as 3.333333333%, etc. 
        
        if ($rep < 5) {
            $rep = 5;  // Minimum bar width is 5% for asthetic/visual purposes. Otherwise, the reputation count won't fit inside the bar. 
        }
        if ($memprofile['reputation'] >= 10 && $rep < 9) {
            $rep = 9; // Bug fix for situations when two digit reputations don't fit inside the bar background. 
        }
    }
    $post['reputation'] = (int) $memprofile['reputation'];
    $memprofile['reputation'] = (int) $memprofile['reputation'];
    eval("\$memprofile['repbars_18'] = \"".$templates->get("repbars_18_bar")."\";"); 
}

function repbars_18_loadlang() {
    global $lang; 
    $lang->load("repbars_18");
}

/* Used for populating the menu item in ACP */
function advrepbars_admin_menu(&$sub_menu) {
	$sub_menu[] = ['id' => 'advrepbars', 'title' => 'Advanced Reputation Bars', 'link' => 'index.php?module=user-advrepbars'];
}

function advrepbars_admin_menu_action_handler(&$actions) {
	$actions['advrepbars'] = ['active' => 'advrepbars', 'file' => 'advrepbars.php'];
}