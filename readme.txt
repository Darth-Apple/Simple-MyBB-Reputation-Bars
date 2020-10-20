You are viewing the alpha version of a quick MyBB 1.8 plugin for reputation bars. It has been tested on a live forum and is safe for installation. However, we are still working on this plugin and some bugs may exist. Please report them and we will fix them promptly! :)

-------------------------------
Installation: 
-------------------------------

Upload the contents of the Upload folder to your forum root. 

-------------------------------
Settings: 
-------------------------------

Four settings are provided: 

 - Background (bar color)
 - Text color
 - Minimum rep
 - Maximum rep

The minimum and maximum rep are used to calculate widths for the reputation bar. If, for example, a user only has a reputation level of 5 but your minimum rep is 30, the bar will display an empty bar. You can adjust these accordingly. The default maximum (full bar level) is 50, but this can be changed to suit your preferences. 

-------------------------------
Templates: 
-------------------------------

One template is added under global templates titled "repbars_18_bar" - You may modify this to change the styling of your reputation bar accordingly. 

Two templates are modified on activation to display the bar. Although this modification is usually made successfully, some highly modified themes may behave in unexpected ways. If the bar does not display after activation, manually modify your postbox and postbit_classic templates, and add the following variable: {$post['repbars_18']}


-------------------------------
Info:
-------------------------------

License: GPL version 3. 
Translations: This plugin is GPL licensed. You may translate as you wish! Many thanks to those who translate my plugins, your work is very much appreciated. :)
Author: Darth-Apple (MyBB Community Forums) @ http://makestation.net