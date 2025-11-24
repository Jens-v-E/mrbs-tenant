<?php
declare(strict_types=1);
namespace MRBS;

require_once "defaultincludes.inc";
require_once "mrbs_sql.inc";
require_once "functions_mail.inc";

// entry_id holen
$entry_id = isset($_REQUEST['entry_id']) ? (int)$_REQUEST['entry_id'] : 0;
if ($entry_id <= 0) {
    fatal_error("Keine gültige Buchungs-ID übermittelt.");
}

// Buchung laden
$sql = "
    SELECT id, name, room_id, start_time, end_time, create_by
    FROM " . _tbl('entry') . "
    WHERE id = ?
    LIMIT 1
";

$res = db()->query($sql, [$entry_id]);
$entry = ($res ? $res->next_row_keyed() : null);

if (!$entry) {
    fatal_error("Buchung konnte nicht geladen werden.");
}

// Zeiten bestimmen
$start = (int)$entry['start_time'];
$day   = (int)strftime('%d', $start);
$month = (int)strftime('%m', $start);
$year  = (int)strftime('%Y', $start);

// area_id holen
$area_id = mrbsGetRoomArea((int)$entry['room_id']);

// Ersteller bestimmen
$creator_username = trim((string)$entry['create_by']);
if ($creator_username === '') {
    fatal_error("Für diese Buchung ist kein Ersteller gespeichert.");
}

$creator_user = auth()->getUser($creator_username);
$creator_email = ($creator_user && !empty($creator_user->email)) ? $creator_user->email : '';

if ($creator_email === '') {
    fatal_error("Für den Ersteller dieser Buchung ist keine E-Mail-Adresse gespeichert.");
}

// ✔ moderne MRBS-Header-Funktion: Kontext-Array!
print_header([
    'day'   => $day,
    'month' => $month,
    'year'  => $year,
    'area'  => $area_id,
    'room'  => $entry['room_id']
]);
?>

<h2>Kontakt zum Ersteller der Buchung</h2>

<p>Sie schreiben an den Ersteller folgender Buchung:</p>

<ul>
  <li><strong>Titel:</strong>
    <?php echo htmlspecialchars($entry['name'], ENT_QUOTES, 'UTF-8'); ?>
  </li>
  <li><strong>Start:</strong>
    <?php echo htmlspecialchars(strftime('%d.%m.%Y %H:%M', $start)); ?>
  </li>
  <li><strong>Ende:</strong>
    <?php echo htmlspecialchars(strftime('%d.%m.%Y %H:%M', (int)$entry['end_time'])); ?>
  </li>
</ul>

<form action="contact_submit.php" method="post">

  <input type="hidden" name="entry_id" value="<?php echo (int)$entry['id']; ?>">
  <input type="hidden" name="creator_email" value="<?php echo htmlspecialchars($creator_email, ENT_QUOTES, 'UTF-8'); ?>">

  <p>
    <label for="guest_name">Ihr Name:</label><br>
    <input type="text" id="guest_name" name="guest_name" required style="width:300px;">
  </p>

  <p>
    <label for="guest_email">Ihre E-Mail-Adresse:</label><br>
    <input type="email" id="guest_email" name="guest_email" required style="width:300px;">
  </p>

  <p>
    <label for="guest_message">Ihre Nachricht:</label><br>
    <textarea id="guest_message" name="guest_message" rows="8" cols="60" required></textarea>
  </p>

  <p>
    <input type="submit" value="Nachricht an den Ersteller senden">
  </p>

</form>

<?php print_footer(); ?>
