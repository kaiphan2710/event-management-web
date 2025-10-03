<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Spot Available - {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .event-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #0b5ed7;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .urgent {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #0d6efd; margin: 0;">🎉 Great News!</h1>
            <p style="margin: 10px 0 0 0; color: #666;">A spot has become available for an event you're waitlisted for</p>
        </div>

        <p>Hi {{ $user->name }},</p>

        <p>We have exciting news! A spot has just opened up for the event you're waitlisted for:</p>

        <div class="event-details">
            <h3 style="margin-top: 0; color: #0d6efd;">{{ $event->title }}</h3>
            <p><strong>📅 Date:</strong> {{ $event->date->format('l, F d, Y') }}</p>
            <p><strong>⏰ Time:</strong> {{ $event->time }}</p>
            <p><strong>📍 Location:</strong> {{ $event->location }}</p>
            <p><strong>👤 Organiser:</strong> {{ $event->organiser->name }}</p>
        </div>

        <div class="urgent">
            <h4 style="margin-top: 0; color: #856404;">⚡ Quick Action Required</h4>
            <p style="margin-bottom: 0;">
                Since you were first on the waitlist, you have <strong>24 hours</strong> to claim this spot. 
                After that, it will be offered to the next person in line.
            </p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $bookingUrl }}" class="cta-button">
                🎫 Book This Event Now
            </a>
        </div>

        <p>
            <strong>What happens next?</strong><br>
            • Click the button above to book your spot<br>
            • You'll receive a confirmation email<br>
            • Get ready to enjoy the event!<br>
        </p>

        <p>
            <strong>Can't attend?</strong><br>
            No problem! Just ignore this email and the spot will be offered to the next person on the waitlist.
        </p>

        <div class="footer">
            <p>
                This is an automated notification from the Event Management System.<br>
                If you have any questions, please contact the event organiser directly.
            </p>
            <p>
                <small>
                    You received this email because you joined the waitlist for this event.<br>
                    Event Management System - {{ date('Y') }}
                </small>
            </p>
        </div>
    </div>
</body>
</html>
