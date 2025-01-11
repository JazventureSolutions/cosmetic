<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? "Circumcision Clinic " . Auth::user()->branch->name }}</title>
    <meta name="description" content="Circumcision Clinic {{ Auth::user()->branch->name }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <style>
        @page {
            size: A4;
        }

        html,
        body {
            height: 100%;
        }

        .pagebreak {
            page-break-after: always;
            height: 0;
            display: block;
            clear: both;
        }

        .footer, .footer-space {
            height: 50px;
        }

        .footer {
            width: 100%;
            position: fixed;
            bottom: 0;
            font-size: 18px;
        }

    </style>

</head>

<body>
    @if ($has_footer)
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
    @endif

    {!! $html !!}

    @if ($has_footer)
                </td>
            </tr>
        </tbody>
        <tfoot class="footer-space"> </tfoot>
    </table>
    @endif

    @if ($has_footer)
    <div class="footer">
        <div style="width: 100%; text-align: center;">Transcend Consulting Rooms, 98 Wentloog Road Cardiff. CF14 9DZ</div>
        <div style="width: 100%; text-align: center;">Tel: 02921320800, 07956050473</div>
    </div>
    @endif

    <script>
        setTimeout(() => {
            window.print();
        }, 1000);
    </script>

</body>

</html>
