Analytics Reporter
==================

Reports analytics data.

Currently working with Piwik.

Installation via composer
-------------------------------
    {
        require: {
            "joaofigueira/analytics": "1.*"
        }
    }

Usage example
-------------
```php
<?php
use Analytics\DataSources\Piwik;
use Analytics\Querys\Browsers as Analytics; //check out the Querys folder, lots of ready to use Querys in there!

$result = new Analytics(new Piwik, $url, $token, $idSite); //engine(Piwik), url, token, idSite
$result ->startDate('2014-10-01')
		->endDate('2014-10-15');

$analyticsData 	= $result->fetchData();
$analyticsgraph	= $result->fetchGraph();
```

Extending
------------
```php
<?php
use Analytics\Analytics;

class NewExtension extends Analytics
{
  //here you can write your own code to query the API
}
```
