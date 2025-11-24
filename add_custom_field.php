<?php
// KEIN namespace MRBS; oder use MRBS\Session; hier

declare(strict_types=1);

namespace MRBS;

require "defaultincludes.inc";

// Formularverarbeitung
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF-Token überprüfen
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Fehler: Ungültiges CSRF-Token.");
    }

    $targetTable = $_POST['target_table'] ?? '';
    $fieldName = $_POST['field_name'] ?? '';
    $dataType = $_POST['data_type'] ?? '';

    // Validierung der Eingaben
    $allowedTables = ['mrbs_room', 'mrbs_entry', 'mrbs_repeat'];
    $allowedDataTypes = ['VARCHAR(255)', 'TEXT', 'INT', 'DATE'];

    if (!in_array($targetTable, $allowedTables)) {
        $error = "Ungültige Zieltabelle ausgewählt.";
    } elseif (!in_array($dataType, $allowedDataTypes)) {
        $error = "Ungültiger Datentyp ausgewählt.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $fieldName)) {
        $error = "Ungültiger Feldname. Nur Buchstaben, Zahlen und Unterstriche sind erlaubt.";
    }

    if (empty($error)) {
        // Datenbankverbindung von MRBS verwenden
        $conn = \MRBS\DB::getInstance()->getConnection();

        $sql = "ALTER TABLE `$targetTable` ADD `$fieldName` $dataType";

        if ($conn->query($sql) === TRUE) {
            $message = "Spalte '{$fieldName}' erfolgreich zur Tabelle '{$targetTable}' hinzugefügt.";

            // Für Buchungen muss auch die repeat-Tabelle aktualisiert werden
            if ($targetTable === 'mrbs_entry') {
                $sql_repeat = "ALTER TABLE `mrbs_repeat` ADD `$fieldName` $dataType";
                if ($conn->query($sql_repeat) === TRUE) {
                    $message .= "<br>Spalte '{$fieldName}' auch zur Tabelle 'mrbs_repeat' hinzugefügt.";
                } else {
                    $error = "Fehler beim Hinzufügen zur Tabelle 'mrbs_repeat': " . $conn->error;
                }
            }
        } else {
            $error = "Fehler beim Hinzufügen der Spalte: " . $conn->error;
        }
    }
}

// HTML-Seite mit Formular
\page_header("Benutzerdefinierte Felder hinzufügen");
?>

<div class="div_container">
    <h2>Benutzerdefinierte Felder hinzufügen</h2>

    <?php if ($message): ?>
        <p class="mrbs-success"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="mrbs-error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label for="target_table">Ziel-Tabelle:</label>
        <select name="target_table" id="target_table">
            <option value="mrbs_room">Räume (mrbs_room)</option>
            <option value="mrbs_entry">Buchungen (mrbs_entry)</option>
        </select>
        <label for="field_name">Feldname (nur A-Z, 0-9, _):</label>
        <input type="text" id="field_name" name="field_name" pattern="^[a-zA-Z0-9_]+$" required>

        <label for="data_type">Datentyp:</label>
        <select name="data_type" id="data_type">
            <option value="VARCHAR(255)">Text (VARCHAR)</option>
            <option value="TEXT">Langer Text (TEXT)</option>
            <option value="INT">Ganze Zahl (INT)</option>
            <option value="DATE">Datum (DATE)</option>
        </select>

        <button type="submit">Feld hinzufügen</button>
    </form>
</div>

<?php
page_footer();
?>
