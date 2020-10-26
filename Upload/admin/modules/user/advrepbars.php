<?php

if(!defined("IN_MYBB"))
{
	die("Hacking Attempt.");
}

/* Load Language */
$lang->load("advrepbars");

/* Define the sub-tabs / menu for this ACP controller */
$sub_tabs['advrepbars_manage'] = array(
	'title' => $lang->advrepbars_manage,
	'link' => "index.php?module=user-advrepbars",
	'description' => $lang->advrepbars_manage_desc
);

$sub_tabs['advrepbars_new'] = array(
	'title' => $lang->advrepbars_new,
	'link' => "index.php?module=user-advrepbars&action=new",
	'description' => $lang->advrepbars_new_desc
);

if ($mybb->input['action'] == 'edit')
{
	$sub_tabs['advrepbars_edit'] = array(
		'title' => $lang->advrepbars_edit,
		'link' => "index.php?module=user-advrepbars&action=edit",
		'description' => $lang->advrepbars_edit_desc
	);
}

/* Output header */
$page->output_header($lang->advrepbars_title);

/* Handle incoming requests */
if (!$mybb->input['action'])
{
	/* View all Advanced Reputation Bars */
	$page->output_nav_tabs($sub_tabs, 'advrepbars_manage');

	/* One query to fetch all Reputation Bars */
	$advrepbars_query = $db->simple_select("advrepbars_bars", "*", "", array("order_by" => "disporder", "order_dir" => "ASC")); // Fetches by disporder
	$advrepbars = array();

	while ($advrepbar = $db->fetch_array($advrepbars_query))
	{
		array_push($advrepbars, $advrepbar);
	}

	/* Construct the table */
	$table = new Table;
	$table->construct_header("Preview", ['width' => '30%', 'class' => 'align_center']);
	$table->construct_header("Level", ['width' => '30%', 'class' => 'align_center']);
	$table->construct_header("Font Style", ['width' => '30%', 'class' => 'align_center']);
	$table->construct_header("OPTIONS", ['width' => '10%', 'class' => 'align_center']);

	/* Generate Dynamic Content */
	foreach ($advrepbars as $row)
	{
		/* Generate Preview */
		$advrepbars['preview_bar'];

		/* Generate Font Style Preview */
		$advrepbars['preview_style'] = '<span style="'.$row['fontstyle'].'">Font Preview: '.$row['bid'].'</span>';

		$table->construct_cell($preview, ['class' => 'align_center']);
		$table->construct_cell($row['level'], ['class' => 'align_center']);
		$table->construct_cell($row['preview_style'], ['class' => 'align_center']);
		
		/* Construct Options for Edit and Delete */
		$popup = new PopupMenu("advrepbars_{$row['bid']}", "Options");
		$popup->add_item('Edit', "index.php?module-user-advrepbars&action=edit&bid={$row['bid']}");
		$popup->add_item('Delete', "index.php?module-user-advrepbars&action=delete&bid={$row['bid']}");
		$table->construct_cell($popup->fetch(), ['class' => 'align_center']);
		/* Placeholder comment: Implement JS on the result Popup for the Delete option - onclick="return confirm('Are you sure you want to delete this bar? Action cannot be undone')" */

		/* Construct the Row */
		$table->construct_row();
	}

	/* In case table is empty, let us tell the user there are no bars */
	if ($table->num_rows() == 0)
	{
		$table->construct_cell('There are currently no bars yet, you can <a href="index.php?module-user-adverepbars&action=new">add one now</a>', ['colspan' => 4, 'class' => 'align_center']);
		$table->construct_row();
	}

	$table->output($lang->advrepbars_manage_table);

} elseif ($mybb->input['action'] == 'new')
{
	// Create a new Advanced Reputation Bar
	$page->output_nav_tabs($sub_tabs, 'advrepbars_new');
} elseif ($mybb->input['action'] == 'edit')
{
	// Edit an existing Advanced Reputation Bar
	$page->output_nav_tabs($sub_tabs, 'advrepbars_edit');
} else {
	flash_message("Unexpected error: The action you tried to access does not exist.", "error");
	admin_redirect("index.php?module=forum-advrepbars");
}

$page->output_footer($lang->advrepbars_title_acronym);