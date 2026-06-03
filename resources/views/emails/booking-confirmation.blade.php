<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #0d6efd;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .body {
            padding: 30px;
        }
        .body h2 {
            color: #333;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .details-table td:first-child {
            font-weight: bold;
            color: #666;
            width: 40%;
        }
        .confirmation-code {
            background: #e8f4fd;
            border: 2px dashed #0d6efd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .confirmation-code span {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            letter-spacing: 3px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 13px;
        }
        .badge {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <h1>🎉 Booking Confirmed!</h1>
            <p>Your event booking has been successfully confirmed.</p>
        </div>

        {{-- Body --}}
        <div class="body">
            <h2>Hello, {{ $user->name }}!</h2>
            <p>Thank you for booking with <strong>EventBazaar</strong>.
               Here are your booking details:</p>

            {{-- Booking Details Table --}}
            <table class="details-table">
                <tr>
                    <td>Event</td>
                    <td><strong>{{ $event->title }}</strong></td>
                </tr>
                <tr>
                    <td>Date & Time</td>
                    <td>{{ $event->event_date->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <td>Seats Booked</td>
                    <td>{{ $booking->seats }}</td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td><strong>₹{{ number_format($booking->seats * $event->price, 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><span class="badge">Confirmed</span></td>
                </tr>
            </table>

            {{-- Confirmation Code --}}
            <div class="confirmation-code">
                <p style="margin:0 0 5px; color:#666; font-size:13px">
                    YOUR CONFIRMATION CODE
                </p>
                <span>{{ $booking->confirmation_code }}</span>
            </div>

            <p style="color:#666; font-size:13px">
                Please keep this confirmation code safe.
                You may need it at the event entrance.
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>This is an automated email from <strong>EventBazaar</strong>.</p>
            <p>Please do not reply to this email.</p>
        </div>

    </div>
</body>
</html>