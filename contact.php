<?php
// Verificăm dacă formularul a fost trimis prin metoda POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Preluăm și curățăm datele introduse pentru a preveni codul malițios
    $nume = htmlspecialchars(strip_tags(trim($_POST["nume"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $facultate = htmlspecialchars(strip_tags(trim($_POST["facultate"])));
    $mesaj = htmlspecialchars(strip_tags(trim($_POST["mesaj"])));

    // Verificăm dacă email-ul este valid și câmpurile nu sunt goale
    if (empty($nume) || empty($mesaj) || empty($facultate) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Redirecționăm înapoi cu un mesaj de eroare în URL
        header("Location: contact.html?status=error");
        exit;
    }

    // Setăm adresa unde vreți să primiți mailurile
    $catre = "as@usamv.ro";
    $subiect = "Mesaj nou de pe site (Formular Contact) - de la $nume";

    // Construim corpul emailului
    $continut_email = "Ai primit un mesaj nou de pe site-ul AS-USAMV.\n\n";
    $continut_email .= "Nume: $nume\n";
    $continut_email .= "Email: $email\n";
    $continut_email .= "Facultate: $facultate\n\n";
    $continut_email .= "Mesaj:\n$mesaj\n";

    // Setăm headerele emailului (pentru ca reply-ul să se ducă direct la student)
    $headers = "From: no-reply@usamv.ro\r\n"; // Ideal o adresă a serverului
    $headers .= "Reply-To: $email\r\n";

    // Trimitem emailul folosind funcția nativă din PHP
    if (mail($catre, $subiect, $continut_email, $headers)) {
        // Dacă s-a trimis cu succes, redirecționăm către site cu mesaj de succes
        header("Location: contact.html?status=success");
    } else {
        // Dacă funcția de mail a serverului a dat greș
        header("Location: contact.html?status=error");
    }
} else {
    // Dacă cineva accesează fisierul PHP direct din browser, îl trimitem înapoi
    header("Location: contact.html");
}
?>
