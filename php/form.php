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

// Check if less then 10
$registrations = count($database->select("registration", "id"));


// is more then 10
if($registrations >= 10) {
    header('location: ../inschrijven.php');
    return false;
}

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


// - Werkt nog niet -
$data = $database->update("codes", [
	"user" => $id
], [
	"codeid" => $codeid
]);


// Verstuur mail
// $mail->addAddress($email);
// $mail->Subject = "Het GLR geeft weg - Tickets";
// $mail->Body = "Beste {$voornaam},<br><br>Je bent een van de gelukkige studenten die wij blij mogen maken met een EK kaartje in het Nederlandse vak.<br><br>Om een kaartje te bemachtigen verzilver je de code <strong>{$code}</strong> op de <a href='https://ticketaway.com/ticketactiegrafischlyceum'>website van Ticketaway</a>. Vervolgens krijg je binnen 2 uur een toegansbewijs gestuurd.<br><br>De wedstrijd vind plaats op zaterdag avond om 19:00 in het Feijenoord Stadion. Er word verlangt 1.5 uur van te voren aanwezig te zijn in ver band met grote drukte in het stadion. De wedstrijd word gespeeld tussen Nederland en Zweden. Mocht je niet kunnen op deze datum en tijd dan word er verlangt het kaartje niet te verzilveren.";
// $mail->IsHTML(true);
// $mail->send();
