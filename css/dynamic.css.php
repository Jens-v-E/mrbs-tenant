<?php
header("Content-type: text/css");
require_once('../config.inc.php');

// Manuelle Verbindung mit PDO
try {
    $db_dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_database;
    $pdo = new PDO($db_dsn, $db_login, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("/* Datenbankverbindung fehlgeschlagen: " . $e->getMessage() . " */\n");
}

// Räume aus der Datenbank abrufen
$query = "SELECT id, bg_color FROM mrbs_room";
$stmt = $pdo->query($query);

echo "/* CSS-Regeln für raumspezifische Farben */\n";

// Hilfsfunktion: kontrastierende Textfarbe
function getContrastColor($hexcolor) {
    $hexcolor = str_replace('#','',$hexcolor);
    $r = hexdec(substr($hexcolor,0,2));
    $g = hexdec(substr($hexcolor,2,2));
    $b = hexdec(substr($hexcolor,4,2));
    $yiq = (($r*299)+($g*587)+($b*114))/1000;
    return ($yiq >= 128) ? '#000000' : '#ffffff';
}

// Schleife durch Räume
$rooms = [];
while ($room = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($room['bg_color'])) {
        $room_id = $room['id'];
        $color = htmlspecialchars($room['bg_color']);
        $textColor = getContrastColor($color);

        $rooms[] = [
            'id' => $room_id,
            'color' => $color,
            'textColor' => $textColor
        ];

        // --- Tagesansicht ---
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

