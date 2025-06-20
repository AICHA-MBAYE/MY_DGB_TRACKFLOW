<!DOCTYPE html>
<html>
<head>
    <title>Votre compte MY_DGB_TRACKFLOW a été validé !</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
        .header { background-color: #003366; color: white; padding: 15px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px 0; }
        .footer { text-align: center; font-size: 0.8em; color: #777; margin-top: 20px; }
        .button { display: inline-block; padding: 10px 20px; margin-top: 15px; background-color: #006400; color: white !important; text-decoration: none; border-radius: 5px; }
        .credentials { background-color: #eee; padding: 10px; border-left: 5px solid #003366; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Bienvenue sur MY_DGB_TRACKFLOW !</h2>
        </div>
        <div class="content">
            <p>Bonjour **{{ $agent->prenom }} {{ $agent->nom }}**,</p>
            <p>Nous avons le plaisir de vous informer que votre compte sur MY_DGB_TRACKFLOW a été validé.</p>
            <p>Vous pouvez maintenant vous connecter en utilisant les informations suivantes :</p>
            <div class="credentials">
                <p><strong>Email :</strong> {{ $agent->email }}</p>
                <p><strong>Mot de passe :</strong> <strong>{{ $password }}</strong></p>
                <p style="font-size: 0.9em; color: #555;">Nous vous recommandons de modifier ce mot de passe après votre première connexion.</p>
            </div>
            <p>Cliquez sur le bouton ci-dessous pour accéder à la page de connexion :</p>
            {{-- MODIFICATION : Ajout du paramètre 'email' à la route de connexion --}}
            <a href="{{ route('login', ['email' => $agent->email]) }}" class="button">Se connecter</a>
            <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
            <p>Cordialement,</p>
            <p>L'équipe MY_DGB_TRACKFLOW</p>
        </div>
        <div class="footer">
            <p>&copy; DGB-SENEGAL 2025 - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
