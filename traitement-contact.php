<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Nettoyage et sanitisation des entrées
    $nom       = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
    $modele    = htmlspecialchars(trim($_POST['modele'] ?? ''));
    $message   = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Type d'appareil : valeur du menu déroulant de contact.html => libellé lisible
    $types_appareil = [
        'telephone'        => 'Téléphone',
        'tablette'         => 'Tablette',
        'montre-connectee' => 'Montre Connectée',
        'ordinateur'       => 'Ordinateur',
        'console'          => 'Console de Jeux',
        'trottinette'      => 'Trottinettes',
        'autre'            => 'Autre demande'
    ];
    $type     = $types_appareil[$_POST['type-appareil'] ?? ''] ?? '';
    $appareil = trim($type . ($type && $modele ? ' — ' : '') . $modele);

    // Validation des champs obligatoires
    if (empty($nom) || !$email || empty($message)) {
        header("Location: contact.html?status=error#formulaire");
        exit;
    }

    // Configuration du destinataire et du contenu
    $destinataire = "contact@smart-repare.fr";
    $sujet        = "Nouvelle demande de devis — " . ($appareil ?: "Général");

    $contenu  = "Vous avez reçu un nouveau message depuis le site internet :\n\n";
    $contenu .= "Nom : $nom\n";
    $contenu .= "Email : $email\n";
    $contenu .= "Téléphone : " . ($telephone ?: "Non renseigné") . "\n";
    $contenu .= "Appareil / Modèle : " . ($appareil ?: "Non spécifié") . "\n\n";
    $contenu .= "Message :\n$message\n";

    // En-têtes pour éviter le classement en spam
    $headers = [
        'From'         => 'no-reply@smart-repare.fr',
        'Reply-To'     => $email,
        'X-Mailer'     => 'PHP/' . phpversion(),
        'Content-Type' => 'text/plain; charset=UTF-8'
    ];

    // Envoi de l'email
    if (mail($destinataire, $sujet, $contenu, $headers)) {
        header("Location: contact.html?status=success#formulaire");
    } else {
        header("Location: contact.html?status=error#formulaire");
    }
    exit;

} else {
    header("Location: contact.html");
    exit;
}