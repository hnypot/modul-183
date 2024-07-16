<?php
session_start();
if (isset($_SESSION['token'])) {
    session_unset();
    $_SESSION = array();
    session_destroy();
    session_regenerate_id(true);
    header('Location: ../../index.php?msg=invalid_registration');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../assets/css/app.css">
    <title>Impressum</title>
</head>
<body>
<div class="impressum-container">
    <p>
        <b>Verantwortliche Instanz:</b><br/>Alperen Yilmaz<br/>Thiersteinerallee 71<br/>4053
        Basel<br/>Switzerland<br/><strong>E-Mail</strong>: alperen.yilmaz@stud.edubs.ch<br/><br/><strong>Haftungsausschluss</strong><br/>Der
        Autor übernimmt keine Gewähr für die Richtigkeit, Genauigkeit, Aktualität, Zuverlässigkeit und Vollständigkeit
        der Informationen.<br/>Haftungsansprüche gegen den Autor wegen Schäden materieller oder immaterieller Art, die
        aus dem Zugriff oder der Nutzung bzw. Nichtnutzung der veröffentlichten Informationen, durch Missbrauch der
        Verbindung oder durch technische Störungen entstanden sind, werden ausgeschlossen.<br/><br/>Alle Angebote sind
        freibleibend. Der Autor behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne
        gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig
        einzustellen.<br/><br/><strong>Haftungsausschluss für Inhalte und Links</strong><br/>Verweise und Links auf
        Webseiten Dritter liegen ausserhalb unseres Verantwortungsbereichs. Es wird jegliche Verantwortung für solche
        Webseiten abgelehnt. Der Zugriff und die Nutzung solcher Webseiten erfolgen auf eigene Gefahr des jeweiligen
        Nutzers.<br/><br/><strong>Urheberrechtserklärung</strong><br/>Die Urheber- und alle anderen Rechte an Inhalten,
        Bildern, Fotos oder anderen Dateien auf dieser Website, gehören ausschliesslich Alperen Yilmaz oder den speziell
        genannten Rechteinhabern. Für die Reproduktion jeglicher Elemente ist die schriftliche Zustimmung des
        Urheberrechtsträgers im Voraus einzuholen.
    </p>
</div>
<div class="navImpressum">
    <a href="../../index.php">
        <button class="btn">
            home
        </button>
    </a>
</div>
</body>
</html>