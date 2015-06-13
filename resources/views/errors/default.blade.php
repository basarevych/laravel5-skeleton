<html>
    <head>
        <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                color: #B0BEC5;
                display: table;
                font: 16pt sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-family: 'Lato';
                font-size: 250%;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">
                    {{ $status }} {{ $phrase }}
                </div>
                <div class="message">
                    @if (Lang::has("errors.http_{$status}"))
                        {{ trans("errors.http_{$status}") }}
                    @else
                        {{ trans("errors.http_default") }}
                    @endif
                </div>
             </div>
        </div>
    </body>
</html>
