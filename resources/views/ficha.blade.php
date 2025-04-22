<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $ficha->nome }}</title>
    <link rel="stylesheet" href="https://unpkg.com/primeflex@latest/primeflex.css">
    
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
</head>

<body>

    <div class="flex flex-column">
        <div class="flex text-4xl font-bold align-items-center">
            <img src="/fichas/{{$ficha->id}}.png" class="mr-2 w-1" />
            {{ $ficha->nome }}
        </div>

        <div class="flex flex-wrap gap-4">
            <div class="flex flex-column flex-wrap my-4">
                <x-property title="Unidade"        :value="$ficha->unidade->nome"                ></x-property>
                <x-property title="Cadastrado em"  :value="$ficha->cadastrado_em" type="datetime"></x-property>
                <x-property title="CPF Nº"         :value="$ficha->cpf"           type="cpf_cnpj"></x-property>
                <x-property title="Nascido em"     :value="$ficha->nascido_em"    type="date"    ></x-property>
                <x-property title="SUS Nº"         :value="$ficha->sus"                          ></x-property>
                <x-property title="NIS Nº"         :value="$ficha->nis"                          ></x-property>
                <x-property title="Posto de Saúde" :value="$ficha->posto_saude"                  ></x-property>
            </div>

            <div class="flex flex-column flex-wrap my-4">
                <x-property title="Mãe"            :value="$ficha->mae_nome"                     ></x-property>
                <x-property title="Pai"            :value="$ficha->pai_nome"                     ></x-property>
                <x-property title="Responsável"    :value="$ficha->responsavel"                  ></x-property>
                <x-property title="Escola"         :value="$ficha->escola"                       ></x-property>
                <x-property title="Endereço"       :value="$ficha->endereco"                     ></x-property>
                <x-property title="Telefone"       :value="$ficha->telefone"                     ></x-property>
                <x-property title="E-mail"         :value="$ficha->email"                        ></x-property>
            </div>
        </div>

        <x-property title="CID's"                 :value="$ficha->cids"              type="array"></x-property>
        <x-property col="" title="Observação"     :value="$ficha->observacao"                    ></x-property>
    </div>

</body>

</html>