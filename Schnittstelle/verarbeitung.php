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

$fehler = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Neu Gelernt die FILTER_ und preg_match...
    $hostname = filter_var($_POST['hostname'], FILTER_SANITIZE_STRING);
    $domain = filter_var($_POST['domain'], FILTER_SANITIZE_STRING);
    $dhcp = isset($_POST['dhcp']) ? 'ja' : 'nein';
    $ip = filter_var($_POST['ip'], FILTER_VALIDATE_IP);
    $mac = filter_var($_POST['mac'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $beschreibung = filter_var($_POST['beschreibung'], FILTER_SANITIZE_STRING);

    if (!preg_match('/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i', $mac)) {
        $fehler['mac'] = "Ungültige MAC-Adresse.";
    }

    if ($dhcp == 'nein' && !$ip) {
        $fehler['ip'] = "Ungültige IP-Adresse.";
    }


    if (empty(trim($hostname))) {
        $fehler['hostname'] = "Hostname ist erforderlich.";
    }
    if (empty(trim($domain))) {
        $fehler['domain'] = "Domain ist erforderlich.";
    }
    if (empty(trim($username))) {
        $fehler['username'] = "Benutzername ist erforderlich.";
    }
    if (empty(trim($_POST['password']))) {
        $fehler['password'] = "Kennwort ist erforderlich.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $fehler['password'] = "Das Kennwort muss mindestens 6 Zeichen lang sein.";
    }


    if (!empty($fehler)) {
        $_SESSION['errors'] = $fehler;
        $_SESSION['inputData'] = $_POST;
        header("Location: integration.php");
        exit();
    }

    $csvDaten = [
        $hostname,
        $domain,
        $dhcp,
        $ip ? $ip : 'DHCP',
        $mac,
        $username,
        $password,
        $beschreibung
    ];
    $existingData = [];

    // CSV-Datei lesen und existierende Daten laden
    if (($handle = fopen('Daten/schnittstellen_daten.csv', 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $existingData[] = $data;
        }
        fclose($handle);
    }
    $duplikatgefunden = false;
    foreach ($existingData as $eintrag) {
        // Hier müssten genauere if Abfragen getätigt werden um mehr Flexibilität zu ermöglichen
        if ($eintrag[0] === $hostname || $eintrag[1] === $domain || $eintrag[2] === 'Ja' || $eintrag[4] === $mac || $eintrag[5] === $username) {
            $duplikatgefunden = true;
            break;
        }
    
        // Nur überprüfen, wenn DHCP nicht aktiv ist
        if ($dhcp !== 'Ja' && $eintrag[3] === $ip) {
            $duplikatgefunden = true;
            break;
        }
    }

    if ($duplikatgefunden) {
        $fehler['duplikat'] = "Netzwerk existiert schon!";
        $_SESSION['errors'] = $fehler;
        $_SESSION['inputData'] = $_POST;
        header("Location: integration.php");
        exit();
    }
    $datei = fopen('Daten/schnittstellen_daten.csv', 'a');
    fputcsv($datei, $csvDaten);
    fclose($datei);

    header("Location: overview.php");
    exit();
}
