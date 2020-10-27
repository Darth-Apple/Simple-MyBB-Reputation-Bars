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
	$advrepbars_query = $db->simple_select("advrepbars_bars", "*", "", array("order_by" => "level", "order_dir" => "ASC")); // Fetches by Level
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
		$row['preview_bar'] = '<div style="margin-top: 3px; padding: 0px; padding-right:3px; margin-right: 5px; max-width: 200px;text-align:center;margin:auto;" title="Preview">
        <div class="rep-meter" style="border-radius: 4px; padding: 2px; padding-right: 5px; border: 1px solid #cccccc; width: 100%; ">
            <div class="rep-meter-inner" style="background-color: '.$row['bgcolor'].'; width: 25%; text-align: left; padding-left:2px; ">
                <span style="'.$row["fontstyle"].'">'.$row["level"].'</span>
            </div>
        </div>    
    </div>';

		/* Generate Font Style Preview */
		$row['preview_style'] = '<span style="'.$row['fontstyle'].'">Font Preview: '.$row['bid'].'</span>';

		$table->construct_cell($row['preview_bar'], ['class' => 'align_center']);
		$table->construct_cell($row['level'], ['class' => 'align_center']);
		$table->construct_cell($row['preview_style'], ['class' => 'align_center']);
		
		/* Construct Options for Edit and Delete */
		$popup = new PopupMenu("advrepbars_{$row['bid']}", "Options");
		$popup->add_item('Edit Bar', "index.php?module=user-advrepbars&action=edit&bid={$row['bid']}");
		$popup->add_item('Delete Bar', "index.php?module=user-advrepbars&action=delete&bid={$row['bid']}", "return confirm('Are you sure you want to delete this bar? Action cannot be undone')");
		$table->construct_cell($popup->fetch(), ['class' => 'align_center']);

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
	/* Create a new Advanced Reputation Bar */
	$page->output_nav_tabs($sub_tabs, 'advrepbars_new');

	if ($mybb->request_method == 'post')
	{
		/* This is a form submit */

		/* Handle missing input */
		if (!$mybb->input['bgcolor'] ||!isset($mybb->input['level']))
		{
			flash_message("Either level or background color was missing, please try again", "error");
			admin_redirect("index.php?module=user-advrepbars&action=new");
			die();
		}

		$fontstyle = '';
		if ($mybb->input['fontstyle'])
		{
			$fontstyle = $db->escape_string($mybb->input['fontstyle']);
		}

		$insert_array = array(
			'level'		=> $db->escape_string($mybb->input['level']),
			'bgcolor' 	=> $db->escape_string($mybb->input['bgcolor']),
			'fontstyle'	=> $fontstyle,
			'dateline'	=> TIME_NOW
		);

		 $db->insert_query('advrepbars_bars', $insert_array);

		 flash_message("Successfully added reputation bar", "success");
		 admin_redirect("index.php?module=user-advrepbars");
	} else {
		/* Generate form */
		/* Placeholder Comment: Requires Language Abstraction for the Form:NEW */
		$form = new Form("index.php?module=user-advrepbars&amp;action=new", "post", "advrepbars", true);
		
		$form_container = new FormContainer("Add New Reputation Bar");
		$form_container->output_row("Level", "The required minimum reputation a user must achieve to have this reputation bar", $form->generate_numeric_field('level', '', array('id' => 'level', 'min' => 0), 'level'));
		$form_container->output_row("Background Color", 'Enter a hex color code or <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/linear-gradient">check out linear-gradients</a> <em>Exclude \'background:\'</em>', $form->generate_text_box('bgcolor', '', array('id' => 'bgcolor'), 'bgcolor'));
		$form_container->output_row("Font Style", "Customize the span containing the amount of reputation on the bar, this input will populate the style attribute", $form->generate_text_box('fontstyle', '', array('id' => 'fontstyle'), 'fontstyle'));		
	
		$form_container->end();
		$buttons = array();
		$buttons[] = $form->generate_submit_button("Save Reputation Bar");

		$form->output_submit_wrapper($buttons);
		$form->end();
	}
} elseif ($mybb->input['action'] == 'edit' && $mybb->input['bid'])
{
	// Edit an existing Advanced Reputation Bar
	$page->output_nav_tabs($sub_tabs, 'advrepbars_edit');

	if ($mybb->request_method == 'post')
	{
		/* This is a form submit */

		/* Handle missing input */
		if (!$mybb->input['bgcolor'] ||!isset($mybb->input['level']))
		{
			flash_message("Either level or background color was missing, please try again", "error");
			admin_redirect("index.php?module=user-advrepbars&action=new");
			die();
		}

		/* Make sure the bar already exists */
		$stripped_bid = $db->escape_string($mybb->get_input('bid', MyBB::INPUT_INT));
		$repbar_query = $db->simple_select("advrepbars_bars", "*", "bid='{$stripped_bid}'", array("limit" => 1));
		$repbar = $db->fetch_array($repbar_query);

		if (empty($repbar))
		{
			/* Reputation Bar does not exist */
			flash_message("Reputation bar does not exist and cannot be edited", "error");
			admin_redirect("index.php?module=user-advrepbars");
			die();
		}

		$fontstyle = '';
		if ($mybb->input['fontstyle'])
		{
			$fontstyle = $db->escape_string($mybb->input['fontstyle']);
		}

		$update_array = array(
			'level'		=> $db->escape_string($mybb->input['level']),
			'bgcolor' 	=> $db->escape_string($mybb->input['bgcolor']),
			'fontstyle'	=> $fontstyle
		);

		 $db->update_query('advrepbars_bars', $update_array, "bid='{$stripped_bid}'");

		 flash_message("Successfully saved reputation bar", "success");
		 admin_redirect("index.php?module=user-advrepbars");
	} else {
		/* Generate Form */

		/* Make sure the bar already exists */
		$stripped_bid = $db->escape_string($mybb->get_input('bid', MyBB::INPUT_INT));
		$repbar_query = $db->simple_select("advrepbars_bars", "*", "bid='{$stripped_bid}'", array("limit" => 1));
		$repbar = $db->fetch_array($repbar_query);

		if (empty($repbar))
		{
			/* Reputation Bar does not exist */
			flash_message("Reputation bar does not exist and cannot be edited", "error");
			admin_redirect("index.php?module=user-advrepbars");
			die();
		}

		/* Placeholder Comment: Requires Language Abstraction for the Form:EDIT */
		$form = new Form("index.php?module=user-advrepbars&amp;action=edit", "post", "advrepbars", true);
		
		$form_container = new FormContainer("Add New Reputation Bar");
		echo $form->generate_hidden_field('bid', $stripped_bid);
		$form_container->output_row("Level", "The required minimum reputation a user must achieve to have this reputation bar", $form->generate_numeric_field('level', $repbar['level'], array('id' => 'level', 'min' => 0), 'level'));
		$form_container->output_row("Background Color", 'Enter a hex color code or <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/linear-gradient">check out linear-gradients</a> <em>Exclude \'background:\'</em>', $form->generate_text_box('bgcolor', $repbar['bgcolor'], array('id' => 'bgcolor'), 'bgcolor'));
		$form_container->output_row("Font Style", "Customize the span containing the amount of reputation on the bar, this input will populate the style attribute", $form->generate_text_box('fontstyle', $repbar['fontstyle'], array('id' => 'fontstyle'), 'fontstyle'));		
	
		$form_container->end();
		$buttons = array();
		$buttons[] = $form->generate_submit_button("Save Reputation Bar");

		$form->output_submit_wrapper($buttons);
		$form->end();
	}
} elseif ($mybb->input['action'] == 'delete' && $mybb->input['bid'])
{
	/* Check if exists */
	$stripped_bid = $db->escape_string($mybb->get_input('bid', MyBB::INPUT_INT));
	$repbar_query = $db->simple_select("advrepbars_bars", "*", "bid='{$stripped_bid}'", array("limit" => 1));
	$repbar = $db->fetch_array($repbar_query);

	if (empty($repbar))
	{
		flash_message("Could not delete reputation bar, the bar does not exist", "error");
		admin_redirect("index.php?module=user-advrepbars");
		die();
	} else {
		/* Delete row */
		$db->delete_query("advrepbars_bars", "bid='{$stripped_bid}'");
		
		flash_message("Successfully deleted reputation bar", "success");
		admin_redirect("index.php?module=user-advrepbars");
	}
} else 
{
	flash_message("Unexpected error: The action you tried to access does not exist.", "error");
	admin_redirect("index.php?module=user-advrepbars");
}

$page->output_footer($lang->advrepbars_title_acronym);