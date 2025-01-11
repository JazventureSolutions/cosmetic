<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Circumcision Clinic ' . Auth::user()->branch->name }}</title>
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
            height: 25px;
        }

        .footer {
            width: 100%;
            position: fixed;
            bottom: 0;
            font-size: 18px;
        }

        li {
            line-height: 30px;
            display: flex;
            align-items: center;
        }

ul.with-c-checkbox {
    list-style: none;
}

ul.with-c-checkbox > li > span {
    float: left;
}

.c-checkbox {
    /* margin-top: 8px; */
    height: 15px;
    width: 15px;
    border: 1px solid #11125c;
    background-color: transparent;
    margin-right: 10px;
}

.c-checkbox.checked {
    background-color: #11125c;
}

    </style>

</head>

@php
    $has_footer = false;
@endphp

<body>
    @if ($has_footer)
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
    @endif

    <div class="template-one-main-lower-third" style="max-width: 1000px; display: block; margin: 30px auto; font-size: 18px;">
        <div style="margin-bottom: 18px; line-height: 30px; text-align: center"><strong>ID Documents check</strong></div>
        <div style="margin-bottom: 18px; line-height: 30px;"><strong>Name:</strong> Master Test <br><strong>Date of birth:</strong> 2021-09-16 <br><strong>Address:</strong> </div>
        <div style="margin-bottom: 18px; line-height: 30px;"><strong>Baby</strong> – One of the following
            <ul class="with-c-checkbox">
                <li style="line-height: 30px;"><div class="c-checkbox"></div> Birth Certificate</li>
                <li style="line-height: 30px;"><div class="c-checkbox checked"></div> Red book</li>
                <li style="line-height: 30px;"><div class="c-checkbox checked"></div> Mother and Baby wrist band</li>
                <li style="line-height: 30px;"><div class="c-checkbox checked"></div> Passport</li>
                <li style="line-height: 30px;"><div class="c-checkbox"></div> Resident permit</li>
            </ul>
        </div>
        <div style="margin-bottom: 18px; line-height: 30px;"><strong>Parents</strong> – one of the following
            <ul class="with-c-checkbox">
                <li style="line-height: 30px;">Passport Father <div class="c-checkbox"></div> / Mother <div class="c-checkbox"></div></li>
                <li style="line-height: 30px;">Driving licence Father <div class="c-checkbox"></div> / Mother <div class="c-checkbox"></div></li>
                <li style="line-height: 30px;">Resident permit Father <div class="c-checkbox"></div> / Mother <div class="c-checkbox"></div></li>
            </ul>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div style="margin-bottom: 18px; width: 100%; float: left;">
            <div style="float: left;">Date: 2021-09-16</div>
            <div style="float: right;">Signature of the staff: <img data-sign-type="staff" class="sign-class"><br>Name of Staff: Owais</div>
        </div>
    </div>

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

</body>

</html>
