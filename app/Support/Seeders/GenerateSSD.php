<?php

namespace App\Support\Seeders;

use App\Models\Ssd;
/**
 * Description of GenerateSSD
 *
 * @author piero
 */
class GenerateSSD {
    
    public static function create(string $prefix, int $max) {
        $prefix = strtoupper($prefix);
        for ($i=1; $i<=$max; $i++){
            Ssd::create([
               "code" => $prefix."/".sprintf("%02d",$i)
            ]);
        }
    }
    
    public static function arrayCreate($prefixes){
        foreach ($prefixes as $key => $value) {
            GenerateSSD::create($key, $value);
        }
    }
    
    public static function createAll(){
    
        GenerateSSD::arrayCreate(self::getGeneratorArray());
    }
    
    public static function getGeneratorArray(){
        return [
            "MAT" => 9,
            "INF" => 1,
            "FIS" => 8,
            "CHIM" => 12,
            "GEO" => 12,
            "BIO" => 19,
            "MED" => 50,
            "AGR" => 20,
            "VET" => 10,
            "ICAR" => 22,
            "ING-IND" => 35,
            "ING-INF" => 7,
            "L-ANT" => 10,
            "L-ART" => 8,
            "L-FIL-LET" => 15,
            "L-LIN" => 21,
            "L-OR" => 23,
            "M-STO" => 9,
            "M-DEA" => 1,
            "M-GGR" => 2,
            "M-FIL" => 8,
            "M-PED" => 4,
            "M-PSI" => 8,
            "M-EDF" => 2,
            "IUS" => 21,
            "SECS-P" => 13,
            "SECS-S" => 6,
            "SPS" => 14
        ];
    }
    
    public static function truncateAndCreateAll() {
        Ssd::truncate();
        GenerateSSD::createAll();
    }
    
//    public static function getSSDId(string $ssd): int{
//        $array = self::getGeneratorArray();
//        $i = 0;
//        foreach ($array as $key => $value) {
//            if (str_starts_with($ssd, $key)){
//                return $i + intval(substr($ssd, strlen($key)+1)); //it also counts the slash
//            }
//            $i += $value;
//        }
//    }
    
    public static function getSSDId(string $ssd): int{
        return Ssd::where("code", $ssd)->first()->id;
    }
}
