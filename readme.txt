=== WPMU Plugin Stats ===
Contributors: DeannaS, kgraeme, MadtownLems
Tags: WPMU, Wordpress Mu, Wordpress Multiuser, Plugin Stats , multisite, network
Requires at least: 3.1
Tested up to: 3.5.1
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides info to site admins as to which plugins are activated sitewide, and which blogs plugins are activated on.

== Description ==

This plugin provides a snapshot view of which blogs are using any particular plugin. 

For sites that are using Plugin Commander to manage plugins, additional columns for the Plugin Commander settings of Auto Activate and User Controlled are included.

For sites that are using Plugin Manager, additional columns for the Plugin Manager settings of Auto Activate, User Controlled and Supporter Controlled are included.

Because the time to generate stats can be quite large, network plugin useage is cached and can be regenerated anytime via the "Regenerate" button.

For users of <a href="http://firestats.cc/wiki/WPMUPluginCommander">Plugin Commander</a> or <a href="http://wpmudev.org/project/wpmu-plugin-manager">Plugin Manager</a>, it also provides information on which plugins are auto activated, user controlled, or supporter-controlled (for Plugin Manager). 

= Development =

* Repository: [wp-repository](https://github.com/wp-repository) / [wpmu-plugin-stats](https://github.com/wp-repository/wpmu-plugin-stats)
* Issue-Tracker: [WPMU Plugin Stats Issues](https://github.com/wp-repository/wpmu-theme-usage-info/issues) **Please use the Issue-Tracker at GitHub!!**
* Translation: [Translate > WPMU Plugin Stats](https://translate.foe-services.de/projects/cets-plugin-stats)

== Installation ==

1. Place the cets\_plugin\_info.php file and directory in the wp-content/mu-plugins folder.
2. In 3.1+, go to network admin -> Plugins -> Plugin Stats to view information.

== Screenshots ==

1. Adminstrator view of list of plugins installed.
2. Administrator view of list of plugins installed with Plugin Commander settings.
3. Administrator view of list of plugins intalled with Plugin Manager settings. (View also shows regenerate button.)

== Changelog ==

= 1.5 =
* translation support
* fixes for WP 3.5
* fix + update of tablesort js library

= 1.3.2 =
* minor cleanups, should work with 3.4.2, and we'll go from here with better support!

= 1.2 =
* updated for new network admin menu in 3.1, eliminated use of plugin on less than WP 3.0

= 1.1 =
* minor tweak to eliminate content shift