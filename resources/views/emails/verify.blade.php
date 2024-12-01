<!DOCTYPE html>
<html>
<head>
    <title>Email Doğrulama</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">{{ config('app.name') }}'e Hoş Geldiniz!</h2>
        
        <p style="color: #666; margin-bottom: 20px; text-align: center;">
            Lütfen email adresinizi doğrulamak için aşağıdaki butona tıklayın:
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
               style="background-color: #4CAF50; color: white; padding: 14px 28px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; font-weight: bold;">
                Email Adresimi Doğrula
            </a>
        </div>

        <p style="color: #666; text-align: center; margin-top: 20px; font-size: 0.9em;">
            Eğer bir hesap oluşturmadıysanız, bu emaili görmezden gelebilirsiniz.
        </p>
    </div>
</body>
</html>