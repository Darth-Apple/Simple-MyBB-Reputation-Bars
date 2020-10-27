<?php

$templatelist = "repbars_18_bar,repbars_18_legend";

define('IN_MYBB', 1); require "./global.php";

//$lang->load("advrepbars");

add_breadcrumb("Reputation Bars Legend", "advrepbars.php");

/* We only need one page for the legend */
/* Generate the repbars */

$advrepbars = $mybb->cache->read('advrepbars');

$advrepbars_templ = '';

foreach ($advrepbars as $advrepbar)
{
    $post['reputation'] = $mybb->user['reputation'];
    $rep = $mybb->user['reputation'];
    $color = '';
    $background = $advrepbar['bgcolor'];
    $fontstyle = $advrepbar['fontstyle'];
    $max_width = '300px';
    eval("\$repbar = \"".$templates->get("repbars_18_bar")."\";");
    $advrepbars_templ .= $repbar;
}

eval("\$repbars_18_legend = \"".$templates->get("repbars_18_legend")."\";");
output_page($repbars_18_legend);