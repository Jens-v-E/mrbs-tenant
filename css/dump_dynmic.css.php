<?php
header("Content-type: text/css");
require_once('../config.inc.php');

// Manuelle Verbindung mit PDO, wie zuvor getestet
try {
    $db_dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_database;
    $pdo = new PDO($db_dsn, $db_login, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("/* Datenbankverbindung fehlgeschlagen: " . $e->getMessage() . " */\n");
}

// Räume aus der Datenbank abrufen
$query = "SELECT id, bg_color FROM mrbs_room";
$stmt = $pdo->query($query); // $stmt ist das Statement-Objekt

echo "/* CSS-Regeln für raumspezifische Farben */\n";

// Korrekte Schleife mit PDO::fetch()
while ($room = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($room['bg_color'])) {
        $room_id = $room['id'];
        $color = htmlspecialchars($room['bg_color']);

        echo "th[data-room=\"{$room_id}\"] { background-color: {$color} !important; }\n";
        echo ".room_{$room_id} { background-color: {$color} !important; }\n";

  // --- Wochenansicht ---
        echo "td.room_{$room_id}, td.cell_{$room_id}, .week_room_{$room_id} { background-color: {$color} !important; }\n";
        echo "table.week_view th.room_{$room_id} { background-color: {$color} !important; }\n";

        // --- Monatsansicht ---
        echo ".month_room_{$room_id}, td.month_room_{$room_id}, .monthview .room_{$room_id} { background-color: {$color} !important; }\n";
        echo ".month_view th.room_{$room_id} { background-color: {$color} !important; }\n";

    }
}
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

