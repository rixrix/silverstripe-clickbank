Silverstripe ClickBank Module
============================

Maintainer Contacts
-------------------
*  Richard Sentino (<richard@mindginative.com>)

Requirements
------------
* SilverStripe 2.4+
* The [Member Profile Pages](https://github.com/ajshort/silverstripe-memberprofiles) module
* The [Orderable](https://github.com/ajshort/silverstripe-orderable) module 

Supported ClickBank Features
----------------------------

### ClickBank IPN Service
Instant Processing Notification service which handles the payment notifications from ClickBank service to your
Silverstripe web application. More information at [ClickBank IPN](http://www.clickbank.com/help/affiliate-help/affiliate-tools/instant-notification-service/) site.  

### ClickBank Module
The following list are the features/functionality of the module :

* Automatically create user account and roles after successful IPN transaction
* Thank You or After Purchase Page for each of your product
* Product page that can be linked to your ClickBank product item
* Member profile page
* Site admin IPN transaction logs for each member
* Authenticated file download for members
* Template for page customization

Installation Instructions
-------------------------
1. Place the module in the root of your Silverstripe installation. Make sure the directory name is 'clickbank' 
2. Visit yoursite.com/dev/build to rebuild the database.
3. By default, the module is not enabled. To enable it, you need to add the following into your mysite/_config.php file : <code>ClickBankManager::enable();</code> 

Todos
----- 
* Test cases
* More documentation 
* Admin report page
* ClickBank API integration
* Port to Silverstripe 3.0

Bugs / Feature Request 
----------------------
Please file any bugs or feature request at GitHub [Issue Tracker](https://github.com/rixrix/silverstripe-clickbank/issues)
