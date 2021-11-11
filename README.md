# BIS Forms API

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## WordPress submission guidlines

https://wordpress.org/plugins/developers/add/

Test if Plugin URL will succeed:

https://wordpress.org/plugins/crm

That one won't. This one will:

https://wordpress.org/plugins/my-salescrm



An API to insert Gravity Forms to the Business Information System

## Installation

Upload the BIS Forms Plugin and activate it.

At the end of your child theme's functions.php file, add these lines:

```php
$bis_environment = "production";
$bis_debug = false;
include_once(ABSPATH . "wp-content/plugins/bis-forms/bis-functions.php");
```

If you don't have a functions.php file, then create one.

## Production versus Testing versus Localhost

The `BIS_MODE` constant determines if the system's environment should be in production or testing mode.
A third mode, automatically detected, is when REMOTE_ADDR is 127.0.0.1.

### Log file creation

Log files will be created when debugging is true. These start with `log_DDMMYYYY` and they are in the plugin directory. It may add up after a while so be sure to delete them.

In production mode, no log files will be created.

# Support

Contact Eugene on 27823096710 or email eugene@vander.host
# forms-api
