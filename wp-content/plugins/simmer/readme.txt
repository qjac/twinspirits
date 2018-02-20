=== Recipes by Simmer ===
Contributors: gobwd
Tags: recipes, recipe, cooking, food, food blog, content marketing, food content marketing, drinks, drink recipes, tutorial maker, tutorials, recipe maker, baking, crafts, DIY, do it yourself
Requires at least: 3.8
Tested up to: 4.9
Stable tag: 1.3.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Recipes by Simmer is an intuitive recipe publishing tool for WordPress.

== Description ==

Professional recipe development is intuitive with the built-in items API of recipes by Simmer.

Upgrade to [Simmer Pro](https://simmerwp.com/#licensing) for complete nutrition content for Google Structured Data output, in addition to complete aggregate star rating support and training and documentation resources for customers only. 

Every WordPress theme handles recipes and custom post types differently. For customized support for your theme's code, [purchase a Simmer Pro license](https://simmerwp.com/#licensing) for premium support from Simmer [customer service](https://simmerwp.com/contact) and other benefits.

**Simple UI**

Add ingredients, step-by-step instructions, sub-headings, structured cook times, and a lot more all within a user-friendly drag and drop interface.

**Food Data Discovery**

Simmer automatically bakes semantic structure and [schema.org](http://schema.org) microdata into the core of every recipe you publish. This allows Google to standardize and serve your recipes across a variety of devices and platforms. In addition, our easy to understand recipe index structure allows you to create endless libraries and collections of specific recipes.

**Extendable**

Simmer is built with developers in mind. Utilize the many actions and filters detailed in the [Developer API](http://develop.simmerwp.com) to customize Simmer to fit any project. 

**Additional Features**

* Bulk-add ingredients & instructions as copy/pasted blocks of text
* Work with any social sharing tools including [Jetpack](https://jetpack.com/)
* All recipes are fully responsive & ready for mobile depending on your theme out of the box
* Create custom recipe card themes & styling easily
* Widgets: display your most recent recipes and/or recipe categories in your sidebar
* Recipe categories
* Recipe authors
* Add recipe servings, yield, cook times, and source name and/or link
* Embed recipes anywhere in your posts or pages
* Featured thumbnail for each recipe
* Front-end recipe printing
* Localization in over 150 languages, with [others always growing](https://translate.wordpress.org/projects/wp-plugins/simmer)

**Follow Simmer's development on at [simmerwp.com/blog](https://simmerwp.com/blog).**

Purchase a Simmer Pro license and premium support in addition to enhanced nutrition content functionality for this plugin. 

== Installation ==

1. Activate the plugin through the 'Plugins' menu in WordPress
2. Go to Settings > Recipes and configure the options
3. Go to Recipes > Add New to start creating with Simmer!
4. Have questions? Feel free to [consult the documentation](http://docs.simmerwp.com/).

== Screenshots ==

1. Simmer's recipe ingredients editor.
2. Simmer's recipe instructions editor.
3. Fine tune your recipe's detailed information.
4. Simmer's comprehensive settings screen for easily customizing your recipes.

== Frequently Asked Questions ==

= Is there a way to add an ingredient without a numerical measurement? (For example: if an ingredient is optional or added "to taste) =

Yes. Simply leave any measurement fields that you do not need blank, and they won't be included in your recipe at all. Then, write in "to taste" or any other instructions in the recipe name line itself or in your instructions.

= What settings can I control for recipes? = 

When activated, a new "Simmer" section is added to Settings in the WordPress dashboard. To see a list of configurable options, consult our free support [docs.simmerwp.com](https://simmer.zendesk.com/hc/en-us/articles/203867035-Configuring-Simmer-Settings).

= Can I add a customized measurement in the drop down for ingredients? = 

Whenever a recipe creator would like to use a unique measurement type not listed in Simmer's drop down UI, we suggest that they simply enter the measurement as part of the description instead. For example: 2 [dropdown empty] 15 oz cans diced tomatoes, 3 [dropdown empty] sprigs of thyme.

= Can I add things like cook time and prep time to my recipes? =

Yes, Simmer provides a number of customized options for each recipe to provide metadata, including an attribution link back to an original recipe author if you'd like to include one. If you choose not to include this data, it won't be labeled or included on your individual recipe's front end page. 

= If I do not include data like cook times or serving size to my recipe, will those labels still appear in my recipe? = 

No. Recipes created with Simmer do not display any labels for fields that are left empty. For example: if no serving size is included, this field will not be displayed or labeled on your recipe on the front end. 

== Changelog ==
= 1.3.11 = 
* New: Licensing updated/changed to GPLv3
* New: Simmer Private add-on card included in Extend admin section
* New: Add documentation notice to top of Extend section
* Fix: Small text changes for the plugin's admin views

= 1.3.9 = 
* Fix: some WPCS nags
* Fix: version number up to date
* Fix: Flushed rewrite rules on activation
* Fix: Flushed rewrite rules on deactivation
* Fix: Improved check before outputting recipe markup
* Fix: Double display check
* Fix: print stylesheet: reduce font size for recipe titles, remove floats, simplification of recipe meta

= 1.3.8 = 
* Fix: BuddyPress conflicts resolved
* Fix: Make sure we actually have a WP_Query object 
* Fix: Used instanceof instead of is_a
* Fix: Hardcoded textdomain
* New: Added grunt-wp-i18n support
* Fix: Made measurements translatable
* New: Regenerated pot file using grunt

= 1.3.7 = 
* Fix: Add lines for unit measurements to main .pot file
* New: Add pt_BR localization
* Fix: Trimmed whitespace
* New: Add plugin compatibility class
* Fix: Removed nopaging argument
* Fix: Improved escaping and sanitization
* Fix: Fix kilograms spelling in all .pot and .po files
* New: Fill in new unit measurements strings for es_ES

= 1.3.6 =
* New: Add Spanish localization

= 1.3.5 = 
* Fix: Plurals in ingredients
* New: Add add-on cards and assets

= 1.3.4 =
* Fix: Ingredients converting to headings when using bulk-add

= 1.3.3 =
* New: Ingredient heading support, just like instructions
* New: 'orderby' param for ingredient queries
* New: Optionally exclude headings in ingredient & instruction queries
* Tweak: Separate serving number & label meta fields & values
* Tweak: Reinstate the 'Quick Edit' post row action for recipes
* Tweak: Improve file & folder structure
* Tweak: Remove core plugin licensing
* Tweak: Improve add-on license detection
* Fix: Input autofocus for ingredients UI

= 1.3.2 =
* Tweak: Change default uninstall setting
* Tweak: Delete custom db tables on uninstall
* Fix: Error on uninstall

= 1.3.1 =
* Fix: Database upgrade procedure

= 1.3.0 =
* New: Items API for handling ingredients, instructions, and other custom recipe data
* New: Refactored architecture and file structure to allow for greater extensibility
* New: Major feature addition welcome screen
* Tweak: Documentation overhaul for the [Developer API reference](http://develop.simmerwp.com)

= 1.2.3 =
* Fix: Prevent theme icon font incompatibilities

= 1.2.2 =
* Fix: Ingredient amount display error with trailing or leading spaces

= 1.2.1 =
* New: Easy recipe printing
* Tweak: Refine microdata markup for single recipes
* Tweak: Ensure WordPress 4.2 compatibility
* Fix: Instructions microdata error

= 1.2.0 =
* New: Bulk add recipe ingredients or instructions
* New: 'Add Recipe' shortcode button and modal UI
* New: Enhanced embedded recipe display with excerpts and thumbnails
* New: [Developer API reference](http://develop.simmerwp.com)
* Tweak: Additional template classes and styling
* Tweak: Recipe author setting
* Tweak: Add more code documentation

= 1.1.0 =
* Add the Recent Recipes widget
* Add the Recipe Categories widget
* Add no-JS ingredient/instruction sorting
* Add shortcode recipe template with link & excerpt
* Adjust admin UI styling
* Change "attribution" to "source" and add label
* Remove < p > option from instructions list display
* Fix instructions list display setting
* Fix recipe excerpt display
* Update inline docs

= 1.0.3 =
* Add focus to input when adding new ingredients or instructions
* Add a plugin list table Settings link
* Fix a sortable UI scrolling bug
* Fix an admin JS error

= 1.0.2 =
Add licensing support

= 1.0.1 =
Fixed an early exit error on clean uninstall

= 1.0.0 =
Preheating the oven to 450...

== Upgrade Notice ==

= 1.3.0 =
This update makes significant database changes. It is recommended that you fully back up your recipes before proceeding.

= 1.0.1 =
This version fixes an error some encounter when attempting to uninstall the plugin. Please upgrade.
