###  Magento SMTP Realy

Simple Magento extension to send all transactional e-mails through custom SMTP server.

#### Features:

+ Use any 3-rd party SMTP server to send all transactional e-mails
+ Add custom headers
+ Multipart support, include text version of HTML e-mail to improve deliverability of your most important e-mail messages (uses slighly modified version of: https://github.com/soundasleep/html2text) to create text version from HTML.


#### Screenshot:

![Screenshot](http://www.creativecast.de/ak/2015-03-30_152907.png)

#### Notes:

Overrides class ```Mage_Core_Model_Email_Template``` so handle with care when used with other e-mail software solutions.
