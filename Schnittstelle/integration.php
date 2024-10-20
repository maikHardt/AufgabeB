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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Location: ../");
    exit();
}
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$inputData = isset($_SESSION['inputData']) ? $_SESSION['inputData'] : [];
session_unset();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/integration.css">
    <title>Schnittstelle implementieren</title>
</head>
<body>
<div class="container">
    <h2>Neues IT-System integrieren</h2>
    <form id="schnittstelleForm" action="verarbeitung.php" method="POST">
        <div class="formDiv">
            <div class="inner_container">
                <label for="hostname">Hostname:</label>
            </div>
            <div class="inner_container">
                <span class="error"><?= $errors['duplikat'] ?? '' ?></span>              
                <input type="text" class="form_input" id="hostname" name="hostname" value="<?= htmlspecialchars($inputData['hostname'] ?? '') ?>" required>
                <span class="error"><?= $errors['hostname'] ?? '' ?></span>
            </div>
        </div>

        <div class="formDiv">
            <div class="inner_container">
                <label for="domain">Domain:</label>
            </div>
            <div class="inner_container">
                <input type="text" class="form_input" id="domain" name="domain" value="<?= htmlspecialchars($inputData['domain'] ?? '') ?>" required>
                <span class="error"><?= $errors['domain'] ?? '' ?></span>
            </div>
        </div>
        
        <div class="formDiv">
            <div class="inner_container">
                <label for="dhcp" style="user-select: none;">DHCP verwenden:</label>                
            </div>
            <div class="inner_container">
                <input type="checkbox" id="dhcp" name="dhcp" <?= isset($inputData['dhcp']) && $inputData['dhcp'] === 'ja' ? 'checked' : '' ?> onchange="toggleIP()">
            </div>
        </div>

        <div class="formDiv" id="ipFeld">
            <div class="inner_container">
                <label for="ip">IP-Adresse:</label>
            </div>
            <div class="inner_container">
                <input type="text" class="form_input" id="ip" name="ip" value="<?= htmlspecialchars($inputData['ip'] ?? '') ?>" placeholder="z.B. 192.168.1.1">
                <span class="error"><?= $errors['ip'] ?? '' ?></span>
            </div>
        </div>

        <div class="formDiv">
            <div class="inner_container">
                <label for="mac">MAC-Adresse:</label>
            </div>
            <div class="inner_container">
                <input type="text" class="form_input" id="mac" name="mac" value="<?= htmlspecialchars($inputData['mac'] ?? '') ?>" required placeholder="z.B. 00:1A:2B:3C:4D:5E">
                <span class="error"><?= $errors['mac'] ?? '' ?></span>
            </div>
        </div>
        
        <div class="formDiv">
            <div class="inner_container">
                <label for="username">Benutzername:</label>
            </div>
            <div class="inner_container">
                <input type="text" class="form_input" id="username" name="username" value="<?= htmlspecialchars($inputData['username'] ?? '') ?>" required>
                <span class="error"><?= $errors['username'] ?? '' ?></span>
            </div>
        </div>
        
        <div class="formDiv">
            <div class="inner_container">
                <label for="password">Kennwort:</label>
            </div>
            <div class="inner_container" id="pwFeld">
                <input type="password" class="form_input" id="password" name="password" value="<?= htmlspecialchars($inputData['username'] ?? '') ?>" required>
                <span class="error"><?= $errors['password'] ?? '' ?></span>                
                <div style="display: flex; flex-direction: row; margin-top: 1ch;">                    
                    <input type="checkbox" id="pwVisible" name="pwVisible" onchange="togglePW()">            
                    <label style="user-select: none; font-size: 1.6ch; line-height: 20px;" for="pwVisible">Kennwort anzeigen</label>
                </div>
                             
            </div>
        </div>
        
        <div class="formDiv" id="beschreibungDiv">
            <div class="inner_container">
                <label for="description">Beschreibung:</label>
            </div>
            <div class="inner_container">
                <textarea class="form_input" id="beschreibung" name="beschreibung" rows="4" cols="19" <?= htmlspecialchars($inputData['beschreibung'] ?? '') ?>></textarea>
                <span class="error"><?= $errors['beschreibung'] ?? '' ?></span>
            </div>
        </div>
        <div class="btnDiv">
            <button type="submit" class="buttonForm">Speichern</button>    
        </div>    
    </form>
    <form method="post">
        <button type="submit">Zurück</button>
    </form>
</div>
<script src="../Assets/js/integration.js"></script>
</body>
</html>