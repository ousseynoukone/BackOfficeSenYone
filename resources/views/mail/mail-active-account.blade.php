<!DOCTYPE html>
<html>
<head>
    <title>Activation de votre compte</title>
    <style>
        /* Styles CSS pour l'e-mail */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #007BFF;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .activation-steps {
            margin-top: 20px;
        }
        .activation-steps ul {
            list-style-type: none;
            padding-left: 0;
        }
        .activation-steps li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Activation de votre compte</h1>
        <p>Cher(e) {{$name}},</p>
        <p>Nous sommes ravis de vous accueillir sur l'application mobile SenYone ! Pour finaliser la création de votre compte, veuillez suivre les étapes ci-dessous :</p>

        <div class="activation-steps">
            <ul>
                <li>Si vous êtes en cours de création de compte :</li>
            </ul>
            <ol>
                <li>Sur la page de validation, saisissez le code unique suivant : <strong>{{$code}}</strong></li>
                <li>Appuyez sur le bouton "Activer le compte".</li>
            </ol>

            <ul>
                <li>Autrement :</li>
            </ul>
            <ol>
                <li>Dans l'écran de connexion, appuyez sur "Activer mon compte" et saisissez le code unique suivant :  <strong>{{$code}}</strong></li>
                <li>Appuyez sur le bouton "Activer le compte".</li>
            </ol>
        </div>

        <p>Si vous avez des questions ou avez besoin d'aide, n'hésitez pas à nous contacter à l'adresse no.reply.781227.1@gmail.com .</p>
        <p>Merci de nous avoir rejoint !</p>
        <p>Cordialement,<br/>L'équipe de SenYone.</p>
    </div>
</body>
</html>
