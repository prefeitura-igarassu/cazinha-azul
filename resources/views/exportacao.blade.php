<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $header['title'] ?? "Exportar" }}</title>

    <!-- <link rel="stylesheet" href="https://getbootstrap.com/2.3.2/assets/css/bootstrap.css"> -->
    <link rel="stylesheet" href="/css/primeflex-v3.3.0.min.css">

    <style>
        .font-4  { font-size: 4pt; }
        .font-5  { font-size: 5pt; }
        .font-6  { font-size: 6pt; }
        .font-7  { font-size: 7pt; }
        .font-8  { font-size: 8pt; }
        .font-9  { font-size: 9pt; }
        .font-10 { font-size: 10pt; }
        .font-11 { font-size: 11pt; }
        .font-12 { font-size: 12pt; }

        /* .text-center { text-align: center; }
        .text-left   { text-align: left; }
        .text-right  { text-align: right; } */

        .text-top    { vertical-align: top; }
        .text-bottom { vertical-align: bottom; }
        .text-middle { vertical-align: middle; }
        .text-sub    { vertical-align: sub; }
        .text-super  { vertical-align: super; }
        .text-baseline { vertical-align: baseline; }
        
        .page-break { page-break-after: always; }

        table          { border-spacing: 0; }
        .striped tr:nth-child( even ){ background-color: #f2f2f2; }
        .border        { border       : solid black 1px; }
        .border-top    { border-top   : solid black 1px; }
        .border-bottom { border-bottom: solid black 1px; }
        .border-left   { border-left  : solid black 1px; }
        .border-right  { border-right : solid black 1px; }
        .border-x      { border-left  : solid black 1px; border-right : solid black 1px; }
        .border-y      { border-top   : solid black 1px; border-bottom: solid black 1px; }

        .font-normal   { font-style: normal; }

        .bg-black { background: black; color: white; }
        .bg-white { background: white; color: black; }
    </style>

    <style type="text/css" media="print">
        @page 
        {
            background: white;
            size: auto;   /* auto is the current printer page size */
            margin: 0;    /* this affects the margin in the printer settings */
            margin-top: 0;
            margin-bottom: 0;
        }

        body 
        {
            background-color:#FFFFFF; 
            margin: 0px;  /* the margin on the content before printing */
            padding: 10px;

            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        @page :footer {
            display: none;
        }
  
        @page :header {
            display: none
        }

        .text-xs { font-size: 4pt; }
        .text-sm { font-size: 8pt; }
    </style>

    <x-style :styles="$header['styles']"></x-style>
</head>

<x-element :data="$body"></x-element>

</html>