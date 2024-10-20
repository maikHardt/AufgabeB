<?php
/*

Es gäbe wahrscheinlich etlich viele Ansätze vorallem dann wenn man eine Datenbank einbindet wie man diese Aufgabe erfüllen will,
ich habe mich für diese Version entschieden, bin mir Sicher das man hier noch weitere Verbesserungen vornehmen könnte, aber ich denke das sollte erstmal reichen.

Ich habe erstmal Maßnahmen unternommen keine Doppelten Einträge in der CSV Datei eintragen zu lassen, darunter Fehleingaben zu unterbinden und den Nutzer darauf aufmerksam zu machen
Eintragungen wenn alles Stimmt werden in eine CSV Datei verpackt und über der Overview.php Seite ausgelesen.
Passwörter werden Gehashed und so in die CSV-Datei eingefügt
Ich denke das wie es Angezeigt wird für den Anfang Übersichtlich ist, mit der Zeit könnte man eine Suchfunktion mit Suchkriterien einfügen oder 
wenn Unternehmen mehrere Systeme angebunden haben, diese als eine Gruppe anzeigen lassen könnte.

Aber muss auch sagen dass das alles eher zurzeit noch ein Gefühl ist wie ich mir bestimmte Dinge vorstelle, kann auch sein das alles Komplett anders sein wird mit der Zeit... :D

*/
session_start();

$filename = 'Daten/schnittstellen_daten.csv';
$netzwerke = [];

if (file_exists($filename) && ($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $netzwerke[] = $data;
    }
    fclose($handle);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/overview.css">
    <title>Überblick</title>    
</head>
<body>
    <h1>Netzwerk Übersicht</h1>
    <div id="div_zurueck">
        <form method="get" action="../">
            <button type="submit">Zurück zur Hauptseite</button>
        </form>
    </div> 
    <?php if (!empty($netzwerke)): ?>
        <?php foreach ($netzwerke as $netzwerk): ?>
            <div class="network">
                <h3>Hostname: <?php echo htmlspecialchars($netzwerk[0]); ?></h3>
                <p>Domain: <?php echo htmlspecialchars($netzwerk[1]); ?></p>
                <p>DHCP: <?php echo htmlspecialchars($netzwerk[2]); ?></p>
                <p>IP-Adresse: <?php echo htmlspecialchars($netzwerk[3]); ?></p>
                <p>MAC-Adresse: <?php echo htmlspecialchars($netzwerk[4]); ?></p>
                <p>Benutzername: <?php echo htmlspecialchars($netzwerk[5]); ?></p>
                <p>Beschreibung: <?php echo htmlspecialchars($netzwerk[7]); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Keine Netzwerke gefunden.</p>
    <?php endif; ?>
</body>
</html>
