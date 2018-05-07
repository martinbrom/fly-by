<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>FlyBy email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        * { font-family: Raleway, sans-serif; }

        .header h1 {
            margin-bottom: 0;
        }

        .header p {
            margin-top: 5px;
        }

        .header,
        .footer {
            padding: 20px 40px;
            color: white;
        }

        .header {
            background: #3993DD;
        }

        .content {
            padding: 20px 40px;
            color: #333;
        }

        .content a {
            color: #777;
            text-decoration: none;
        }

        .content a:hover,
        .content a:focus {
            text-decoration: underline;
            color: #000;
        }

        .footer {
            background: #132423;
        }

        .footer a {
            color:white;
            text-decoration: none;
        }

        .footer a:hover,
        .footer a:focus {
            color: #BBBBBB;
            text-decoration: none;
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td>
            <table align="center" cellpadding="0" cellspacing="0" width="600">
                <tr>
                    <td align="center" class="header">
                        <h1 class="text-center">Fly-By</h1>
                        <p class="text-center">Plánování vyhlídkových letů</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" class="content">
                        @yield('content')
                    </td>
                </tr>
                <tr>
                    <td align="center" class="footer">
                        © 2018 <a href="http://www.github.com/martinbrom/fly-by">Fly-By</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>