Instructions for the eRecht24 legal text plugin for TYPO3
=======================================================

How to access the settings of the eRecht24 legal text plugin
--------------------------------------------------------------

Specifying the API key
------------------------------

This plugin offers [eRecht24 Premium users](https://www.e-recht24.de/mitglieder/) the ability to transfer the legal texts from the eRecht24 Project Manager directly into TYPO3. To enable the necessary communication between both sides, the provision of an API key is necessary.

To do this, proceed as follows:

1. Create a project for your website in the [eRecht24 Projekt Manager](https://www.e-recht24.de/mitglieder/tools/projekt-manager/), if one does not already exist.
2. There, click the Settings icon (gear icon) next to the project name.
3. Click the _Create new API key_ button.
4. Copy the API key to the clipboard.
5. In the TYPO3 backend, switch to the eRecht24 legal texts plugin.
6. add the API key to the corresponding field under the _API key_ tab.
7. Enter the URL of your website under the _Domain_ tab and select the respective _Site Configuration_.
8. Click _Create configuration_ to apply the setting.
9. If you want to use the legal text plugin with more websites in your TYPO3 installation, you can create additional configurations by repeating these steps.


  Click _Create configuration_ to apply the setting.

**Your eRecht24 legal text plugin can now communicate with the eRecht24 Project Manager.**

Transfer and insert imprint
---------------------------------

### Storage of the imprint

1. Create your imprint in the [eRecht24 Project Manager](https://www.e-recht24.de/mitglieder/tools/projekt-manager/).
2. Go to the settings of the eRecht24 legal text plugin, click on the _Imprint_ tab.
3. For Data source, select the option _eRecht24 Project Manager_.
4. Click the _Synchronize and save data_ button below to transfer your imprint from the eRecht24 Project Manager.

**Your imprint is now saved in the configuration of your eRecht24 legal text plugin.**

_**Notice**: Synchronize your data in the future after each change of the imprint in the eRecht24 Project Manager. For liability reasons, every update of the imprint must be done and checked manually by the website operator. Therefore, an automatic import is not provided._

### Integrating the imprint into a page

1. Open the editing view of your page, in which the imprint should be displayed in the future.
2. Click on the _+ Inthalt_ button and select the eRecht24 legal texts item under the _Plug-Ins_ tab.
3. In the dialog that appears, select _Imprint_ from the drop-down list under the _Plug-In_ tab for "_Which document type should be displayed?_" and click on it.
4. Under „_Which language should it be displayed in?_“, select the desired display language.
5. Save your page.

_**Notice**: The imprint is created in the eRecht24 Project Manager with the heading "Imprint". If your page also has the heading "Imprint", this could lead to duplication. In this case, activate the option "Remove H1 heading from text" in the plug-in menu under step 3. Then the heading will be removed from the imprint text in the display._

Transfer and insert privacy policy
--------------------------------------------

### Storage of the privacy policy

To retrieve your privacy policy, please proceed as described under the section _Imprint_.

### Integrating the privacy policy into a page

To integrate your privacy policy, please proceed as described under the section _Imprint_. Under „_Which document type should be displayed?_“, select the option _privacy policy_.

_**Notice**: Always include the imprint and privacy policy on separate pages, as this is required by law. Make both pages accessible by link from every subpage of the website._

Transfer and insert privacy policy for social media profiles
---------------------------------------------------------------------

### Storage of the privacy policy for social media profiles

To retrieve your privacy policy for social media profiles, please proceed as described under the section _Imprint_.

### Integrating the privacy policy for social media profiles into a page

To integrate your privacy policy for social media profiles, please proceed as described under the section _Imprint_. Under „_Which document type should be displayed?_“, select the option _privacy policy for social media profiles_.

_**Important**: If you link to your privacy policy page from your social media profile, please add the anchor point #socialmediaprofile at the end of the URL after copying the URL to your privacy policy into your social media profile. This way, the page view will immediately jump to the passage for the social media profiles after the link is called up._

Integrating Google Analytics Tracking Code
------------------------------------------

1. Unless you already have a Google Analytics tracking ID for your site, use your Google Account to create a tracking code for your site ([see these instructions](https://support.google.com/analytics/answer/1008015?hl=de)).
2. Switch to the eRecht24 legal texts plugin here, click on the _Google Analytics_ tab.
3. Then copy the ID of the tracking code (example: UA-1234567-1) into the _Google Analytics ID_ field.
4. If the Google Analytics tracking code is to be inserted by the eRecht24 legal text plugin, activate the option _Insert tracking code_.
5. Save the setting.

_**Important**: If you integrate the Google Analytics tracking code and / or the opt-out code via the eRecht24 legal text plugin, please make sure that this code is not also integrated by the template or other extensions of TYPO3. Otherwise, this may lead to functional errors, faulty tracking or compromise legal security._

Updating the imprint and privacy policy
--------------------------------------------------------------

Occasionally, the texts of your imprint and privacy policy need to be updated because, for example, wording has changed or new points have been included.

### Revision of the legal text

To do this, first run through the corresponding generator in the eRecht24 Project Manager as usual. After that, you have the following options to transfer your legal texts to your plugin:

### Method 1: Update directly from the eRecht24 Project Manager

In the eRecht24 Project Manager, click on the synchronize icon in the line with your legal text. The eRecht24 Project Manager establishes a connection to your eRecht24 legal text plugin, which then retrieves the updated legal text.

_**Important**: The legal texts can only be synchronized if you have selected eRecht24 Projekt Manager as the data source for the respective legal texts in the configuration of your plugin._

### Method 2: Fetching the changed legal text in the eRecht24 legal text plugin

In addition, you also have the option to retrieve imprint and privacy policy directly from the eRecht24 legal texts plugin from the eRecht24 Project Manager and to overwrite the previously saved version of the texts in the plugin. To do this, use the button _Synchronize all legal texts_ and save in the plugin configuration or the button _Synchronize data and save_ behind the tab of the individual legal text.

_**Important**: The legal texts can only be synchronized if you have selected eRecht24 Projekt Manager as the data source for the respective legal texts in the configuration of your plugin._

### Method 3: Manual copying into your eRecht24 legal texts plugin

In the eRecht24 Project Manager, click on the HTML link in the line with your legal text and, in the dialog that opens, click on the _copy HTML code to clipboard_ button at the bottom.

Then open the configuration in the eRecht24 legal text plugin on your website. Call up the tab for the associated legal text. Make sure that the selection for _Data source_ is set to _Local version_, remove the previous legal text in the text field and paste your updated legal text from the clipboard. Finish the process by clicking the Save button.

### Note for all above methods

If you use caching in your website, please check whether the cache for the relevant pages with the legal texts may need to be cleared again so that the updated content is displayed.
