<?php
header("Content-type: text/css");
require_once('../config.inc.php');

try {
    $db_dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_database;
    $pdo = new PDO($db_dsn, $db_login, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("/* Datenbankverbindung fehlgeschlagen: " . $e->getMessage() . " */\n");
}

// Räume aus der Datenbank abrufen (id, name, bg_color)
$query = "SELECT id, name, bg_color FROM mrbs_room";
$stmt = $pdo->query($query);

$rooms = [];
echo "/* Dynamische Raumfarben für MRBS: Tages-, Wochen- und Monatsansicht */\n";

/* --- CSS-Teil: Standard-Selektoren (Tages-/Wochen-/Monats-Varianten) --- */
while ($room = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($room['bg_color'])) {
        $room_id = (int)$room['id'];
        $room_name = $room['name'];
        $color = htmlspecialchars($room['bg_color']);

        // Sammle Räume für das JS am Ende
        $rooms[] = [
            'id' => $room_id,
            'name' => $room_name,
            'color' => $color
        ];

        echo "\n/* Raum-ID: {$room_id} — " . addslashes($room_name) . " */\n";

        // Tagesansicht (dein Original)
        echo "th[data-room=\"{$room_id}\"] { background-color: {$color} !important; }\n";
        echo ".room_{$room_id} { background-color: {$color} !important; }\n";

        // Wochenansicht (häufige Klassen/Attribute)
        echo "td.room_{$room_id}, td.cell_{$room_id}, .week_room_{$room_id}, .mrbs_week .room_{$room_id} { background-color: {$color} !important; }\n";
        echo "table.week_view th.room_{$room_id}, .mrbs-week th[data-room=\"{$room_id}\"] { background-color: {$color} !important; }\n";

        // Monatsansicht (häufige Klassen)
        echo ".month_room_{$room_id}, td.month_room_{$room_id}, .monthview .room_{$room_id}, .mrbs_month .room_{$room_id} { background-color: {$color} !important; }\n";
        echo ".month_view th.room_{$room_id} { background-color: {$color} !important; }\n";

        // Option im Select hervorheben (falls gewünscht)
        echo "select.room_area_select option[value=\"{$room_id}\"] { background-color: {$color}; }\n";
    }
}

/* --- JavaScript-Fallback: falls die Wochen/Monats-HTML keine Klassen/Attribute hat --- */
/* Wir geben das JS als CSS-kommentierte <script>-Block aus. MRBS lädt diese 'CSS' Datei in <link rel="stylesheet">.
   Einige Browser ignorieren <script> in CSS, daher ist es besser, dieses JS per <script src="..."> einzubinden.
   Falls du die Datei per <link> einbindest, füge alternativ folgendes JS-Snippet in ein <script> in deinem Template ein.
*/

echo "\n/* FALLBACK-JS: Wenn die HTML-Struktur keine Raum-Klassen/Attribute hat, benutze dieses JS (als <script> in deinem MRBS-Template einfügen) */\n";

$rooms_json = json_encode($rooms, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT);

echo "/* JS_DATA: {$rooms_json} */\n";

/* Zum Schluss geben wir noch eine Anleitung aus (als Kommentar) */
echo "\n/* Anleitung:
   1) Wenn möglich: binde diese Datei per <link rel=\"stylesheet\" href=\"/pfad/zu/room_colors.php\"> (wie bisher).
   2) Falls die Wochen-/Monatsansicht deine Farben nicht anzeigt, füge in deinem MRBS-Template (z.B. templates/common.php oder footer)
      folgendes <script> mit dem unten stehenden JS ein (achte auf sichere Einbindung):
*/\n";

?>


.banner {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -ms-flex-direction: row;
    flex-direction: row;
    -ms-flex-pack: start;
    justify-content: flex-start;
    background-color: #fff;
    color: #0069b4;
    border-color: #fff;
    border-width: 0px;
    border-style: solid;
}

.banner a:visited, .banner a:link, nav.logon input {
    color: #0069b4;
}

.banner nav a:hover, nav.logon input:hover {
    background-color: #0069b4;
    color: #ffffff;
}

nav a.selected, nav.view a:hover, nav.view a:focus, nav.arrow a:hover, nav.arrow a:focus {
    background: #0069b4;
    box-shadow: inset 1px 1px #ffffff;
    color: #ffffff;
    text-decoration: none;
}

/* Farbe für den Raum-Header und die Buchungszellen von Raum 1 */
.room_1_1, .room_1_1 .entry {
  background-color: #FFC0CB !important;
}
/* Farbe für den Raum-Header und die Buchungszellen von Raum 2 */
.room_2_1, .room_2_1 .entry {
  background-color: #ADD8E6 !important;
}
/* Farbe für den Raum-Header und die Buchungszellen von Raum 3 */
.room_3_1, .room_3_1 .entry {
  background-color: #FFC0CB !important;
}
/* Farbe für den Raum-Header und die Buchungszellen von Raum 4 */
.room_4_1, .room_4_1 .entry {
  background-color: #ADD8E6 !important;
}
/* Farbe für den Raum-Header und die Buchungszellen von Raum 5 */
.room_5_1, .room_5_1 .entry {
  background-color: #FFC0CB !important;
}

/* Button f�r Gast Kantakt */

.guest-contact-wrapper {
    text-align: center;
    margin-top: 2px;
}

.guest-contact-btn {
    font-size: 10px;
    padding: 2px 5px;
    border: 1px solid #333;
    border-radius: 3px;
    background: #f7f7f7;
    cursor: pointer;
}

.guest-contact-btn:hover {
    background: #eaeaea;
}


