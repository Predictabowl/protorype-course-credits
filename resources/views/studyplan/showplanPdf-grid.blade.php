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
            padding-bottom: 4px;
         }
        td { border: 1px solid; }
        tr    { page-break-inside:avoid; page-break-after:avoid }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

        .page-break {
            page-break-after: always;
        }

        .grid-container{
            display: grid;
            width: 100%;
            grid-template-columns: 8% 42% 35% 3% 8% 3%;
           /* grid-template-columns: auto auto auto auto auto auto;*/
            grid-gap: 4px;
            justify-content: start;
            text-overflow: auto;
        }

    </style>
</head>
<body>
            <div class="grid-container">

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
                 @foreach($studyPlan->getexamBlocks() as $examBlock)
                    <?php $startBlock = true; ?>
                       @foreach($examBlock->getExamOptions() as $option)
                            <div>
                               {{$option->getSsd()}}
                            </div>
                            <div>
                                {{ $option->getExamName() }}
                            </div>
                            <div style="font-size: 12px;">
                                @foreach($option->getTakenExams() as $taken)
                                    {{ $taken->getExamName() }}({{ $taken->getSsd() }}): 
                                    {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                    <br>
                                @endforeach
                            </div>
                            <div style="text-align: center;">
                                {{ $option->getCfu() }}
                            </div>
                                
                            @if($startBlock)
                                <?php $rows = $examBlock->getExamOptions()->count() ?>
                                <div style="text-align: center; border-left: 1px solid; grid-row: span {{$rows}}">
                                {{-- <div style="text-align: center; border-left: 1px solid;"> --}}
                                    @if($examBlock->getExamOptions()->count()  == 1)
                                        Obbligatorio.
                                    @else
                                        <?php $numOptions = $examBlock->getNumExams() ?>
                                        {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} a scelta.
                                    @endif
                                </div>
                                <div style="text-align: center; border-left: 1ox solid; font-weight: bold; grid-row: span {{ $rows }}">
                            {{--     <div style="text-align: center; border-left: 1ox solid; font-weight: bold;"> --}}
                                        {{ $option->getBlock()->getIntegrationValue()}}
                                </div>
                            @endif
                           <?php $startBlock = false; ?>
                        @endforeach
                @endforeach
            </div>
       
            
</body>
</html>