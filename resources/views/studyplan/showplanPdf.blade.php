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
            page-break-inside:avoid;
            page-break-after: auto;
            font-size: 14px;
            border: 2px solid;
            border-collapse: collapse;
         /*   table-layout: fixed;*/
            width: 100%;
            padding-bottom: 3px;
         }
        td { border: 1px solid; }
        tr    { page-break-inside:avoid; page-break-after:avoid }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

        .page-break {
            page-break-after: always;
        }

    </style>
</head>
<body>
             <div style="text-overflow: auto;">
{{--                 <table>
                    <thead style="border: 2px solid black;">
                        <tr>
                            <th style="font-weight: bolder;">SSD</th>
                            <th>Nome Insegnamento</th>
                            <th>Esami Riconosciuti</th>
                            <th>CFU</th>
                            <th>Mod.</th>
                            <th>Integrazione</th>
                        </tr>
                    </thead>
                    <tbody> --}}
                <?php $rowNum = 1; ?>
                @foreach($studyPlan->getexamBlocks() as $examBlock)
                     {{-- WARNING: this is important!
                        This if statement should not be here, it's an hack inserted to avoid DOMPDF bug on page breaks
                        which there's no way to circumvent other than putting manual page breaks. 
                        The problem is that doing like this it will only work for this course! different courses will have 
                        a mangled up table.
                        If you want to use this html page for other courses this if statement must be removed at the cost of
                        having the 1st table after the page break with incorrect widths.
                        It's a bug since 2016 so I doubt it will be fixed.--}}
{{--                     @if($rowNum >= 30 )
                        <div style="page-break-after: always"></div>
                        <?php $rowNum = 1; ?>
                    @endif --}}
                    <table style="border: 1px">
                        <tr><td style="border:0px"></td></tr>
                    </table>
                    <?php $startBlock = true; ?>
                    <table>
{{--                         <colgroup>
                            <col width="70px">
                        </colgroup> --}}
 {{--                        <thead style="border: 2px solid black;">
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
                            @foreach($examBlock->getExamOptions() as $option)
                                <?php $rowNum++ ?>
                                <tr>
                                    <td width="7%" {{-- "70px" --}}>
                                        {{$option->getSsd()}}
                                    </td>
                                    <td width="44%" {{-- "450px" --}}>{{ $option->getExamName() }}</td>
                                    <td style="font-size: 12px;" width="35%"{{-- "350px" --}}>

                                        @foreach($option->getTakenExams() as $taken)
                                            {{ $taken->getExamName() }}({{ $taken->getSsd() }}): 
                                            {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                            <br>
                                        @endforeach
                                    </td>
                                    <td  width="3%"{{-- "25px" --}} style="text-align: center;">{{ $option->getCfu() }}</td>
                                    
                                    @if($startBlock)
                                        <?php $rows = $examBlock->getExamOptions()->count() ?>
                                        <td style="text-align: center; border-left: 1px solid;" 
                                            rowspan="{{ $rows }}" width="8%"{{-- "90px" --}}>
                                                @if($examBlock->getExamOptions()->count()  == 1)
                                                    Obbligatorio.
                                                @else
                                                    <?php $numOptions = $examBlock->getNumExams() ?>
                                                    {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} a scelta.
                                                @endif
                                        </td>
                                        <td style="text-align: center; border-left: 1ox solid; font-weight: bold;"
                                            rowspan="{{ $rows }}" width="3%"{{-- "25px" --}}>
                                                {{ $option->getBlock()->getIntegrationValue()}}
                                        </td>
                                    @endif
                                   <?php $startBlock = false; ?>
                               </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
       
            
</body>
</html>