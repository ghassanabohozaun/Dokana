<?php

namespace App\Helpers;

class TafqeetHelper
{
    private $units = ["", "واحد", "اثنان", "ثلاثة", "أربعة", "خمسة", "ستة", "سبعة", "ثمانية", "تسعة", "عشرة", "أحد عشر", "اثنا عشر", "ثلاثة عشر", "أربعة عشر", "خمسة عشر", "ستة عشر", "سبعة عشر", "ثمانية عشر", "تسعة عشر"];
    private $tens = ["", "", "عشرون", "ثلاثون", "أربعون", "خمسون", "ستون", "سبعون", "ثمانون", "تسعون"];
    private $hundreds = ["", "مائة", "مائتان", "ثلاثمائة", "أربعمائة", "خمسمائة", "ستمائة", "سبعمائة", "ثمانمائة", "تسعمائة"];
    private $thousands = ["", "ألف", "ألفان", "آلاف", "ألفاً"];
    private $millions = ["", "مليون", "مليونان", "ملايين", "مليوناً"];
    private $billions = ["", "مليار", "ملياران", "مليارات", "ملياراً"];

    public function convert($number)
    {
        if ($number == 0) {
            return "صفر";
        }

        $number = (string)intval($number);
        $len = strlen($number);

        if ($len > 12) {
            return "رقم كبير جداً";
        }

        // Divide number into groups of 3
        $chunks = array_reverse(str_split(str_pad($number, ceil($len / 3) * 3, '0', STR_PAD_LEFT), 3));
        
        $results = [];
        foreach ($chunks as $index => $chunk) {
            if ($chunk == '000') continue;
            
            $groupText = $this->convertGroup($chunk);
            $suffix = "";

            switch ($index) {
                case 1: // Thousands
                    $val = intval($chunk);
                    if ($val == 1) $groupText = $this->thousands[1];
                    elseif ($val == 2) $groupText = $this->thousands[2];
                    elseif ($val >= 3 && $val <= 10) $groupText .= " " . $this->thousands[3];
                    else $groupText .= " " . $this->thousands[4];
                    break;
                case 2: // Millions
                    $val = intval($chunk);
                    if ($val == 1) $groupText = $this->millions[1];
                    elseif ($val == 2) $groupText = $this->millions[2];
                    elseif ($val >= 3 && $val <= 10) $groupText .= " " . $this->millions[3];
                    else $groupText .= " " . $this->millions[4];
                    break;
                case 3: // Billions
                    $val = intval($chunk);
                    if ($val == 1) $groupText = $this->billions[1];
                    elseif ($val == 2) $groupText = $this->billions[2];
                    elseif ($val >= 3 && $val <= 10) $groupText .= " " . $this->billions[3];
                    else $groupText .= " " . $this->billions[4];
                    break;
            }
            
            array_unshift($results, $groupText);
        }

        return implode(' و ', $results);
    }

    private function convertGroup($number)
    {
        $n = intval($number);
        $res = [];

        $h = floor($n / 100);
        $remainder = $n % 100;

        if ($h > 0) {
            $res[] = $this->hundreds[$h];
        }

        if ($remainder > 0) {
            if ($remainder < 20) {
                $res[] = $this->units[$remainder];
            } else {
                $u = $remainder % 10;
                $t = floor($remainder / 10);
                
                $tensText = $this->tens[$t];
                if ($u > 0) {
                    $res[] = $this->units[$u] . " و " . $tensText;
                } else {
                    $res[] = $tensText;
                }
            }
        }

        return implode(' و ', $res);
    }
}
