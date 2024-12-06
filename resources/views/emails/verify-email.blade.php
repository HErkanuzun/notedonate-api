<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Confirm Your Email - NotfDonate</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
    <!-- Header -->
    <tr>
      <td align="center" style="padding: 40px 0; background-color: #ffffff;">
        <table border="0" cellpadding="0" cellspacing="0" width="90%">
          <tr>
            <td align="center">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" style="margin-bottom: 16px;">
                <path d="M21 12c0 1.2-4 6-9 6s-9-4.8-9-6c0-1.2 4-6 9-6s9 4.8 9 6z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              <h1 style="color: #1a1a1a; font-size: 24px; margin: 0;">NotDonate</h1>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- Body -->
    <tr>
      <td align="center" style="padding: 0 0 40px;">
        <table border="0" cellpadding="0" cellspacing="0" width="90%">
          <tr>
            <td>
              <h2 style="color: #1a1a1a; font-size: 20px; margin: 0 0 20px;">Welcome to NotfDonate!</h2>
              <p style="color: #4a4a4a; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
                Thank you for joining our community. To get started, please confirm your email address by clicking the button below.
              </p>
              
              <!-- CTA Button -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                <tr>
                  <td align="center">
                    <a href="{{ $url }}" style="background-color: #2563eb; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px;">Confirm Email</a>
                  </td>
                </tr>
              </table>

              <p style="color: #4a4a4a; font-size: 14px; line-height: 1.6; margin: 24px 0;">
                If the button doesn't work, you can copy and paste this link into your browser:
                <br/>
                <a href="{{ $url }}" style="color: #2563eb; text-decoration: underline; word-break: break-all;">{{ $url }}</a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="background-color: #f8fafc; padding: 32px 0;">
        <table border="0" cellpadding="0" cellspacing="0" width="90%" style="margin: 0 auto;">
          <tr>
            <td align="center">
              <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 0 0 16px;">
                If you didn't create an account with NotfDonate, please ignore this email or contact us at 
                <a href="mailto:support@notfdonate.com" style="color: #2563eb; text-decoration: none;">support@notfdonate.com</a>
              </p>
              <p style="color: #64748b; font-size: 12px; margin: 0;">
                NotfDonate Inc. • 123 Donation Street • Charity City, CH 12345
              </p>
              <p style="color: #64748b; font-size: 12px; margin: 16px 0 0;">
                <a href="#" style="color: #2563eb; text-decoration: none; margin: 0 8px;">Privacy Policy</a> • 
                <a href="#" style="color: #2563eb; text-decoration: none; margin: 0 8px;">Terms of Service</a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
