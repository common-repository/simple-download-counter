=== Simple Download Counter ===

Plugin Name: Simple Download Counter
Plugin URI: https://perishablepress.com/simple-download-counter/
Description: Simply counts the number of times your files are downloaded. Display download links and counts using shortcodes.
Tags: download counter, download manager, file manager, downloads, statistics
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 2.0
Version:    2.0
Requires PHP: 5.6.20
Text Domain: simple-download-counter
Domain Path: /languages
License: GPL v2 or later

Simply counts the number of times your files are downloaded. Display download links and counts using shortcodes.



== Description ==

Simple Download Counter (SDC) provides a simple yet powerful way to count file downloads. Works with any file type that's supported by WordPress (e.g., JPG, PNG, ZIP, MP4, TXT, and many more).


**How it works**

Visit the plugin settings to add a download file. Then use the shortcode to display a download link on any post or page. The plugin will then count every download and display it in the plugin settings. You can also display the download count on the front end using a shortcode.


**Features**

* Simple and powerful download counter
* Supports local and remote file URLs
* Supports any file type supported by WordPress
* Lightweight - entire plugin size is &lt; 160 KB
* Super fast and built for optimal performance
* Clean code tightly integrated with WordPress
* No Apache/.htaccess required

Check out the screenshots below to get a better idea of the plugin settings and more.


**How is this better?**

There are numerous "download counter" or "download manager" plugins available, but none of them satisfy all of my specific, stringent requirements:

* Current with latest WordPress
* Clean and simple code using WP APIs
* Lightweight with minimal useless features
* Supports all local and remote downloads
* No automatically created posts or pages
* No advertisements or weird admin notifications
* No obtuse styling of default WP Admin elements
* Download posts are visible only in the Admin Area
* Descriptive error handling for upload files
* No redundant or sloppy coding practices
* No requirement for cookies or sessions
* No requirement for Apache/.htaccess
* No weird database shenanigans

So I decided to build my own. Let me emphasize the utter simplicity of this plugin. It does one thing and does it well: counts the number of downloads for your files. And makes it easy to display download links and download counts anywhere on the front end.


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. The *only* thing this plugin does is count the number of times files are downloaded. So 100% privacy friendly for everyone.

Simple Download Counter is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).



== Installation ==

**Installing the plugin**

Activate like any other plugin and done. For usage instructions, continue reading to the "How to use" section below.

More info on [installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins).


**Uninstalling**

To uninstall/remove the plugin, visit the Plugins screen, deactivate and delete the plugin. When the plugin is deleted in this way, the plugin options are removed from the database automatically. 

Note that any defined downloads/files that may have been added will NOT be removed. Those are left in place in order for the site admin to decide whether or not to delete.


**How to use**

After activating the plugin, visit Downloads in the WP menu. There you can click "Add New" to add a download file. Here are the steps:

1. Visit Add New
2. Give the download a title
3. Upload your image by clicking "Upload File"
4. Optionally set the starting count and version
5. Publish the post and done!

After adding a download, you can display a download link on the front end. Here are the steps:

1. Visit Downloads to view a list of all downloads
2. In the "Shortcode" column, click button to copy shortcode
3. Visit any post or page and paste the shortcode
4. Save changes and done!

Note: If you’re getting 404 errors when trying to download, here is a [quick solution](https://wordpress.org/support/topic/note-about-404-errors-how-to-fix/).

To display the count (number of downloads) on any post or page, follow these steps:

1. Add the count shortcode `[sdc_count id="123"]` to your page
2. Change the `id` attribute value to match your download ID
3. Save changes and done!

Alternately you can display the download count (and other file information) next to the download link. To learn how, check out the next section on shortcodes.


**Shortcodes**

Description of shortcodes. Currently there are two shortcodes available: one to display download links, and another to display download counts.


**Display download link:** `[sdc_download]`

Attributes:

	id      (required) the download ID
	wrap    (optional) element to use for markup (p, div, span, none)
	text    (optional) download link text
	title   (optional) download link title
	before  (optional) content to display before the download link
	after   (optional) content to display after the download link
	class   (optional) custom class for markup (separate multiple classes with commas)
	type    (optional) display as "link", "button", or "none" (with none, only the before and after attributes are displayed, no link)

For example, a shortcode configuration that includes some possible attributes:

	[sdc_download id="123" wrap="div" text="%title%" before="Download " after=" %size%"]

That will display a download link that says, "Download {download title} 64 KB". And you can customize further with shortcut variables. Notice where it says `%title%` and `%size%`. Those are dynamic placeholders that output the download title and file size, respectively. You can use any of the following shortcuts for the `text`, `title`, `before`, and `after` attributes:

	%id%       displays the download ID
	%count%    displays the download count
	%title%    displays the download title
	%version%  displays the download version
	%type%     displays the download type (local or remote)
	%ext%      displays the download file extenstion (e.g., JPG)
	%size%     displays the download file size

Using these shortcut variables, it's possible to configure your download links however is desired.


**Display download count:** `[sdc_count]`

Attributes:

	id      (required) the download ID
	wrap    (optional) element to use for markup (p, div, span, none)
	before  (optional) content to display before the download count
	after   (optional) content to display after the download count
	class   (optional) custom class for markup (separate multiple classes with commas)

For example, a shortcode configuration that includes some possible attributes:

	[sdc_count id="123" wrap="div" before="Download count: " after=" - Version: %version%"]

That will display a line that says, "Download count: {download count} - Version: {download version}". You can customize further with shortcut variables. Notice where it says `%version%`. That outputs the download version. You can use any of the following shortcuts for the `before` and `after` attributes:

	%id%       displays the download ID
	%title%    displays the download title
	%version%  displays the download version
	%type%     displays the download type (local or remote)
	%ext%      displays the download file extenstion (e.g., JPG)
	%size%     displays the download file size

Using these shortcut variables, it's possible to configure your download counts however is desired.


**Display total download count:** `[sdc_count_total]`

This shortcode displays the total number of times any/all published downloads have been downloaded. 

	[sdc_count_total]

No attributes for this one, just add to page and enjoy results.


**Display the number of downloads:** `[sdc_downloads_published]`

A simple shortcode to display the number of published downloads:

	[sdc_downloads_published]

No attributes, just grab gulp and go.


**Display download metadata:** `[sdc_meta]`

Attributes:

	id      (required) the download ID
	title   (optional) the download title
	url     (optional) the download URL
	count   (optional) the download count
	version (optional) the download version
	type    (optional) the download type
	ext     (optional) the download file extension
	size    (optional) the download size

This shortcode displays any bit of meta information about the download. Here are some examples:

	[sdc_meta id="123" size="true"] // displays the file size for download ID 123
	[sdc_meta id="123" type="true"] // displays the file type for download ID 123
	[sdc_meta id="1728" title="true" count="true"] // displays the title and count

By default, only the `id` is required. All other attributes are optional. Here is a blank template for quick copy/paste:

	[sdc_meta id="" title="" url="" count="" version="" type="" ext="" size=""]

And here is another template with all available attributes set to `true`:

	[sdc_meta id="123" title="true" url="true" count="true" version="true" type="true" ext="true" size="true"]

Remember to be mindful of the `id` attribute, make sure it is correct according to your downloads.


Questions? Comments? Send &rsquo;em via the [contact form](https://plugin-planet.com/support/#contact) at Plugin Planet.



== Upgrade Notice ==

To upgrade this plugin, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.



== Screenshots ==

1. View Downloads screen
2. Edit Download screen
3. Plugin settings



== Frequently Asked Questions ==

**How to disable inline-block style on button forms?**

By default, download button forms are styled with inline-block. To disable and display as block-level, add the following code snippet [via theme functions or simple plugin](https://digwp.com/2022/12/custom-code-wordpress/):

`function simple_download_counter_button_style($style) { return ''; }
add_filter('simple_download_counter_button_style', 'simple_download_counter_button_style');`

No changes necessary, save and done.


**How to stop bots from crawling download URLs?**

By default, the plugin uses download URLs with the following structure:

	https://example.com/sdc_download/19890/?key=1234567890abc

In the Admin Area, the plugin displays these download URLs/links in various places, like the "Edit" screen. On the front end, the plugin does not add any download URLs/links. So by default, there is no way that any bots will know about any of your download links. As soon as you add a download link anywhere on the front end, bots will be able to find and crawl, etc. To prevent good/obedient bots from crawling your download links, add the following to your site's robots.txt file:

	User-agent: *
	Disallow: /sdc_download

Then to verify, use a free online robots.txt validator to make sure the new rules mesh well with any existing rules. Adding the above rules to robots.txt will prevent good bots (who obey robots.txt) from following the links.


**Is it possible to define custom file headers?**

Yes, here is a [tutorial](https://perishablepress.com/custom-headers-simple-download-counter/) that explains how to set it up.


**Got a question?**

Send any questions or feedback via my [contact form](https://plugin-planet.com/support/#contact)



== Changelog ==

If you like Simple Download Counter, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/simple-download-counter/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**2.0 (2024/10/10)**

* Improves function `simple_download_counter_count_total`
* Adds filter hook `simple_download_counter_total_sep`
* Updates plugin settings page
* Updates default translation template
* Tests on WordPress 6.7 (beta)


Full changelog @ [https://plugin-planet.com/wp/changelog/simple-download-counter.txt](https://plugin-planet.com/wp/changelog/simple-download-counter.txt)
