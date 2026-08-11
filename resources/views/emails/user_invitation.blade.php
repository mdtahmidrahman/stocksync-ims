<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invitation to StockSync IMS</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #0f172a;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            color: #94a3b8;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .body {
            padding: 40px;
        }
        .welcome-text {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            color: #64748b;
            font-weight: 500;
        }
        .info-value {
            color: #0f172a;
            font-weight: 600;
        }
        .btn-container {
            text-align: center;
            margin-top: 32px;
            margin-bottom: 16px;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.3);
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>StockSync IMS</h1>
            <p>Inventory & Supply Chain Management System</p>
        </div>
        <div class="body">
            <div class="welcome-text">Hello, {{ $user->name }}!</div>
            <p style="line-height: 1.6; color: #475569;">
                You have been invited to join <strong>{{ $companyName }}</strong> on StockSync IMS. Below are your account invitation details:
            </p>

            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Company:</span>
                    <span class="info-value">{{ $companyName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Assigned Role:</span>
                    <span class="info-value">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Assigned Location:</span>
                    <span class="info-value">{{ $user->location ?? 'All Locations' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email Address:</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                @if($temporaryPassword)
                <div class="info-row" style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                    <span class="info-label">Temporary Password:</span>
                    <span class="info-value" style="font-family: monospace; color: #d97706; font-size: 15px;">{{ $temporaryPassword }}</span>
                </div>
                @endif
            </div>

            <div class="btn-container">
                <a href="{{ $loginUrl }}" class="btn">Accept Invitation & Login</a>
            </div>

            <p style="font-size: 13px; color: #94a3b8; text-align: center; margin-top: 24px;">
                If you did not expect this invitation, you can safely ignore this email.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} StockSync IMS. All rights reserved.
        </div>
    </div>
</body>
</html>
