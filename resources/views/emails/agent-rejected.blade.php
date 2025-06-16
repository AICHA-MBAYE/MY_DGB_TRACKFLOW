<!DOCTYPE html>
<html>
<head>
    <title>Demande d'inscription MY_DGB_TRACKFLOW - Information importante</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
        .header { background-color: #CC0000; color: white; padding: 15px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px 0; }
        .footer { text-align: center; font-size: 0.8em; color: #777; margin-top: 20px; }
        .button { display: inline-block; padding: 10px 20px; margin-top: 15px; background-color: #CC0000; color: white !important; text-decoration: none; border-radius: 5px; } /* Couleur du bouton adaptée au thème de rejet */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Informations concernant votre inscription à MY_DGB_TRACKFLOW</h2>
        </div>
        <div class="content">
            <p>Bonjour **{{ $agent->prenom }} {{ $agent->nom }}**,</p>
            <p>Nous vous remercions de l'intérêt que vous portez à MY_DGB_TRACKFLOW.</p>
            <p>Après examen de votre demande d'inscription par notre administrateur sectoriel, nous sommes au regret de vous informer qu'elle n'a pas pu être approuvée.</p>

            <p>La raison principale de ce refus est la suivante :</p>
            <div style="background-color: #ffe0e0; padding: 10px; border-left: 5px solid #CC0000; margin: 15px 0;">
                <p><strong>Une incohérence a été détectée lors de la vérification de vos données.</strong></p>
                <p style="font-size: 0.9em; color: #555;">Pour que votre inscription puisse être validée, nous vous invitons à vérifier et à corriger les informations que vous avez soumises.</p>
                @if($rejectionReason)
                    <p style="font-size: 0.9em; color: #555;">Détails du rejet : {{ $rejectionReason }}</p>
                @endif
            </div>

            <p>Veuillez cliquer sur le bouton ci-dessous pour modifier votre inscription et soumettre une nouvelle demande :</p>
            <a href="{{ $editUrl }}" class="button">Modifier mon inscription</a>

            <p>Si vous avez des questions ou si vous rencontrez des difficultés, n'hésitez pas à nous contacter en répondant à cet e-mail.</p>
            <p>Cordialement,</p>
            <p>L'équipe MY_DGB_TRACKFLOW</p>
        </div>
        <div class="footer">
            <p>&copy; DGB-SENEGAL 2025 - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>