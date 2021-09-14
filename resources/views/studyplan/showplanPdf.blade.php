<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> Prospetto Pdf </title>

    <style type="text/css">
        .table-prospetto {
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

        .signature-label{
            margin-top: 3rem;
            text-align:center;
        }

        .signature-underline{
            margin-top: 2.5rem;
            width: 100%;
            border-bottom: 1px solid;
            display: inline-block;
        }


    </style>
</head>
<body>
    {{-- This weird table formation was made as an Hack to minimize few nasty bugs in DOMPDF
        1. using the rowspan attribute will mangle tables at page break
        2. Using separate tables will ignore formatting on the first table after a page break
        3. DOMPDF doesn't support flexbox or grids so I'll have to paginate using tables.

        So what I'm doing here is abusing the property "page-break-inside:avoid" to make whole tables
        inside <td> tags in a single <tr> so those won't be be split in page breaks. --}}
        <div>
            <table style="table-layout: fixed; width: 100%;">
                <tr>
                    <td style="width: 24%;">
                    </td>
                    <td style="text-align:center; font-size: 17px;">
                        <span style="font-weight:bold; font-size:19px;"> Dipartimento di Giurisprudenza</span><br>
                        Valutazione Carriera <br>
                        Prospetto riconoscimento esami
                    </td>
                    <td  style="width:24%;">
                        <img src="{{public_path()."/images/logo_new.svg"}}" alt="Università degli studi di Torino" style="width: 120;">
                    </td>
                </tr>
            </table>


            <section style="font-size: 14px; margin-top: 1rem;">
                <x-legal-heading/>
            </section>

            <section style="margin-top: 0.7rem; margin-bottom: 2rem; font-size:16px;">
                <p>
                    Corso: <span style="font-weight:  bold;">{{ $front->course->name }}</span>
                </p>
                <p>
                    Nome e Cognome: <span style="font-weight: bold;">{{ $front->user->name }}</span>
                </p>
                <p>
                    Email: <span style="font-weight: bold;">{{ $front->user->email }}</span>
                </p>
                <p>
                    Cellulare: <span style="border-bottom:1px solid; padding-left: 200px;">&nbsp;</span>
                </p>
            </section>

            <section style="border:1px  solid; text-align:center; width: 50%; margin:auto;">

                <?php $cfu = $studyPlan->getRecognizedCredits() ?>
                <p>
                    Totale CFU riconosciuti: <span style="font-weight: bold;">{{ $cfu }}</span>
                </p>
                <p>
                    CFU da sostenere: <span style="font-weight: bold;">{{ $front->course->cfu - $cfu }}</span>
                </p>
            </section>

            <section style="position: relative; width: 30%; left: 5%;">
                <div class="signature-label"">Data</div>
                <div class="signature-underline"/>
            </section>
            <section style="position:relative; width:40%; left: 50%;">
                <div class="signature-label"> Firma del docente</div>
                <div class="signature-underline"/>
                <div class="signature-label"> Firma dello studente per accettazione</div>
                <div class="signature-underline"/>
            </section>

        </div>

        <div style="text-overflow: auto; page-break-before:always;">
            <table class="table-prospetto" style="border: 2px solid;">
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
                                <table class="table-prospetto no-break-table">
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
                                        {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} da {{$examBlock->getCfu()}} CFU a scelta
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


        @if($studyPlan->getLeftoverExams()->count() > 0)
            <div style="border-top: 1px solid; border-bottom: 1px solid; margin-top: 10px; padding-top: 10px; padding-bottom: 10px;">
                Lista esami con crediti inutilizzati:
                <ul class="list-disc pl-6">
                    @foreach($studyPlan->getLeftoverExams() as $exam)
                        <li>
                            {{ $exam->getExamName() }}: {{ $exam->getActualCfu() }}/{{ $exam->getCfu() }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
</body>
</html>