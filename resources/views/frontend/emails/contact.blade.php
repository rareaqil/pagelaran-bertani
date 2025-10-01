<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pesan Baru dari Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9fafb;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #16a34a;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .content {
            padding: 20px;
        }

        .content p {
            margin: 8px 0;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .footer {
            background: #f1f5f9;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Pesan Baru dari Contact Us</div>
        <div class="content">
            <p><span class="label">📧 Email:</span> {{ $data['email'] }}</p>
            <p><span class="label">📱 WhatsApp:</span> {{ $data['whatsapp'] }}</p>
            <p><span class="label">📸 Instagram:</span> {{ $data['instagram'] }}</p>
            <p><span class="label">💬 Pesan:</span></p>
            <p>{{ $data['message'] }}</p>
        </div>
        <div class="footer">
            Email ini dikirim otomatis dari website Pagelaran Bertani.<br>
            Jangan balas email ini langsung.
        </div>
    </div>
</body>

</html>
