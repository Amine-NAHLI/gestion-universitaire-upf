<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre compte a été approuvé !</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-top: 8px solid #4f46e5;">
        <h2 style="color: #1e1b4b; font-size: 24px; font-weight: 800; margin-top: 0;">Félicitations {{ $name }} ! 🎉</h2>
        <p style="color: #4b5563; font-size: 16px; line-height: 1.6;">Votre demande d'inscription à l'espace numérique de l'<strong>Université Privée de Fès (UPF)</strong> a été officiellement approuvée par l'administration.</p>
        <div style="background-color: #f3f4f6; border-radius: 12px; padding: 20px; margin: 24px 0;">
            <p style="margin: 0; color: #1f2937; font-size: 14px;"><strong>Vos informations de connexion :</strong></p>
            <p style="margin: 8px 0 0 0; color: #4b5563; font-size: 14px;">Email : {{ $email }}</p>
        </div>
        <p style="color: #4b5563; font-size: 16px; line-height: 1.6;">Vous pouvez dès maintenant vous connecter à votre espace étudiant :</p>
        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ $loginUrl }}" style="background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: bold; font-size: 16px; display: inline-block;">Se connecter à l'espace UPF</a>
        </div>
        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 32px 0;">
        <p style="color: #9ca3af; font-size: 12px; text-align: center; margin: 0;">Cet email est automatique. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>
