# sketchy-api
PHP wrapper for [Sketchy API](https://github.com/Netflix/sketchy).

API wrapper baseline code borrowed from [VirusTotal API](https://github.com/jayzeng/virustotal_apiwrapper).

## Usage
- Install via composer (http://getcomposer.org/)

Include the following in your composer.json
```json
{
  "require": {
    "demonslay335/sketchy-api": "master"
  }
}
```

```
composer update
```

## Quick Start
```php
<?php

require_once('vendors/autoload.php');

$apiUrl = 'your_sketchy_api_url';
$apiToken = 'your_sketchy_api_token';

$sketchy = new Sketchy\URL($apiUrl, $apiToken);
$response = $sketchy->capture('https://google.com');

var_dump($response);

?>
```

Sample output:
```
array(10) {
    ["job_status"]=>
    string(7) "CREATED"
    ["capture_status"]=>
    NULL
    ["created_at"]=>
    string(26) "2017-01-01 09:03:33.497800"
    ["html_url"]=>
    NULL
    ["id"]=>
    int(105)
    ["modified_at"]=>
    string(4) "None"
    ["scrape_url"]=>
    NULL
    ["sketch_url"]=>
    NULL
    ["url"]=>
    string(18) "https://google.com"
    ["url_response_code"]=>
    NULL
}
```

### Retrieve Capture Result
```php
<?php

$sketchy = new Sketchy\URL(API_URL_SKETCHY, API_TOKEN_SKETCHY);

$response = $sketchy->retrieveCapture(105);

var_dump($response);

?>
```

*Note the token is automatically appended to returned URLs for static access.*

Sample output:
```
array(12) {
  ["job_status"]=>
  string(9) "COMPLETED"
  ["retry"]=>
  int(0)
  ["sketch_url"]=>
  string(101) "https://yourserver.com/files/api.blockcypher.com_53627.png?token=your_token"
  ["capture_status"]=>
  string(22) "LOCAL_CAPTURES_CREATED"
  ["url"]=>
  string(45) "https://google.com"
  ["created_at"]=>
  string(26) "2017-01-01 09:03:33.497800"
  ["modified_at"]=>
  string(26) "2017-01-01 09:03:36.893800"
  ["html_url"]=>
  string(102) "https://yourserver.com/files/google.com_53627.html?token=your_token"
  ["scrape_url"]=>
  string(101) "https://yourserver.com/files/google.com_53627.txt?token=your_token"
  ["url_response_code"]=>
  int(200)
  ["status_only"]=>
  bool(false)
  ["id"]=>
  int(1)
}
```