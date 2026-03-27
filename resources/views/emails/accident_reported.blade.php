<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Accident Report</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f6f8; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: linear-gradient(90deg, #0d9488, #059669); color: #fff; padding: 20px;">
            <h2 style="margin: 0;">🚨 New Accident / Incident Report</h2>
        </div>

        <!-- Body -->
        <div style="padding: 20px;">

            <p><strong>Report Type:</strong> {{ $report['report_type'] }}</p>
            <p><strong>Reporter Name:</strong> {{ $report['reporter_name'] }}</p>
            <p><strong>Email:</strong> {{ $report['reporter_email'] }}</p>
            <p><strong>Phone:</strong> {{ $report['reporter_phone'] ?? 'N/A' }}</p>

            <hr>

            <p><strong>Date:</strong> {{ $report['accident_date'] }}</p>
            <p><strong>Time:</strong> {{ $report['accident_time'] }}</p>
            <p><strong>Location:</strong> {{ $report['location'] }}</p>

            <hr>

            <p><strong>Aircraft Registration:</strong> {{ $report['aircraft_registration'] ?? 'N/A' }}</p>
            <p><strong>Aircraft Type:</strong> {{ $report['aircraft_type'] ?? 'N/A' }}</p>

            <hr>

            <p><strong>Description:</strong></p>
            <p>{{ $report['description'] }}</p>

            <p><strong>Injuries:</strong></p>
            <p>{{ $report['injuries'] ?? 'None reported' }}</p>

            <p><strong>Damage:</strong></p>
            <p>{{ $report['damage'] ?? 'None reported' }}</p>

        </div>

        <!-- Footer -->
        <div style="background: #f1f5f9; padding: 15px; text-align: center; font-size: 12px; color: #555;">
            AAIB System • Accident Reporting Notification
        </div>

    </div>

</body>
</html>