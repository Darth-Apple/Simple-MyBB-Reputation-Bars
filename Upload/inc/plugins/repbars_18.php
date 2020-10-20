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

$plugins->add_hook("postbit", "repbars_18_parse");
$plugins->add_hook("postbit_pm", "repbars_18_parse");
$plugins->add_hook("postbit_announcement", "repbars_18_parse");

function repbars_18_info()
{
	return array(
		'name'	        =>  'Simple Reputation Bars',
		'description'	=>  'Displays simple reputation bars on posts.',
		'website'		=>  'http://www.makestation.net',
		'author'		=>  'Darth Apple',
		'authorsite'	=>  'http://www.makestation.net',
		'codename' 		=>  'repbars_18',
		'version'		=>  '1.0',
		"compatibility"	=>  "18*"
	);
}

function repbars_18_activate () {
    global $db; 
	require MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'groupimage\']}').'#', '{$post[\'groupimage\']} {$post[\'repbars_18\']}');	
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'groupimage\']}').'#', '{$post[\'groupimage\']} {$post[\'repbars_18\']}');	

    $setting_group = array (
        'name' => 'repbars_18', 
        'title' => $db->escape_string("Simple Reputation Bars"),
        'description' => $db->escape_string("Configure Simple Reputation Bars on Postbit"),
        'disporder' => $rows+3,
        'isdefault' => 0
    ); 

    $group['gid'] = $db->insert_query("settinggroups", $setting_group); // inserts new group for settings into the database. 
		
    $settings = array();
    
    $settings[] = array(
        'name' => 'repbar_18_min',
        'title' => $db->escape_string("Minimum Reputation Level: "),
        'description' => $db->escape_string("What reputation should be required before the bar starts to increase?"),
        'optionscode' => 'numeric',
        'value' => '0',
        'disporder' => 1,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    $settings[] = array(
        'name' => 'repbar_18_max',
        'title' => $db->escape_string("Maximum Reputation Level: "),
        'description' => $db->escape_string("Users with the max reputation level will have a full bar."),
        'optionscode' => 'numeric',
        'value' => '50',
        'disporder' => 2,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    $settings[] = array(
        'name' => 'repbar_18_background',
        'title' => $db->escape_string("Background (bar color): "),
        'description' => $db->escape_string("Configure what color the reputation bar should be. "),
        'optionscode' => 'text',
        'value' => '#22a232',
        'disporder' => 3,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    $settings[] = array(
        'name' => 'repbar_18_textcolor',
        'title' => $db->escape_string("Text color: "),
        'description' => $db->escape_string("Configure text color. "),
        'optionscode' => 'text',
        'value' => '#ffffff',
        'disporder' => 4,
        'isdefault' => 0,
        'gid' => $group['gid']
    );		

    // insert the settings
    foreach($settings as $array => $setting) {
        $db->insert_query("settings", $setting); // lots of queries
    }
    rebuild_settings();
}

function repbars_18_deactivate () {
    global $db; 
	require MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets("postbit", '#'.preg_quote(' {$post[\'repbars_18\']}').'#', '',0);
    find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'repbars_18\']}').'#', '',0); 
    
    $query = $db->simple_select('settinggroups', 'gid', 'name = "repbars_18"'); // remove settings
    $groupid = $db->fetch_field($query, 'gid');
    $db->delete_query('settings','gid = "'.$groupid.'"');
    $db->delete_query('settinggroups','gid = "'.$groupid.'"');
    rebuild_settings();
}

function repbars_18_parse (&$post) {
    global $mybb, $templates, $cache, $repbars_18, $templates;
    
    $color = htmlspecialchars($mybb->settings['repbar_18_textcolor']);
    $background = htmlspecialchars($mybb->settings['repbar_18_background']);;

    if ($post['reputation'] <= $mybb->settings['repbar_18_min']) {
        $rep = 5; // 5% = Minimum bar, for asthetic purposes. 
    }
    else if ($post['reputation'] >= $mybb->settings['repbar_18_max']) {
        $rep = 100; 
    }
    // Calculate some percentages. 
    else {
        $rep = $post['reputation'] - $mybb->settings['repbar_18_min'];

        // Future release: Add floor division, to avoid situations where the CSS has to figure out widths such as 3.3333333333333%, etc. 
        $rep = $rep / ($mybb->settings['repbar_18_max'] - $mybb->settings['repbar_18_min']); 
        $rep = $rep * 100;
    }

    $post['repbars_18'] = '
Reputation: 
<div style="margin-top: 3px; padding: 0px; margin-right: 5px;">
    <div class="rep-meter" style="border-radius: 4px; padding: 2px; border: 1px solid #cccccc; width: 100%; ">
        <div class="rep-meter-inner" style="background-color: '.$background.'; color: '.$color.'; width: '.$rep.'%; ">
            ' . (int) $post['reputation'] .'
        </div>
    </div>    
</div>'; 
	return $post;
}