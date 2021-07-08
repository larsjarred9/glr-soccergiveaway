<?php
require '../vendor/autoload.php';
require_once "database.php";

// Setup Mail
require_once "mail.php";

$voornaam = $_POST['voornaam'];
$achternaam = $_POST['achternaam'];
$geboortedatum = $_POST['geboortedatum'];
$telefoon = $_POST['telefoon'];
$email = $_POST['email'];

// Check if available
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

if($inschrijvingen < $codeammount) {

    // Check if email is already in use
    $hasemail = $database->select("registration", "email", [
        "email" => $email
    ]);

    // Email is in use
    if(!empty($hasemail)) {
        header('location: ../inschrijven.php?msg=Je hebt al een kaartje met een unieke code ontvangen via je school email adres met verzilverings instructies.');
        return false;
    }

    // If email is not from GLR domain
    if(!preg_match('/^\w+@glr\.nl$/i', $email) > 0) {
        header('location: ../inschrijven.php?msg=Het ingevoerde email adres behoort niet tot het Grafisch Lyceum Rotterdam. Probeer het opnieuw met een geldig email adres.');
        return false;
    }

    // Else
    $database->insert("registration", [
        "firstname" => $voornaam,
        "lastname" => $achternaam,
        "dateofbirth" => $geboortedatum,
        "phone" => $telefoon,
        "email" => $email
    ]);

    $id = $database->id();

    // Haal codes op
    $codes = $database->select("codes", [
        "id",
        "code"
    ], [
        "user" => null
    ]);

    foreach($codes as $item) {
        $codeid = $item["id"];
        $code = $item["code"];
        break;
    }

    $stmt = $conn->prepare("UPDATE codes SET user = ? WHERE id = ?");
    $stmt->bind_param("ii", $id, $codeid);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("SELECT code FROM codes WHERE user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($code);
    $stmt->fetch();
    $stmt->close();

    $mail->addAddress($email);
    $mail->Subject = "Het GLR geeft weg - Tickets";
    $mail->Body = "Beste {$voornaam},<br><br>Je bent een van de gelukkige studenten die wij blij mogen maken met een EK kaartje in het Nederlandse vak.<br><br>Om een kaartje te bemachtigen verzilver je de code <strong>{$code}</strong> op de <a href='https://ticketaway.com/ticketactiegrafischlyceum'>website van Ticketaway</a>. Vervolgens krijg je binnen 2 uur een toegansbewijs gestuurd.<br><br>De wedstrijd vind plaats op zaterdag avond om 19:00 in het Feijenoord Stadion. Er word verlangt 1.5 uur van te voren aanwezig te zijn in ver band met grote drukte in het stadion. De wedstrijd word gespeeld tussen Nederland en Zweden. Mocht je niet kunnen op deze datum en tijd dan word er verlangt het kaartje niet te verzilveren.";
    $mail->IsHTML(true);
    $mail->send();


    echo "Je aanmelding is ontvangen, je hebt een email ontvangen met instructies voor de verzilvering.";
}
else {
    header("location: ../inschrijven.php");
}