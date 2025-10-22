<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Registration Status</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f44336; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
        .reason-box { background: #fff; border-left: 4px solid #f44336; padding: 15px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Registration Update</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>Thank you for your interest in registering with our barangay portal.</p>
            <p>After careful review, we regret to inform you that your account registration could not be approved at this time.</p>
            
            <div class="reason-box">
                <strong>Reason for Decline:</strong>
                <p>{{ $reason ?? 'No specific reason provided.' }}</p>
            </div>

            <p>If you believe this was a mistake or would like to provide additional information, please contact the barangay office for further assistance.</p>
            
            <p>You may also submit a new registration with corrected information if applicable.</p>
            
            <p>
                <strong>Contact Information:</strong><br>
                Barangay Office: [Your Barangay Contact Number]<br>
                Office Hours: [Your Office Hours]<br>
                Address: [Your Barangay Address]
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>