<?php
declare(strict_types=1);
namespace MRBS;

require_once "defaultincludes.inc";
require_once "functions_mail.inc";
require_once "mrbs_sql.inc";

// Ausgabe UTF-8
header("Content-Type: text/html; charset=UTF-8");

// POST Variablen einlesen
$entry_id      = isset($_POST['entry_id']) ? (int)$_POST['entry_id'] : 0;
$creator_email = trim($_POST['creator_email'] ?? '');
$guest_name    = trim($_POST['guest_name'] ?? '');
$guest_email   = trim($_POST['guest_email'] ?? '');
$guest_message = trim($_POST['guest_message'] ?? '');

// Validierung
if ($entry_id <= 0) { fatal_error("Ungültige Buchungs-ID."); }
if ($guest_name === '' || $guest_email === '' || $guest_message === '') {
    fatal_error("Bitte alle Felder ausfüllen.");
}
if ($creator_email === '') {
    fatal_error("Fehler: Keine Zieladresse definiert.");
}

// Buchung aus DB laden
$sql = "
    SELECT id, name, room_id, start_time, end_time, create_by
    FROM " . _tbl('entry') . "
    WHERE id = ?
    LIMIT 1
";
$res = db()->query($sql, [$entry_id]);
$entry = ($res ? $res->next_row_keyed() : null);

if (!$entry) {
    fatal_error("Buchung nicht gefunden.");
}

// Datum für Header
$start = (int)$entry['start_time'];
$day   = (int)strftime('%d', $start);
$month = (int)strftime('%m', $start);
$year  = (int)strftime('%Y', $start);
$area_id = mrbsGetRoomArea((int)$entry['room_id']);

// =======================================
// 1) E-MAIL AN BUCHUNGS-ERSTELLER
// =======================================

$subject_creator = "Anfrage zu Ihrer Buchung: " . $entry['name'];

$headers_creator = [
    "From: {$guest_name} <{$guest_email}>",
    "Reply-To: {$guest_email}",
    "Content-Type: text/plain; charset=UTF-8",
    "Content-Transfer-Encoding: 8bit"
];

$body_creator =
"Sie haben eine Anfrage zu Ihrer Buchung im MRBS-System erhalten.\n\n" .
"Buchungstitel: " . $entry['name'] . "\n" .
"Start: " . strftime('%d.%m.%Y %H:%M', $entry['start_time']) . "\n" .
"Ende: " . strftime('%d.%m.%Y %H:%M', $entry['end_time']) . "\n\n" .
"Absender:\n" .
$guest_name . " <" . $guest_email . ">\n\n" .
"Nachricht:\n" .
$guest_message . "\n\n";

// E-Mail an Ersteller senden
mail(
    $creator_email,
    '=?UTF-8?B?' . base64_encode($subject_creator) . '?=',
    $body_creator,
    implode("\r\n", $headers_creator)
);


// =======================================
// 2) BESTÄTIGUNGSMAIL AN DEN GAST (NEU)
// =======================================

$subject_guest = "Bestätigung Ihrer Anfrage zur MRBS-Buchung";

$headers_guest = [
    "From: noreply@" . $_SERVER['SERVER_NAME'],
    "Content-Type: text/plain; charset=UTF-8",
    "Content-Transfer-Encoding: 8bit"
];

$body_guest =
"Hallo {$guest_name},\n\n" .
"wir bestätigen den Eingang Ihrer Nachricht zur folgenden MRBS-Buchung:\n\n" .
"Buchungstitel: " . $entry['name'] . "\n" .
"Datum: " . strftime('%d.%m.%Y', $entry['start_time']) . "\n" .
"Zeit: " . strftime('%H:%M', $entry['start_time']) .
" - " . strftime('%H:%M', $entry['end_time']) . "\n\n" .

"Ihre Nachricht:\n" .
$guest_message . "\n\n" .

"Hinweis:\n" .
"Die E-Mail-Adresse des Erstellers bleibt aus Datenschutzgründen anonym.\n" .
"Ihre Anfrage wurde automatisch weitergeleitet.\n\n" .

"Vielen Dank.\n" .
"Ihr MRBS-System\n";

// E-Mail an Gast senden
mail(
    $guest_email,
    '=?UTF-8?B?' . base64_encode($subject_guest) . '?=',
    $body_guest,
    implode("\r\n", $headers_guest)
);


// =======================================
// HTML-AUSGABE
// =======================================

print_header([
    'day'   => $day,
    'month' => $month,
    'year'  => $year,
    'area'  => $area_id,
    'room'  => $entry['room_id']
]);
?>

<h2>Nachricht gesendet</h2>

<p>Ihre Nachricht wurde erfolgreich an den Ersteller der Buchung gesendet.</p>

<ul>
    <li><strong>Buchung:</strong> <?php echo htmlspecialchars($entry['name']); ?></li>
    <li><strong>Ziel:</strong> Ersteller der Buchung (E-Mail geschützt)</li>
</ul>

<p>Eine Bestätigung wurde an Ihre E-Mail-Adresse gesendet.</p>

<p><a href="index.php">Zurück zur Übersicht</a></p>

<?php print_footer(); ?>
