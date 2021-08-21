<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <!-- Fonts -->
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}

        <!-- Styles -->
{{--         <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}

        <!-- Scripts (Apline) -->
  {{--       <script src="{{ asset('js/app.js') }}" defer></script> --}}
    
    <title></title>

    <style type="text/css">
        table {
            page-break-inside:auto;
            page-break-after: auto;
            font-size: 14px;
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
            padding-bottom: 3px;
         }
        th {border: 2px solid;}
        td { page-break-inside:avoid;}
        tr    {
            page-break-inside:avoid;
            page-break-after:auto
        }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

        ul {
            margin-top: 3px; 
            margin-bottom: 3px;
        }

        .no-break-table{
            page-break-inside: avoid;
            padding: 0px;
        }
        .outer-td{
            border-left: 1px solid;
        }

        .border-t{
            border-top: 1px solid;
        }

    </style>
</head>
<body>
    {{-- This weird table formation was made as an Hack to minimize 2 nasty bugs in DOMPDF
        1. using the rowspan attribute will mangle tables at page break
        2. Using separate tables will ignore formatting on the first table after a page break
        3. DOMPDF doesn't support flexbox so I'll have to paginate using tables.

        So what I'm doing here is abusing the property "page-break-inside:avoid" to make whole tables
        inside <td> tags in a single <tr> so those won't be be split in page breaks. --}}
        <div>
            <table>
                <tr>
                    <td style="width: 24%;">
                        <img src="{{public_path()."/images/logo_new.svg"}}" alt="Università degli studi di Torino" style="width: 120;">
                    </td>
                    <td style="text-align:center; font-size: 17px;">
                        <span style="font-weight:bold; font-size:18px;"> Dipartimento di Giurisprudenza</span><br>
                        Valutazione Carriera <br>
                        Prospetto riconoscimento esami
                    </td>
                    <td  style="width:24%;"/>
                </tr>
            </table>
            <section  style="font-size: 16px; border-collapse: unset;">
                <p> Corso: {{ $front->course->name }}
                </p>
                <p> Nome:
                    <span style="border-bottom:1px solid; padding-right:300px;">&nbsp;</span>
                </p>
                <p>
                    Cognome:
                    <span style="border-bottom:1px solid; padding-right:300px;">&nbsp;</span>
                </p>
                <p>
                    Email:
                    <span style="border-bottom:1px solid; padding-right: 300px;">&nbsp;</span>
                </p>
            </section>
        </div>

        <div style="text-overflow: auto;">
            <table style="border: 2px solid;">
                <thead style="border: 2px solid black;">
                    <tr>
                        <th width="70%">Insegnamento e corrispondenti Esami Riconosciuti </th>
                        <th width="30%" colspan="2">Integrazione</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studyPlan->getexamBlocks() as $examBlock)
                        <tr>
                            <td>
                            <?php $startBlock = true; ?>
                            @foreach($examBlock->getExamOptions() as $option)
                                <table class="no-break-table">
{{--                                         <tr>
                                        <td width="12%" class={{!$startBlock ? "border-t" : ""}}>
                                            {{$option->getSsd()}}
                                        </td>
                                        <td width="50%" class={{!$startBlock ? "border-t" : ""}}>
                                            {{ $option->getExamName() }}
                                        </td>
                                        <td style="font-size: 12px;" width="30%" class={{!$startBlock ? "border-t" : ""}}>
                                            @foreach($option->getTakenExams() as $taken)
                                                {{ $taken->getExamName() }}({{ $taken->getSsd() }}): 
                                                {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                                <br>
                                            @endforeach
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td class={{!$startBlock ? "border-t" : ""}}>
                                            @if($option->getSsd() != null) 
                                                [{{$option->getSsd()}}]
                                            @endif
                                            {{ $option->getExamName() }}
                                        </td>
                                    </tr>
                                    <tr>         
                                        <td style="font-size: 12px;">
                                            <ul>
                                                @foreach($option->getTakenExams() as $taken)
                                                <li>
                                                    [{{ $taken->getSsd() }}] {{ $taken->getExamName() }}: 
                                                    {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                                </li>
                                            
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                                <?php $startBlock = false; ?>
                            @endforeach
                            <td class="outer-td" style="text-align: center;" width="13%">
                                    @if($examBlock->getExamOptions()->count()  == 1)
                                        Obbligatorio
                                    @else
                                        <?php $numOptions = $examBlock->getNumExams() ?>
                                        {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} da {{$examBlock->getExamOptions()->first()->getCfu()}} CFU a scelta
                                    @endif
                            </td>
                            <td class="outer-td" style="text-align: center; font-weight: bold; font-size:15px" width="5%">
                                    {{ $option->getBlock()->getIntegrationValue()}}
                            </td>
                        </tr>
                        <tr><td style="border: 1px solid;" colspan="3"></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="font-size: 16px; page-break-before: always;">
            <?php $cfu = $studyPlan->getRecognizedCredits() ?>
            <p>Totale CFU riconosciuti: {{ $cfu }}</p>
            <p>CFU da sostenere: {{ $front->course->cfu - $cfu }}</p>
        </div>
        <div style="border-top: 1px solid; padding-top: 5px;">
            Lista esami con crediti inutilizzati:
            <ul class="list-disc pl-6">
                @foreach($studyPlan->getLeftoverExams() as $exam)
                    <li>
                        {{ $exam->getExamName() }}: {{ $exam->getActualCfu() }}/{{ $exam->getCfu()}}
                    </li>
                @endforeach
            </ul>
        </div>

        <div style="margin-top: 20px;">
            <p>
                Firma del docente:
                <span style="border-bottom:1px solid; padding-left:300px;">&nbsp;</span>
            </p>
             <p>
                Firma dello studente per accettazione:
                <span style="border-bottom:1px solid; padding-left:300px;">&nbsp;</span>
            </p>
        </div>

{{--         <script type="text/php">
             if (isset($pdf)) {
                $text = "pagina {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Serif");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) -20;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script> --}}

</body>
</html>