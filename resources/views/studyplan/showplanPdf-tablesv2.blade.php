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
            border: 0px solid;
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
            padding-bottom: 3px;
         }
        td { page-break-inside:avoid;}
        tr    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

        .first-in-block{
            border-top: 1px solid;
        }
        .outer-td{
            border-left: 1px solid;
        }
        .page-break {
            page-break-after: always;
        }

    </style>
</head>
<body>
    {{-- This weird table formation was made as an Hack to avoid 2 nasty bugs in DOMPDF
        1. using the rowspan attribute will mangle tables at page break
        2. Using separate tables will ignore formatting on the first table after a page break

        So what I'm doing here is abusing the table row property "page-break-inside:avoid" to make whole tables
        inside <td> tags in a single <tr> so those won't be be split in page breaks. --}}
        <img src="{{public_path()."/images/logo_new.svg"}}" alt="Università degli studi di Torino" style="width: 120;">
             <div style="text-overflow: auto;">
                <table style="border: 2px solid;">
{{--                     <thead style="border: 2px solid black;">
                        <tr>
                            <th style="font-weight: bolder;">SSD</th>
                            <th>Nome Insegnamento</th>
                            <th>Esami Riconosciuti</th>
                            <th>CFU</th>
                            <th>Mod.</th>
                            <th>Integrazione</th>
                        </tr>
                    </thead> --}}
                    <tbody>
                        @foreach($studyPlan->getexamBlocks() as $examBlock)
                            
                            <tr>
                                <td width="10%" class="first-in-block">
                                    <table>
                                        <tbody>
                                            @foreach($examBlock->getExamOptions() as $option)
                                                <tr><td>{{$option->getSsd()}}</td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td width="72%" class="first-in-block outer-td">
                                    <table>
                                        <tbody>
                                            @foreach($examBlock->getExamOptions() as $option)
                                                <tr>
                                                    <td width="55%">
                                                        {{ $option->getExamName() }}
                                                    </td>
                                                    <td style="font-size: 12px;" width="45%">
                                                        @foreach($option->getTakenExams() as $taken)
                                                            {{ $taken->getExamName() }}({{ $taken->getSsd() }}): 
                                                            {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                                            <br>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                {{-- <td>
                                    <table>
                                        <tbody>
                                            @foreach($examBlock->getExamOptions() as $option)
                                                <tr><td>
                                                    @foreach($option->getTakenExams() as $taken)
                                                        {{ $taken->getExamName() }}({{ $taken->getSsd() }}): 
                                                        {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                                        <br>
                                                    @endforeach
                                                </td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td> --}}
                                <td width="3%" class="first-in-block outer-td">
                                    <table>
                                        <tbody>
                                            @foreach($examBlock->getExamOptions() as $option)
                                                <tr>
                                                    <td style="text-align: center;">
                                                        {{ $option->getCfu() }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="first-in-block outer-td" style="text-align: center;" width="12%">
                                        @if($examBlock->getExamOptions()->count()  == 1)
                                            Obbligatorio
                                        @else
                                            <?php $numOptions = $examBlock->getNumExams() ?>
                                            {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} a scelta
                                        @endif
                                </td>
                                <td class="first-in-block outer-td" style="text-align: center; font-weight: bold; font-size:15px" width="3%">
                                        {{ $option->getBlock()->getIntegrationValue()}}
                                </td>
                           </tr>
                           <tr><td style="border: 1px solid;" colspan="5"></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
       
            
</body>
</html>