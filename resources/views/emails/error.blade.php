<html>
    <head>
        <style>
            hr {
                height: 1px;
                border: 0 none;
                background-color: #aaaaaa;
                color: #aaaaaa;
            }

            dt {
                float: left;
                clear: left;
                width: 150px;
                text-align: right;
                font-weight: bold;
                color: #a90000;
            }

            dt:after {
                content: ":";
            }

            dd {
                margin: 0 0 0 160px;
                padding: 0 0 0.5em 0;
            }
        </style>
    </head>
    <body>
        <h3>Exceptions</h3>

        @foreach ($exceptions as $ex)
            <hr>
            <dl>
                <dt>Class</dt>
                <dd>{{ get_class($ex) }}</dd>

                <dt>Code / Message</dt>
                <dd>[{{ $ex->getCode() }}] {{ $ex->getMessage() }}</dd>

                <dt>File / Line</dt>
                <dd>{{ $ex->getFile() }}: {{ $ex->getLine() }}</dd>

                <dt>Stack trace</dt>
                <dd><pre>{{ $ex->getTraceAsString() }}</pre></dd>
            </dl>
        @endforeach
    </body>
</div>
