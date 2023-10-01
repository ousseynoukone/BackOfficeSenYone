<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        /* Styles CSS pour centrer tout le contenu */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #007bff;
        }
        p {
            color: #555;
        }
        .team {
            color: #888;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Réinitialisation de mot de passe</h1>
        <p>Vous avez demandé la réinitialisation de votre mot de passe. Voici votre code de réinitialisation :</p>
        <h2 style="font-size: 36px;">{{$code}}</h2>
        <p style="color:crimson ; font-weight:600;">Ce code est a usage unique.</p>

        <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet e-mail.</p>
        <p>Merci de faire confiance à notre service.</p>
        <h3 class="team">L'équipe de SenYone</h3>
    </div>
</body>
</html>
