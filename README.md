A PHP package to simplify the generation of calendar events/reminders.

## Example Usage

```php
<?php

require_once(__DIR__ . '/vendor/autoload.php');


$event = new Programster\iCal\Event(
    "Calendar Name",
    "Event Name",
    Programster\iCal\EventTimeConfiguration::createTimespan(time(), time() + 3600),
    "My App or Service Name",
);

print($event); // prints out the ical text.
```
