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
                        Prospetto riconoscimento esami<br>
                        Anno Accademico - {{$academicYear}}/{{$academicYear+1}}
                    </td>
                    <td  style="width:24%;">
                        <img src="{{public_path()."/images/logo_new.svg"}}" alt="Università degli studi di Torino" style="width: 120;">
                    </td>
                </tr>
            </table>


            <section style="font-size: 14px; margin-top: 1rem;">
                <x-legal-heading/>
            </section>

            <section style="margin-top: 0.6rem; margin-bottom: 2rem; font-size:16px; line-height: 1.7;">
                <div>
                    Corso: <span style="font-weight:  bold;">{{ $front->course->name }}</span>
                </div>
                <div>
                    Anno di Corso: <span style="font-weight:  bold;">{{ $courseYear }}°</span>
                </div>
                <div>
                    Coorte: <span style="font-weight:  bold;">{{ $academicYear - $courseYear +1 }}</span>
                </div>
                <div>
                    Nome e Cognome: <span style="font-weight: bold;">{{ $front->user->name }}</span>
                </div>
                <div>
                    Email: <span style="font-weight: bold;">{{ $front->user->email }}</span>
                </div>
                <div>
                    Luogo e Data di Nascita: <span style="border-bottom:1px solid; padding-left: 500px;">&nbsp;</span>
                </div>
            </section>

            <section style="border:1px  solid; text-align:center; width: 50%; margin:auto;">

                <?php $cfu = $studyPlan->getRecognizedCredits() ?>
                <div style="margin-top: 0.75rem;">
                    Totale CFU riconosciuti: <span style="font-weight: bold; color: green;">{{ $cfu }}</span>
                </div>
                <div style="margin-top: 0.5rem;">
                    CFU da sostenere: <span style="font-weight: bold; color: red;">{{ $front->course->cfu - $cfu }}</span>
                </div>
                <div style="margin-top: 0.25rem; margin-bottom: 0.75rem; font-size: 15px;">
                    di cui:
                    <?php $activities = $front->course->otherActivitiesCfu ?>
                    @if(isset($activities))
                        <div>- Altre attività: {{ $activities }} cfu</div>
                    @endif
                    <div>- Prova Finale: {{ $front->course->finalExamCfu}} cfu</div>

                </div>
            </section>

            <section style="position: relative; width: 30%; left: 5%;">
                <div class="signature-label"">Data</div>
                <div class="signature-underline" style="text-align: center; margin-top: 1rem; font-weight: bold;">
                    {{ \Carbon\Carbon::now()->format("d/m/Y") }}
                </div>
            </section>
            <section style="position:relative; width:40%; left: 50%;">
                <div class="signature-label"> Firma del docente</div>
                <div class="signature-underline"/>
                <div class="signature-label"> Firma dello studente per accettazione</div>
                <div class="signature-underline"/>
            </section>

        </div>

        {{-- Exams Table --}}
        <div style="text-overflow: auto; page-break-before:always;">
            <table class="table-prospetto" style="border: 2px solid;">
                <thead style="border: 2px solid black;">
                    <tr>
                        <th width="66%">Insegnamento e corrispondenti Esami Riconosciuti </th>
                        <th width="5%">Anno</th>
                        <th width="29%" colspan="2">CFU a Debito</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studyPlan->getexamBlocks() as $examBlock)
                        <tr>
                            <td>
                                <?php $startBlock = true; ?>
                                @foreach($examBlock->getExamOptions() as $option)
                                    <?php
                                        $bOptionCleared  = $option->getRecognizedCredits() == $examBlock->getCfu();
                                        $bOptionPartial = $option->getRecognizedCredits() > 0 && !$bOptionCleared;
                                    ?>
                                    <table class="table-prospetto no-break-table">
                                        <tr>
                                            <td class={{!$startBlock ? "border-t" : ""}}>
                                                @if($option->getSsd() != null)
                                                    {{$option->getSsd()}} |
                                                @endif
                                                {{ $option->getExamName() }}
                                                @if($bOptionCleared)
                                                    <span style="color: green;">
                                                        [Esame Riconosciuto]
                                                    </span>
                                                @elseif($bOptionPartial)
                                                    <span style="color: red;">
                                                        [Dovuta Integrazione]
                                                    </span>
                                                @endif
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
                            </td>
                            <td class="outer-td" style="text-align: center;">
                                @if($examBlock->getCourseYear() != null)
                                    {{ $examBlock->getCourseYear() }}°
                                @endif
                            </td>
                            <td class="outer-td" style="text-align: center;" width="13%">
                                    @if($examBlock->getExamOptions()->count()  == 1)
                                        {{$examBlock->getCfu()}} CFU Obbligatorio
                                    @else
                                        <?php $numOptions = $examBlock->getNumExams() ?>
                                        {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} da {{$examBlock->getCfu()}} CFU a scelta
                                    @endif
                            </td>
                            <?php $integration = $examBlock->getIntegrationValue() ?>
                            <td class="outer-td" style="text-align: center; font-weight: bold; font-size:15px;" width="5%">
                                <span style="color: {{ $integration > 0 ? 'red' : 'green'}};">
                                    {{ $integration }}
                                </span>
                            </td>
                        </tr>
                        <tr><td style="border: 1px solid;" colspan="4"></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        @if($studyPlan->getLeftoverExams()->count() > 0)
            <div style="border-top: 1px solid; border-bottom: 1px solid; margin-top: 10px; padding-top: 10px;
                 padding-bottom: 10px;">
                Lista esami con crediti inutilizzati:
                <div style="font-size: 14px;">
                    <ul class="list-disc pl-6">
                        @foreach($studyPlan->getLeftoverExams() as $exam)
                            <li>
                                [{{ $exam->getSsd()}}] {{ $exam->getExamName() }}: {{ $exam->getActualCfu() }}/{{ $exam->getCfu() }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
</body>
</html>
