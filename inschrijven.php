<?php
// Require Composer's autoloader.
require 'vendor/autoload.php';

require_once "php/database.php";
// Codes uitgedeeld
$stmt = $conn->prepare("SELECT count(user) FROM codes WHERE user IS NOT NULL");
$stmt->execute();
$stmt->bind_result($inschrijvingen);
$stmt->fetch();
$stmt->close();

// Totale codes beschikbaar
$stmt = $conn->prepare("SELECT count(id) FROM codes");
$stmt->execute();
$stmt->bind_result($codeammount);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Het GLR Geeft Weg!</title>
    <link rel="stylesheet" href="https://use.typekit.net/aga2utb.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="icon" type="image/png" href="images/glr_logo.png" />
</head>

<body class="overflow-hidden">
    <div class="container">
        <div class="row mt-5">
            <div class="col-12 col-md-8" style="z-index: 3;">
                <?php
                if (!empty($_GET['msg'])) {
                    echo "<div class='alert alert-light'>".$_GET['msg']."</div><a class='btn btn-glr' href='inschrijven.php'>Opnieuw Proberen</a>";
                } else {
                
                if ($inschrijvingen < $codeammount) { ?>
                <h2>Vul het formulier in</h2>
                <p>Na het invullen van het formulier ontvangt u indien er codes beschikbaar zijn een unieke code die je kunt verzilveren.</p>
                <form method="POST" action="php/form.php">
                    <label>Voornaam</label>
                    <input required name="voornaam" type="text" class="form-control">
                    <label class="mt-3">Achternaam</label>
                    <input required name="achternaam" type="text" class="form-control">
                    <label class="mt-3">Gebeoortedatum</label>
                    <input required name="geboortedatum" type="date" class="form-control">
                    <label class="mt-3">Telefoonnummer</label>
                    <input required name="telefoon" type="text" class="form-control">
                    <label class="mt-3">GLR Email</label>
                    <input name="email" pattern=".+@glr\.nl"  type="text" class="form-control">
                    <button required type="submit" class="btn btn-glr mt-3">Inschijven</button>
                </form>
                <?php } else { echo "<div class='alert alert-light'>Helaas, alle tickets zijn al uitgedeeld aan je medestudenten. Houd regelmatig deze website in de gaten voor andere weg geef acties of wijzigingen.</div>"; } }?>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-none d-md-block">
                    <img id="footballplayer" class="d-block mx-auto" src="images/player.png">
                </div>
                <div class="d-block d-md-none">
                    <img id="footballplayer" style="max-width: 30vh;" class="d-block mx-auto mt-0" src="images/player.png">
                </div>
            </div>
        </div>
    </div>
    <div class="d-none d-md-block">
        <img src="images/geluidgolf.png" style="max-height: 1500px; top: -450px; left: -800px; z-index:-1;" class="d-abs opacity">
    </div>
    <img src="images/glr_logo.png" style="max-height: 250px; transform: rotate(-20deg); top: 20px; left: 30px; z-index:-1;" class="d-abs opacity">
    <script src="js/bootstrap.min.js"></script>
</body>

</html>