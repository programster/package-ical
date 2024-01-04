<?php

/*
 * This test checks that the createdAt parameter actually has an effect in ensuring the latest details
 * of the event are always kept, and not based on the order of emails received.
 */

require_once(__DIR__ . '/settings.php');


use Programster\Phpmailer\Attachment;
use Programster\Phpmailer\AttachmentCollection;
use Programster\Phpmailer\Contact;
use Programster\Phpmailer\ContactCollection;
use Programster\Phpmailer\PhpMailerEmailer;
use Programster\Phpmailer\SecurityProtocol;

require_once(__DIR__ . '/../vendor/autoload.php');

$eventId = "9b02edc1-d788-4de4-bb53-83b8251d2547";

$eventV1 = new Programster\iCal\Event(
    uuid: $eventId,
    calendarName: "Test Calendar",
    eventName: "Incorrect Order Event",
    timeConfiguration: Programster\iCal\EventTimeConfig::createFullDay(2,3,2024, null),
    prodId: "Programster Calendars",
    description: "This is the old description tht should have been overridden.",
    createdAt: \Brick\DateTime\Instant::of(time() - 1)
);

$eventV2 = new Programster\iCal\Event(
    uuid: $eventId,
    calendarName: "Test Calendar",
    eventName: "Incorrect Order Event",
    timeConfiguration: Programster\iCal\EventTimeConfig::createFullDay(2,3,2024, null),
    prodId: "Programster Calendars",
    description: "This is the new description that should override the first description.",
    createdAt: \Brick\DateTime\Instant::of(time() + 1)
);

$icalFilepath1 = tempnam(sys_get_temp_dir(), "");
$icalFilepath2 = tempnam(sys_get_temp_dir(), "");
file_put_contents($icalFilepath1, $eventV1);
file_put_contents($icalFilepath2, $eventV2);

$emailer = new PhpMailerEmailer(
    smtpHost: SMTP_HOST,
    smtpUser: SMTP_USER,
    smtpPassword: SMTP_PASSWORD,
    securityProtocol: SecurityProtocol::tryFrom(SMTP_SECURITY_PROTOCOL),
    from: new Contact(SMTP_FROM_EMAIL, SMTP_FROM_NAME),
    smtpPort: SMTP_PORT,
);

$attachment1 = new Attachment(
    filepath: $icalFilepath1,
    name: "calendar-event.ics",
    mimetype: "text/calendar"
);

$attachment2 = new Attachment(
    filepath: $icalFilepath2,
    name: "calendar-event.ics",
    mimetype: "text/calendar"
);

// Send the latest version of the event first, to test that it doesnt get overridden by the out of date details sent
// later.
$emailer->send(
    subject: "Calendar Event",
    plaintextMessage: "Test event",
    htmlMessage: "Out of order event. This one should be added first to your calendar, and not be overridden by the secondary email.",
    to: new ContactCollection(new Contact(SMTP_TO_EMAIL, SMTP_TO_NAME)),
    attachments: new AttachmentCollection($attachment2)
);

$emailer->send(
    subject: "Calendar Event",
    plaintextMessage: "Test event",
    htmlMessage: "Out of order event. This one should not override the previous event because created at is earlier.",
    to: new ContactCollection(new Contact(SMTP_TO_EMAIL, SMTP_TO_NAME)),
    attachments: new AttachmentCollection($attachment1)
);

print "Calendar event email sent." . PHP_EOL;