Reputation Bars: 

This is a simple plugin that creates a progress bar for user reputations on their posts (below their avatars). This plugin is safe for live installations and has been tested. As this is a first-version plugin, we encourage you to report any bugs or suggestions you may encounter! :)

-------------------------------
Installation: 
-------------------------------

Upload the contents of the Upload folder to your forum root. 

-------------------------------
Settings: 
-------------------------------

Four settings are provided: 

 - Enable/Disable on Postbit
 - Enable/Disable on Profile
 - Background (bar color)
 - Text color
 - Minimum rep
 - Maximum rep

The minimum and maximum rep are used to calculate widths for the reputation bar. If, for example, a user only has a reputation level of 5 but your minimum rep is 30, the bar will display an empty bar. You can adjust these accordingly. The default maximum (full bar level) is 50, but this can be changed to suit your preferences. 

-------------------------------
Templates: 
-------------------------------

One template is added under global templates titled "repbars_18_bar" - You may modify this to change the styling of your reputation bar accordingly. 

Two templates are modified on activation to display the bar. Although this modification is usually made successfully upon activation, it may fail on certain highly modified themes If the bar does not display after activation, manually modify your postbox and postbit_classic templates, and add the following variable: {$post['repbars_18']}. For user profiles, modify member_profile and add: {$memprofile['repbars_18']}


-------------------------------
Info:
-------------------------------

License: GPL version 3. 

Translations: This plugin is GPL licensed. You may translate as you wish! Many thanks to those who translate my plugins, your work is very much appreciated. :)

Developers: If you're interested in forking this plugin (or otherwise modifying it or extending it) please feel free. If you distribute a modified version, please leave a link and credit to the original plugin. That's my only request! 

Author: Darth-Apple (MyBB Community Forums) @ http://makestation.net