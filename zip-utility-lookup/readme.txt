Install and activate the plugin.



This creates a new custom post type called "Utilities."



==================


"Zip Codes" page:


Within the Utilities area, you can upload a zip code file. This file must be CSV format. Each line should be "zip code, utility abbreviation"



Example:
12345, COH
78704, ABC
60654, ETC

When a zip code file is uploaded, it overwrites everything that was there previously.



==================


"Utilities" page:


This is where the utilities are managed. You can set rates, upload files for the rates, and assign abbreviations to the utilities here.

The abbreviations to choose from are supplied from the zip code file page so you need to add a zip code with the abbreviation in order to assign it to a utility.



The rates show on the utility page.



==================


"Quake Zip Code Form Rate Lookup" widget:


This is the widget that allows a user to enter a zip code. You can place this widget wherever you'd like. The form routes a user to the appropriate utility based on the zip code they enter. The plugin searches for the zip code, finds it's utility abbreviation and looks up the utility that has that abbreviation. You should make sure that each abbreviation is only assigned to one utility, otherwise the plugin randomly chooses the first utility that matches the abbreviation.



On the utilities page, the details are shown to the user with a form to select a rate and enroll. When they press the Enroll button, they are sent to a gravity form that prepopulates with the rate and utility.



==================


Setting up the gravity form:


The gravity form does not allow for dynamic creation of rate fields based on utility, so this has to be set manually. The "Utility" select field is prepopulated with all utilities that are published, so you do not have to worry about setting the options for the utility field.

For each utility, you will need to create a "Rate" select field that has each rate in it that corresponds to the rates in the utility. So if a utility has a Rate 1 and a Rate 2, you will have to create a form field with options for the values of "1" (rate 1) and "2" (rate 2).

Then you will need to set the conditional logic on that rate field to only show when the utility has been chosen from the utilities select field.

One rate select field must be created for each utility manually. The options are not pre-loaded.




==================


Options page:


The options need to be set to correspond to your pages. 

You will have to identify which page you have set up the gravity form on that the user will be sent to after they select a rate and press 'enroll'. 

You will also have to identify what page you would like the user to be sent to when they click "back to choose another zip code" on the utility page. This page must have the widget on it so the user can enter a zip code.

