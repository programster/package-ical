<?php

require_once(__DIR__ . '/settings.php');


use Programster\Phpmailer\Attachment;
use Programster\Phpmailer\AttachmentCollection;
use Programster\Phpmailer\ContactCollection;
use Programster\Phpmailer\PhpMailerEmailer;
use Programster\Phpmailer\SecurityProtocol;

require_once(__DIR__ . '/../vendor/autoload.php');


$event = new Programster\iCal\Event(
    calendarName: "Development",
    eventName: "Simple Event",
    timeConfiguration: Programster\iCal\EventTimeConfig::createFullDay(2,3,2024, null),
    prodId: "Programster Calendars",
);

$icalFilepath = tempnam(sys_get_temp_dir(), "");
file_put_contents($icalFilepath, $event);

$emailer = new PhpMailerEmailer(
    smtpHost: SMTP_HOST,
    smtpUser: SMTP_USER,
    smtpPassword: SMTP_PASSWORD,
    securityProtocol: SecurityProtocol::tryFrom(SMTP_SECURITY_PROTOCOL),
    from: new \Programster\Phpmailer\Contact(SMTP_FROM_EMAIL, SMTP_FROM_NAME),
    smtpPort: SMTP_PORT,
);

$attachment = new Attachment(
    filepath: $icalFilepath,
    name: "calendar-event.ics",
    mimetype: "text/calendar"
);

$emailer->send(
    subject: "Calendar Event",
    plaintextMessage: "Test event",
    htmlMessage: "Test event",
    to: new ContactCollection(new \Programster\Phpmailer\Contact(SMTP_TO_EMAIL, SMTP_TO_NAME)),
    attachments: new AttachmentCollection($attachment)
);

print "Calendar event email sent." . PHP_EOL;