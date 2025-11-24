<?php // -*-mode: PHP; coding:utf-8;-*-
declare(strict_types=1);
namespace MRBS;

use IntlDateFormatter;

require_once 'lib/autoload.inc';

/**************************************************************************
 *   MRBS Configuration File
 *   Configure this file for your site.
 *   You shouldn't have to modify anything outside this file.
 *
 *   This file has already been populated with the minimum set of configuration
 *   variables that you will need to change to get your system up and running.
 *   If you want to change any of the other settings in systemdefaults.inc.php
 *   or areadefaults.inc.php, then copy the relevant lines into this file
 *   and edit them here.   This file will override the default settings and
 *   when you upgrade to a new version of MRBS the config file is preserved.
 *
 *   NOTE: if you include or require other files from this file, for example
 *   to store your database details in a separate location, then you should
 *   use an absolute and not a relative pathname.
 **************************************************************************/

/**********
 * Timezone
 **********/

// The timezone your meeting rooms run in. It is especially important
// to set this if you're using PHP 5 on Linux. In this configuration
// if you don't, meetings in a different DST than you are currently
// in are offset by the DST offset incorrectly.
//
// Note that timezones can be set on a per-area basis, so strictly speaking this
// setting should be in areadefaults.inc.php, but as it is so important to set
// the right timezone it is included here.
//
// When upgrading an existing installation, this should be set to the
// timezone the web server runs in.  See the INSTALL document for more information.
//
// A list of valid timezones can be found at http://php.net/manual/timezones.php
// The following line must be uncommented by removing the '//' at the beginning
$timezone = "Europe/Berlin";


/*******************
 * Database settings
 ******************/

// If you are using cPanel on your web server, make sure you include the prefix,
// typically 8 characters followed by an underscore, in your database name and
// database username.  For example $db_database = "abcdefgh_mrbs". (Note: this
// prefix is not the same as the table prefix below.)

// Which database system: "pgsql"=PostgreSQL, "mysql"=MySQL
$dbsys = "mysql";
// Hostname of database server. For pgsql, can use "" instead of localhost
// to use Unix Domain Sockets instead of TCP/IP. For mysql "localhost"
// tells the system to use Unix Domain Sockets, and $db_port will be ignored;
// if you want to force TCP connection you can use "127.0.0.1".
$db_host = "localhost";
// If you need to use a non standard port for the database connection you
// can uncomment the following line and specify the port number
//$db_port =1234;
// Database name:
$db_database = "c0mrbs1";
// Schema name.  This only applies to PostgreSQL and is only necessary if you have more
// than one schema in your database and also you are using the same MRBS table names in
// multiple schemas.
//$db_schema = "public";
// Database login user name:
$db_login = "c0mrbs1";
// Database login password:
$db_password = 'hm_V8gY2Te';
// Prefix for table names.  This will allow multiple installations where only
// one database is available
$db_tbl_prefix = "mrbs_";
// Set $db_persist to TRUE to use PHP persistent (pooled) database connections.  Note
// that persistent connections are not recommended unless your system suffers significant
// performance problems without them.   They can cause problems with transactions and
// locks (see http://php.net/manual/en/features.persistent-connections.php) and although
// MRBS tries to avoid those problems, it is generally better not to use persistent
// connections if you can.
$db_persist = false;


/* Add lines from systemdefaults.inc.php and areadefaults.inc.php below here
   to change the default configuration. Do _NOT_ modify systemdefaults.inc.php
   or areadefaults.inc.php.  */

/***********************************************
 * Authentication settings - read AUTHENTICATION
 ***********************************************/

// NOTE: if you are using the 'joomla', 'saml' or 'wordpress' authentication type,
// then you must use the corresponding session scheme.

$auth["type"] = "dbTenant"; // How to validate the user/password. One of
                      // "auth_basic", "cas", "config", "crypt", "db", "db_ext", "idcheck",
                      // "imap", "imap_php", "joomla", "ldap", "none", "nw", "pop3",
                      // "saml", "wix" or "wordpress".

   
/*********************************
 * Site identification information
 *********************************/

// Set to true to enable multisite operation, in which case the settings below are for the
// home site, identified by the empty string ''.   Other sites have their own supplementary
// config fies in the sites/<sitename> directory.
$multisite = false;
$default_site = '';

$mrbs_admin = "Dein Administrator";
$mrbs_admin_email = "jens.von_einsiedel@fli.de";
// NOTE:  there are more email addresses in $mail_settings below.    You can also give
// email addresses in the format 'Full Name <address>', for example:
// $mrbs_admin_email = 'Booking System <admin_email@your.org>';
// if the name section has any "peculiar" characters in it, you will need
// to put the name in double quotes, e.g.:
// $mrbs_admin_email = '"Bloggs, Joe" <admin_email@your.org>';

// The company name is mandatory.   It is used in the header and also for email notifications.
// The company logo, additional information and URL are all optional.

$mrbs_company = "";   // This line must always be uncommented ($mrbs_company is used in various places)

// Uncomment this next line to use a logo instead of text for your organisation in the header
$mrbs_company_logo = "/images/logo.jpg";    // name of your logo file.   This example assumes it is in the MRBS directory

// Uncomment this next line for supplementary information after your company name or logo.
// This can contain HTML, for example if you want to include a link.
//$mrbs_company_more_info = "You can put additional information here";  // e.g. "XYZ Department"

// Uncomment this next line to have a link to your organisation in the header
// $mrbs_company_url = "https://www.fli.de/";

// This is to fix URL problems when using a proxy in the environment.
// If links inside MRBS or in email notifications appear broken, then specify here the URL of
// your MRBS root directory, as seen by the users. For example:
$url_base =  "http://mrbs.intranet.fli.de/";


/*******************
 * Themes
 *******************/

// Choose a theme for the MRBS.   The theme controls two aspects of the look and feel:
//   (a) the styling:  the most commonly changed colours, dimensions and fonts have been
//       extracted from the main CSS file and put into the styling.inc file in the appropriate
//       directory in the Themes directory.   If you want to change the colour scheme, you should
//       be able to do it by changing the values in the theme file.    More advanced styling changes
//       can be made by changing the rules in the CSS file.
//   (b) the header:  the header.inc file which contains the function used for producing the header.
//       This enables organisations to plug in their own header functions quite easily, in cases where
//       the desired corporate look and feel cannot be changed using the CSS alone and the mark-up
//       itself needs to be changed.
//
//  MRBS will look for the files "styling.inc" and "header.inc" in the directory Themes/$theme and
//  if it can't find them will use the files in Themes/default.    A theme directory can contain
//  a replacement styling.inc file or a replacement header.inc file or both.

// Available options are:

// "default"        Default MRBS theme
// "classic126"     Same colour scheme as MRBS 1.2.6

$theme = "default";

// Use the $custom_css_url to override the standard MRBS CSS.
//$custom_css_url = 'css/custom.css.php';
$custom_css_url = 'css/dynamic.css.php';

// Use the $custom_js_url to add your own JavaScript.
//$custom_js_url = 'js/custom.js';

/******************
 * Display settings
 ******************/

// [These are all variables that control the appearance of pages and could in time
//  become per-user settings]

// Start of week: 0 for Sunday, 1 for Monday, etc.
$weekstarts = 1;

// Days of the week that are weekdays
$weekdays = array(1, 2, 3, 4, 5);

// Set this to true to add styling to weekend days
$style_weekends = true;

// To show week numbers in the main calendar, set this to true. The week
// numbers are only displayed if you set $weekstarts to start on the first
// day of the week in your locale and area's timezone.  (This assumes that
// the PHP IntlCalendar class is available; if not, the week is assumed to
// start on Mondays, ie the ISO stanard.)
$view_week_number = true;

// Um ​​die Wochennummern in den Minikalendern anzuzeigen, setzen Sie diesen Wert auf „true“. Die Wochenzahlen werden nur angezeigt, wenn Sie $weekstarts auf den Wochenanfang setzen.
// Siehe den Kommentar zum Wochenbeginn oben.
$mincals_week_numbers = true;

/***********************************************
 * Form values
 ***********************************************/

 $select_options  = array();
// It is possible to constrain some form values to be selected from a drop-
// down select box, rather than allowing free form input.   This is done by
// putting the permitted options in an array as part of the $select_options
// two-dimensional array.   The first index specifies the form field in the
// format tablename.columnname.    For example to restrict the name of a booking
// to 'Physics', 'Chemistry' or 'Biology' uncomment the line below.

//$select_options['entry.name'] = array('Physics', 'Chemistry', 'Biology');

// At the moment $select_options is only supported as follows:
//     - Entry table: name, description and custom fields
//     - Users table: custom fields

// For custom fields only (will be extended later) it is also possible to use
// an associative array for $select_options, for example

//$select_options['entry.catering'] = array('c' => 'Coffee','s' => 'Sandwiches','h' => 'Hot Lunch');

// In this case the key (eg 'c') is stored in the database, but the value
// (eg 'Coffee') is displayed and can be searched for using Search and Report.
// This allows you to alter the displayed values, for example changing 'Coffee'
// to 'Coffee, Tea and Biscuits', without having to alter the database.   It can also
// be useful if the database table is being shared with another application.
// MRBS will auto-detect whether the array is associative.
//
// Note that an array such as
//
$select_options['entry.catering'] = array('2' => 'Coffee',
                                           '4' => 'Sandwiches',
                                           '5' => 'Hot Lunch');
//
// will be treated as a simple indexed array rather than as an associative array.
// That's because (a) strictly speaking PHP does not distinguish between indexed
// and associative arrays and (b) PHP will cast any string key that looks like a
// valid integer into an integer.
//
// If you want to make the select field a mandatory field (see below) then include
// an empty string as one of the values, eg
//
//$select_options['entry.catering'] = array(''  => 'Please select one option',
//                                          'c' => 'Coffee',
//                                          's' => 'Sandwiches',
//                                          'h' => 'Hot Lunch');
//
$datalist_options = array();
// Instead of limiting the user to a fixed selection of options with $select_options,
// you can provide a list of options that serve as suggestions. However, the user can also enter their own input. (MRBS presents these via an HTML5 <datalist> element in browsers that support it. In browsers that don't, JavaScript emulation is used—except for IE6 and below, where a regular text input field is displayed.)
//
// As with $select_options, the array can be either a simple indexed array or an
// associative array, e.g., array('AL' => 'Alabama', 'AK' => 'Alaska', etc.).
// However, some users might find an associative array confusing, since the key is entered into the input field
// when the corresponding value is selected.
//
// Currently, $datalist_options is only supported for the same fields as
// $select_options (see above for details).

/**********************************************
 * Email settings
 **********************************************/

// BASIC SETTINGS
// --------------

// Set the email address of the From field. Default is 'admin_email@your.org'
$mail_settings['from'] = 'no-reply@fli.de';

// By default MRBS will send some emails (eg booking approval emails) as though they have come from
// the user, rather than the From address above.   However some email servers will not allow this in
// order to prevent email spoofing.   If this is the case then set this to true in order that the
// From address above is used for all emails.
$mail_settings['use_from_for_all_mail'] = true;

// By default MRBS will set a Reply-To address and use current user's email address.  Set this to
// false in order not to set a Reply-To address.
$mail_settings['use_reply_to'] = true;

// The address to be used for the ORGANIZER in an iCalendar event.   Do not make
// this email address the same as the admin email address or the recipients
// email address because on some mail systems, eg IBM Domino, the iCalendar email
// notification is silently discarded if the organizer's email address is the same
// as the recipient's.  On other systems you may get a "Meeting not found" message.
$mail_settings['organizer'] = 'no-reply@fli.de';

// Set the recipient email. Default is 'admin_email@your.org'. You can define
// more than one recipient like this "john@doe.com,scott@tiger.com"
$mail_settings['recipients'] = 'jens.von_einsiedel@fli.de';

// Set email address of the Carbon Copy field. Default is ''. You can define
// more than one recipient (see 'recipients')
$mail_settings['cc'] = '';

// Set to true if you want the cc addresses to be appended to the to line.
// (Some email servers are configured not to send emails if the cc or bcc
// fields are set)
$mail_settings['treat_cc_as_to'] = false;

// WHO TO EMAIL
// ------------
// The following settings determine who should be emailed when a booking is made,
// edited or deleted (though the latter two events depend on the "When" settings below).
// Set to true or false as required
// (Note:  the email addresses for the area and room administrators are set from the
// edit_area.php and edit_room.php pages in MRBS)
$mail_settings['admin_on_bookings']      = false;  // the addresses defined by $mail_settings['recipients'] below
$mail_settings['area_admin_on_bookings'] = false;  // the area administrator
$mail_settings['room_admin_on_bookings'] = true;  // the room administrator
$mail_settings['booker']                 = true;  // the person making the booking
$mail_settings['book_admin_on_approval'] = true;  // the booking administrator when booking approval is enabled
                                                   // (which is the MRBS admin, but this setting allows MRBS
                                                   // to be extended to have separate booking approvers)

// WHEN TO EMAIL
// -------------
// These settings determine when an email should be sent.
// Set to true or false as required
//
// (Note:  (a) the variables $mail_settings['admin_on_delete'] and
// $mail_settings['admin_all'], which were used in MRBS versions 1.4.5 and
// before are now deprecated.   They are still supported for reasons of backward
// compatibility, but they may be withdrawn in the future.  (b)  the default
// value of $mail_settings['on_new'] is true for compatibility with MRBS 1.4.5
// and before, where there was no explicit config setting, but mails were always sent
// for new bookings if there was somebody to send them to)

$mail_settings['on_new']    = true;   // when an entry is created
$mail_settings['on_change'] = true;  // when an entry is changed
$mail_settings['on_delete'] = true;  // when an entry is deleted

// It is also possible to allow all users or just admins to choose not to send an
// email when creating or editing a booking.  This can be useful if an inconsequential
// change is being made, or many bookings are being made at the beginning of a term or season.
$mail_settings['allow_no_mail']        = false;
$mail_settings['allow_admins_no_mail'] = false;  // Ignored if 'allow_no_mail' is true
$mail_settings['no_mail_default'] = false; // Default value for the 'no mail' checkbox.
                                           // true for checked (ie don't send mail),
                                           // false for unchecked (ie do send mail)


// WHAT TO EMAIL
// -------------
// These settings determine what should be included in the email
// Set to true or false as required
$mail_settings['details']   = true; // Set to true if you want full booking details;
                                     // otherwise you just get a link to the entry
$mail_settings['html']      = true; // Set to true if you want HTML mail
$mail_settings['icalendar'] = true; // Set to true to include iCalendar details
                                     // which can be imported into a calendar.  (Note:
                                     // iCalendar details will not be sent for areas
                                     // that use periods as there isn't a mapping between
                                     // periods and time of day, so the calendar would not
                                     // be able to import the booking)

// HOW TO EMAIL - LANGUAGE
// -----------------------------------------

// Set the language used for emails.  This should be in the form of a BCP 47
// language tag, eg 'en-GB'.  MRBS will use the language tag to set the locale
// for date and time formats, and find the best match in the lang.* files for
// translations.  For example, setting the admin_lang to 'en' will give English
// text and am/pm style times; setting it to 'en-GB' will give English text with
// 24-hour times.
$mail_settings['admin_lang'] = 'de';   // Default is 'en'.

/*******************
 * SMTP settings
 */

// These settings are only used with the "smtp" backend
$smtp_settings['host'] = '172.24.7.11';   // 172.24.7.11  // SMTP server
$smtp_settings['port'] = 25;           // SMTP port number
$smtp_settings['auth'] = false;        // Whether to use SMTP authentication
$smtp_settings['secure'] = '';         // Encryption method: '', 'tls' or 'ssl' - note that 'tls' means TLS is used even if the SMTP
                                       // server doesn't advertise it. Conversely if you specify '' and the server advertises TLS, TLS
                                       // will be used, unless the 'disable_opportunistic_tls' configuration parameter shown below is
                                       // set to true.
$smtp_settings['username'] = '';       // Username (if using authentication)
$smtp_settings['password'] = '';       // Password (if using authentication)

// The hostname to use in the Message-ID header and as default HELO string.
// If empty, PHPMailer attempts to find one with, in order,
// $_SERVER['SERVER_NAME'], gethostname(), php_uname('n'), or the value
// 'localhost.localdomain'.
$smtp_settings['hostname'] = '';

// The SMTP HELO/EHLO name used for the SMTP connection.
// Default is $smtp_settings['hostname']. If $smtp_settings['hostname'] is empty, PHPMailer attempts to find
// one with the same method described above for $smtp_settings['hostname'].
$smtp_settings['helo'] = '';

$smtp_settings['disable_opportunistic_tls'] = false; // Set this to true to disable
                                                     // opportunistic TLS
                                                     // https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting#opportunistic-tls
// If you're having problems with sending email to a TLS-enabled SMTP server *which you trust* you can change the following
// settings, which reduce TLS security.
// See https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting#php-56-certificate-verification-failure
$smtp_settings['ssl_verify_peer'] = true;
$smtp_settings['ssl_verify_peer_name'] = true;
$smtp_settings['ssl_allow_self_signed'] = false;

