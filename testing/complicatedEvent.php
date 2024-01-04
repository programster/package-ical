<?php

require_once(__DIR__ . '/settings.php');


use Programster\iCal\Attendee;
use Programster\iCal\AttendeeCollection;
use Programster\iCal\Contact;
use Programster\iCal\DateTimeCollection;
use Programster\iCal\Location;
use Programster\iCal\RecurConfig;
use Programster\Phpmailer\Attachment;
use Programster\Phpmailer\AttachmentCollection;
use Programster\Phpmailer\ContactCollection;
use Programster\Phpmailer\PhpMailerEmailer;
use Programster\Phpmailer\SecurityProtocol;
use Spatie\IcalendarGenerator\Enums\Classification;
use Spatie\IcalendarGenerator\Enums\EventStatus;
use Spatie\IcalendarGenerator\Enums\ParticipationStatus;
use Spatie\IcalendarGenerator\Enums\RecurrenceDay;
use Spatie\IcalendarGenerator\Enums\RecurrenceFrequency;
use Spatie\IcalendarGenerator\ValueObjects\RRule;

require_once(__DIR__ . '/../vendor/autoload.php');

$recuranceRule = RRule::frequency(RecurrenceFrequency::daily());
$recuranceRule->onWeekDay(RecurrenceDay::monday());
$recuranceRule->onWeekDay(RecurrenceDay::tuesday());
$recuranceRule->onWeekDay(RecurrenceDay::wednesday());
$recuranceRule->onWeekDay(RecurrenceDay::thursday());
$recuranceRule->onWeekDay(RecurrenceDay::friday());

$event = new Programster\iCal\Event(
    calendarName: "Development",
    eventName: "Morning Standup",
    timeConfiguration: Programster\iCal\EventTimeConfig::createTimespan(1704099600, 1704099600 + (30*60)),
    prodId: "Programster Calendars",
    uuid: "9aff639b-5846-405c-af1c-eb8a79ad99e5",
    description: "A daily stand-up for catching up on progress and discuss issues that may have arisen.",
    location: new Location("Meeting room 1"),
    organizer: new Contact(SMTP_TO_EMAIL, SMTP_TO_NAME),
    attendees: new AttendeeCollection(
        new Attendee(SMTP_TO_EMAIL, SMTP_TO_NAME, ParticipationStatus::needs_action()),
        new Attendee("dev1@gmail.com", "Dev 1", ParticipationStatus::accepted()),
        new Attendee("dev2@gmail.com", "Dev 2", ParticipationStatus::tentative()),
    ),
    status:  EventStatus::confirmed(),
    classification: Classification::public(),
    recurConfig: new RecurConfig(
        recurrenceRule: $recuranceRule,
        doNotRepeatOn: new DateTimeCollection(new DateTime("01-01-2024 09:00")) // bank holiday new years day.
    ),
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