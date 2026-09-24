<?php
session_start();
header('Content-Type: application/json');

$email_destinataire = "contact@smart-repare.fr";

// Génération d'un code à 6 chiffres
$code = rand(100000, 999999);
$_SESSION['reset_code'] = $code;
$_SESSION['code_expires'] = time() + (15 * 60); // Valide 15 minutes

$sujet = "Code de réinitialisation - Administration Smart Repare";
$message = "Bonjour,\n\nVoici votre code de vérification pour l'espace administration : " . $code . "\n\nCe code expire dans 15 minutes.";
$headers = "From: no-reply@smart-repare.fr\r\nReply-To: contact@smart-repare.fr\r\nContent-Type: text/plain; charset=UTF-8";

if (mail($email_destinataire, $sujet, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => "Impossible d'envoyer l'email."]);
}
?>