<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New User Credentials for CMS</title>
</head>
<body style="background-color: #f6f6f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; font-size: 16px; padding-top: 80px; padding-bottom: 80px; line-height: 1.4; color: #3d4852;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <tr>
        <td style="padding: 20px;">
            <p style="margin: 0; font-weight: bold;">Hello!</p>
            <p style="margin: 15px 0; color: #3d4852;">You are receiving this email because an account has been created for you on our platform CMS.</p>

            <h3 style="font-size: 18px; margin: 20px 0; color: #3d4852;">Your Credentials</h3>
            <p style="margin: 15px 0; color: #3d4852;">Email: {{ $email }}</p>
            <p style="margin: 15px 0; color: #3d4852;">Password: {{ $password }}</p>
            <p style="margin: 15px 0; color: #3d4852;">Please make sure to change your password after your first login for security reasons.</p>

            <p style="margin: 15px 0; color: #3d4852;">This credential information will be valid for initial login.</p>

            <p style="margin: 15px 0; color: #3d4852;">If you did not request an account, no further action is required.</p>

            <p style="margin: 15px 0; color: #3d4852;">Regards,</p>
            <p style="margin: 15px 0; color: #3d4852;">CMS Team</p>

            <p style="margin: 15px 0; color: #3d4852;">If you're having trouble logging in, contact support or follow the URL to reset your password: <a href="{{ $reset_url }}" style="color: #3490dc; text-decoration: underline;">Reset Password</a></p>
        </td>
    </tr>
</table>
</body>
</html>
