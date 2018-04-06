# splatr-public
This is the public repository for splatr, a cryptocurrency tracking platform written in PHP.

## Setup
To install your own version of splatr, follow these steps:

1. Clone this repository
2. Add a new file called dbcred.php, make sure it is protected
3. Add the following code:
```
<?php
//Set these for error reporting
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

define('DB_SERVER', 'sql.server.net');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_DATABASE', 'database_splatr');
?>
```

There is still some work on the setup, but I'm fixing it!
In the meantime, questions can be emailed to jesse.geens@gmail.com
