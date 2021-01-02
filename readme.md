<h2>Reputation Bars</h2>

This plugin allows you to create progress bars based on users reputation level on their posts (below their avatars). The plugin is safe for live installations and has been tested on a clean and default MyBB installation.

_Note: The plugin has recently been remade, as such we hope you can help by reporting to us any bugs and/or issues you experience with the plugin for improvement._

**Installation:**

 - Upload the contents of the Upload folder to your forum root. 
 - Enable from ACP -> Configuration -> Plugins
 - Setup your reputation bars from ACP -> Users & Groups -> Advanced Reputation Bars

 _A special thanks to @Darth-Apple for providing the base of the plugin before expansion._

**Settings:** 

Two settings are provided: 

 - Enable/Disable on Postbit
 - Enable/Disable on Profile

And inside 'AdminCP -> Users & Groups' there is a submenu named "Advanced Reputation Bars" where you can create and set up your reputation bars and the reputation required*.

*The setup for your reputation bars should follow a simple logic, the conditions are displayed here:

1. The reputation bar with the lowest reputation possible is the first reputation bar any user can get and defaults from 0 (lowest) to the level on the reputation bar
2. The reputation bar with the highest reputation possible acts as the "highest level", once achieved the bar will be completely filled (100% width) _Note: The persons reputation is still displayed although going above the limit_
3. From one reputation bar to another, as long as the 'level' amount of the next bar is achieved, the user is granted that bar and it counts as 0% whereas the next bars' 'level' amount counts as 100%.

**Templates:**

Two templates are added under global templates.

The first is titled "repbars_18_bar" - You may modify this to change the styling of your reputation bar accordingly. 

The second is titled "repbars_18_legend" - This is the default page template for the {mybb_url}/advrepbars.php page.

Two templates are modified on activation to display the bar. Although this modification is usually made successfully upon activation, it may fail on certain highly modified themes If the bar does not display after activation, manually modify your postbox and postbit_classic templates, and add the following variable: {$post['repbars_18']}. For user profiles, modify member_profile and add: {$memprofile['repbars_18']}

**Info:**

License: GPL version 3. 

Translations: This plugin is GPL licensed. You may translate as you wish! Many thanks to those who translate my plugins, your work is very much appreciated. :)

Developers: If you're interested in forking this plugin (or otherwise modifying it or extending it) please feel free. If you distribute a modified version, please leave a link and credit to the original plugin. That's my only request! 

Author: Xazin (GodLess101 on MyBB Community Forums)

Original Author: Darth-Apple (MyBB Community Forums) @ http://makestation.net @ Credited for: https://github.com/Darth-Apple/Simple-MyBB-Reputation-Bars 
