<?php

if (empty($_POST["id"])) {
    die("Un identifiant est requis");
}

if (empty($_POST["nom"])) {
    die("Un nom est requis");
}

if (empty($_POST["prenom"])) {
    die("Un prenom est requis");
}

if (empty($_POST["postal"])) {
    die("Une adresse postale est requise");
}

if ( ! filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
    die("Une adresse mail valide est requise");
}

if (strlen($_POST["pswd"]) < 8) {
    die("Le mot de passe doit contenir au moins 8 caractères");
}

if ( ! preg_match("/[a-z]/i", $_POST["pswd"])) {
    die("Le mot de passe doit contenir au moins une lettre");
}

if ( ! preg_match("/[0-9]/", $_POST["pswd"])) {
    die("Le mot de passe doit contenir au moins un chiffre");
}

if ($_POST["pswd"] !== $_POST["password_confirmation"]) {
    die("Les mots de passe ne correspondent pas");
}

$password_hash = password_hash($_POST["pswd"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO client (id, mail, postal, nom, prenom, date_naissance, date_inscription, password_hash)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                    $_POST["id"],
                    $_POST["mail"],
                    $_POST["postal"],
                    $_POST["nom"],
                    $_POST["prenom"],
                    $_POST["date_naissance"],
                    $_POST["date_inscription"],
                    $password_hash);
                  
if ($stmt->execute()) {

    header("Location: signup-success.html");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("Le mail est déjà utilisé");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}

?>