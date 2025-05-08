# Setting Up Mailtrap for Email Testing

This document provides instructions for setting up Mailtrap to test email functionality in the FandonWearShop application.

## What is Mailtrap?

Mailtrap is a fake SMTP server for development teams to test, view and share emails sent from the development and staging environments without sending them to real customers.

## Setup Instructions

1. **Create a Mailtrap Account**:
   - Go to [Mailtrap.io](https://mailtrap.io/) and sign up for a free account
   - After signing up, you'll be directed to your inbox

2. **Get Your SMTP Credentials**:
   - In your Mailtrap dashboard, select the "Inboxes" tab
   - Click on the "SMTP Settings" button for your inbox
   - Select "Laravel" from the integrations dropdown to see the Laravel-specific configuration

3. **Update Your .env File**:
   - Open your `.env` file in the project root
   - Update the following mail settings with your Mailtrap credentials:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=524b707a0ae656
   MAIL_PASSWORD=186c1e343b1195
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@fandonwearshop.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```
   - Replace `your_mailtrap_username` and `your_mailtrap_password` with the credentials from your Mailtrap inbox

4. **Test Email Functionality**:
   - After updating your .env file, you can test the email functionality
   - Any emails sent by the application (password resets, verification emails, etc.) will be captured in your Mailtrap inbox
   - No real emails will be sent to actual email addresses

## Features Available in Mailtrap

- **View Email Content**: See the HTML, Text, and Raw versions of your emails
- **Analyze Email Headers**: Check email headers for proper configuration
- **Spam Analysis**: Test if your emails might be flagged as spam
- **HTML/CSS Testing**: See how your emails render across different email clients
- **API Access**: Programmatically access your test emails

## Troubleshooting

If emails are not appearing in your Mailtrap inbox:

1. Verify your .env settings match the credentials provided by Mailtrap
2. Ensure the MAIL_MAILER is set to "smtp"
3. Check your application logs for any mail-related errors
4. Make sure your application is actually triggering email sending functionality

For more help, visit the [Mailtrap Documentation](https://mailtrap.io/blog/category/tutorials/).
