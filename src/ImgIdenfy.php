<?php

namespace QZxPath;

class ImgIdenfy {

    private static $charMap = [
        'a' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100000011111111111100011100111111111111111110001111111111111111000111111111111111100011111111111100000001111111111000010000111111111000011100011111111100011110001111111110001111000111111111000111000011111111110011100000111111111100011100011111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'b' => '110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001000000111111111000000000001111111100000111100011111110001111110000111111000111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111110001111110001111111000011110000111111100000000000111111110001000000111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'c' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110000001111111111100000000011111111100001111111111111110001111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111110001111111111111111000011111111111111110000000011111111111110000001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'd' => '111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111100000010001111111100000000000111111100001111000011111110001111110001111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100001111110001111111000111100000111111110000000000011111111100000110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'e' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110000011111111111100000000111111111100011110001111111110011111100011111110001111110001111111000000000000111111100000000000011111110001111111111111111000111111111111111110001111111111111111000111111111111111110000000001111111111110000000111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'f' => '111110000011111111111110000001111111111110000111111111111111000111111111111111100011111111111111110001111111111111100000000111111111110000100011111111111110111111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'g' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100000110001111111100000000000111111100001111000011111110001111110001111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100001111100001111111000111100000111111110000000000011111111100000110001111111111111111000111111111111111100011111110011111100011111111000000000011111111111000000111111111',
        'h' => '110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001100000111111111000100000001111111100000111000011111110000111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'i' => '111111111111111111111000111111111111111100011111111111111111111111111111111111111111111111111111111111111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'j' => '111111111111111111111000111111111111111100011111111111111111111111111111111111111111111111111111111111111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110101111111111111111000111111111111111100011111111111111110111111111111111100011111111111111110001111111111111111',
        'k' => '110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111110111111111000111110011111111100011110011111111110001110011111111111000110011111111111100010011111111111110000000111111111111000100001111111111100011000011111111110001110000111111111000111100001111111100011111000011111110001111110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'l' => '110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'm' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110001100001111000011000100000001000001101000111100001100110011111100001111011000111110001111101101011111000111110110001111100011111011000111110001111101100011111000111110110001111100011111011000111110001111101100011111000111110110001111111111111011111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'n' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110001111000111111111000100000001111111100000111000011111110001111110001111111000111111001111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'p' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110001000000111111111000000000001111111100000111100011111110001111110000111111000111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111110001111110001111111000011110000111111100000000000111111110001100000111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111',
        'q' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100000010001111111100000000000111111100001111000011111110001111110001111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100001111110001111111000111100000111111110000000000011111111100000110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111',
        'r' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110001100000111111111000100000001111111100000111000011111110100111110001111111000111111000111111100011111100111111110001111111111111111000111111111111111100011111110011111110001111111001111111000111111110111111100011111111111111110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        's' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100000011111111111100000000111111111100011111111111111110001111111111111111000011111111111111111100111111111111111111000001111111111111100000011111111111111100001111111111111111000111111111001111100011111111100001000011111111111000000111111111111111111111111111111111111111111111111111111111111111111111111111111111111001111111111111111100',
        't' => '111111111111111111111111111111111111111111111111111111111110001111111111111111000111111111111111100011111111111111100000001111111111110000000111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000011111111111111110000011111111111111100001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'u' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110001111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111110001111111000111111000111111100011111100011111110001111100001111111000011100000111111110000000100011111111100000110001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'v' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100111111111011111110001111111001111111000111111100111111110001111100111111111000111110011111111110001110011111111111000111001111111111100001000111111111111000100111111111111100000011111111111111000011111111111111100001111111111111111001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'w' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100011111100111111010001111100001111001100011110000111100110001111000011110011000111001000111001100001100100011000111000100010000100111100010011100010011110001001110001001111100000111000000111110000111110000111111000011111000011111100001111100001111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'x' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111110001111100111111111000111110011111111110001110011111111111000010011111111111110000001111111111111100001111111111111110000111111111111111000011111111111111000000111111111111100100001111111111100111000111111111100111110001111111110011111000111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        'y' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100111111100111111110001111110011111111000111110001111111100011111001111111111000111100111111111100011100111111111110000110011111111111100010001111111111110001001111111111111100000111111111111110000111111111111111000011111111111111110011111111111111111001111111111111111001111111111111111000111111111111111000111111111111111100011111111111111',
        'z' => '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111100000000000011111110000000000001111111111111110001111111111111110000111111111111111000111111111111111100111111111111110000111111111111110000111111111111110000111111111111110000111111111111111000111111111111111100000000000111111110000000000011111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '1' => '111111111111111111111111100011111111111110000001111111111111000000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111100011111111111110000000001111111111000000000111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '2' => '111111111111111111111110000001111111111100000000011111111110011111000111111111111111110001111111111111111000111111111111111100011111111111111110001111111111111110000111111111111111000111111111111111000111111111111111000111111111111111000111111111111111000111111111111111000111111111111111000111111111111111000111111111111111100000000000111111110000000000011111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '3' => '111111111111111111111100000011111111111100000000111111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111000111111111111111100111111111111000010111111111111100000011111111111111111100001111111111111111000111111111111111110001111111111111111000111111111111111100011111111001111100011111111100000000011111111111000000011111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '4' => '111111111111111111111111111100011111111111111100001111111111111100000111111111111110000011111111111110010001111111111110011000111111111110001100011111111111001110001111111111001111000111111111000111100011111111100000000000011111110000000000001111111111111100011111111111111110001111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '5' => '111111111111111111111000000000001111111100000000000111111110011111111111111111001111111111111111100111111111111111110011111111111111111000000111111111111100000000111111111111111100000111111111111111100011111111111111111000111111111111111100011111111111111110001111111111111111000111111111111111000011111111111111000011111111110000000011111111111000000111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '6' => '111111111111111111111111100000011111111111000000000111111111000011111111111111000111111111111111100011111111111111100011111111111111110001111111111111111000110000011111111100000000000111111110000011100001111111000011111000011111100011111110001111110001111111000111111100111111100011111110001111110001111111100011110001111111111000000001111111111110000011111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '7' => '111111111111111111111000000000000111111100000000000011111111111111110001111111111111110001111111111111111001111111111111111001111111111111111000111111111111111100111111111111111100111111111111111110011111111111111110011111111111111110001111111111111111001111111111111111000111111111111111100011111111111111110011111111111111111101111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '8' => '111111111111111111111111000000111111111110000000001111111110000111000011111111000111110001111111100011111000111111110000111100011111111100000100011111111110000000011111111111100000001111111111100010000011111111100011110000111111100011111100001111110001111111000111111000111111100011111100001111110001111111000011110001111111100000000001111111111100000011111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
        '9' => '111111111111111111111111000001111111111110000000011111111110000111000111111111000111110001111111000111111100111111100011111110001111110001111111000111111000111111100011111100001111100001111111000011100000111111110000000000011111111100000110001111111111111111001111111111111111000111111111111111100011111111111111000011111111110000000011111111111000000111111111111111111111111111111111111111111111111111111111111111111111111111111111111001111111111111111100',
    ];

    private static $height = 40;
    private static $width = 80;
    private static $rgbThres = 120;

    private static function binaryImage($im){
        $imgArr = [[]];
        for($x = 0;$x < self::$width;$x++) {
            for($y =0;$y < self::$height;$y++) {
                if($x === 0 || $y === 0 || $x === self::$width - 1 || $y === self::$height - 1){
                    $imgArr[$y][$x] = 1;
                    continue;
                }
                $rgb = imagecolorat($im, $x, $y);
                $rgb = imagecolorsforindex($im, $rgb);
                if($rgb['red'] < self::$rgbThres && $rgb['green'] < self::$rgbThres && $rgb['blue'] < self::$rgbThres) {
                    $imgArr[$y][$x] = 0;
                } else {
                    $imgArr[$y][$x] = 1;
                }
            }
        }
        return $imgArr;
    }

    private static function cutImg($img,$arrX,$arrY,$n){
        $imgArr = [];
        for($i = 0;$i < $n; ++$i){
            $unitImg = [[]];
            for ($j=$arrY[$i][0]; $j < $arrY[$i][1]; $j++) {
                for ($k=$arrX[$i][0]; $k < $arrX[$i][1]; $k++) {
                    $unitImg[$j-$arrY[$i][0]][$k-$arrX[$i][0]] = $img[$j][$k];
                }
            }
            array_push($imgArr, $unitImg);
        }
        return $imgArr;
    }

    private static function getString($img) {
        $s = "";
        foreach($img as $image) {
            foreach($image as $string) {
                $s .= $string;
            }
        }
        return $s;
    }

    private static function removeByLine($imgArr) {
        $xCount = count($imgArr[0]);
        $yCount = count($imgArr);
        for ($i=1; $i < $yCount-1 ; $i++) {
            for ($k=1; $k < $xCount-1; $k++) {
                if($imgArr[$i][$k] === 0){
                    $countOne = $imgArr[$i][$k-1] + $imgArr[$i][$k+1] + $imgArr[$i+1][$k] + $imgArr[$i-1][$k];
                    if($countOne > 2) $imgArr[$i][$k] = 1;
                }
            }
        }
        return $imgArr;
    }

    private static function comparedText($s1,$s2){
        $percent = 0;
        for ($i=0; $i < 456; $i++) {
            $s1[$i] === $s2[$i] ? $percent++ : null;
        }
        return $percent;
    }

    private static function matchCode($imgArr,$charMap){
        $record = "";
        foreach ($imgArr as $img) {
            $maxMatch = 0;
            $tempRecord = "";
            $s = ImgIdenfy::getString($img);
            foreach ($charMap as $key => $value) {
                $percent = self::comparedText($s , $value);
                if($percent > $maxMatch){
                    $maxMatch = $percent;
                    $tempRecord = $key;
                }
            }
            $record = $record.$tempRecord;
        }
        return $record;
    }

    public static function getVC($data) {
        $img = imagecreatefromstring($data);
        $imgArr = ImgIdenfy::binaryImage($img);
        $imgArr = ImgIdenfy::removeByLine($imgArr);
        $imgArrArr = ImgIdenfy::cutImg($imgArr, [[3, 22], [21, 40], [39, 58], [57, 76]],[[9, 33], [9, 33], [9, 33], [9, 33]],4);
        return ImgIdenfy::matchCode($imgArrArr, self::$charMap);
    }
}