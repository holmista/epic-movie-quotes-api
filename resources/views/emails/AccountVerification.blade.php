<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        a:hover {
            cursor: pointer;
        }

        a {
            text-decoration: none !important;
        }

        body {
            font-family: 'Inter';
            padding: 0;
            margin: 0;
        }

        .image {
            margin: 0 auto;
            display: block;
        }

        .button {
            margin: 0 auto;
            width: 128px;
        }
    </style>
</head>

<body>
    <div style="background: linear-gradient(#11101a 100%, #08080d 100%, #000000 0%); height: 100vh; color: white">
        <div style="padding-left: 194px">
            <div class="img_wrapper" style="padding-top: 80px">
                <img src="{{ asset('storage/icon.png') }}" class="image">
            </div>
            <br>
            <p style="margin-top: 73px">
                Hola ekaterine!
            </p>
            <br>
            <p>
                Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your
                account:
            </p>
            <br>
            <a href="google.com" class="button">
                <p class="verify_email"
                    style="padding-top: 1rem; padding-bottom: 1rem; font-size: 16px; border-radius: 4px;
                        color: white; text-align: center;  text-decoration: none; background-color: #E31221; width: 128px;">
                    Verify account
                </p>
            </a>
            <br>
            <p>If clicking doesn't work, you can try copying and pasting it to your browser:</p>
            <br>
            <p style="color: #DDCCAA">urll</p>
            <br>
            <p>If you have any problems, please contact us: support@moviequotes.ge</p>
            <br>
            <p>MovieQuotes Crew</p>
        </div>
    </div>
</body>
