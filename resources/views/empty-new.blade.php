<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Circumcision Clinic Cardiff' }}</title>
    <meta name="description" content="Circumcision Clinic Cardiff" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <style>
        @page {
            size: A4;
            margin: 50px;
        }

        .pagebreak {
            page-break-after: always;
            height: 0;
            display: block;
            clear: both;
        }

        .row {
            display: block;
        }
    </style>

</head>

<body>

    <div id="main" class="main">
        {!! $root_html !!}
    </div>

    <script>
        setTimeout(() => {
            window.print();
        }, 1000);
    </script>
</body>

</html>
