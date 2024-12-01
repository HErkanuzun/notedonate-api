<!DOCTYPE html>
<html>
<head>
    <title>Şifre Sıfırlama</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">Şifre Sıfırlama İsteği</h2>
        
        <p style="color: #666; margin-bottom: 20px; text-align: center;">
            Şifrenizi sıfırlamak için aşağıdaki butona tıklayın. Bu link 60 dakika boyunca geçerli olacaktır.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}" 
               style="background-color: #4CAF50; color: white; padding: 14px 28px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; font-weight: bold;">
                Şifremi Sıfırla
            </a>
        </div>

        <p style="color: #666; text-align: center; margin-top: 20px; font-size: 0.9em;">
            Eğer bu isteği siz yapmadıysanız, bu emaili görmezden gelebilirsiniz.
            Link 60 dakika sonra geçerliliğini yitirecektir.
        </p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666;">
            <small>Bu otomatik olarak gönderilen bir emaildir. Lütfen cevaplamayınız.</small>
        </div>
    </div>
</body>
</html>
