<?php

namespace App\Helper;

class Date
{
    public static function dmY($date,$format = 'd.m.Y')
    {
        if ($date == null) {
            return "";
        }
        return date($format, strtotime($date));
    }

    public static function Ymd($date)
    {
        if ($date == null) {
            return "";
        }
        return date('Y-m-d', strtotime($date));
    }

    public static function firstDay($month, $year)
    {
        return sprintf("%d%02d%02d", $year , $month , 1 );
    }

    public static function lastDay($month, $year)
    {
        return sprintf("%d%02d%02d",$year, $month,  self::daysInMonth($month, $year), );
    }

    public static function daysInMonth($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    public static function generateDates($year, $month, $days)
    {
        $dateList = [];
        for ($day = 1; $day <= $days; $day++) {
            // Tarih formatını ayarlama (d.m.Y)
            $formattedDate = sprintf("%2d%02d%02d", $year, $month,  $day);
            $dateList[] = $formattedDate;
        }
        return $dateList;
    }

    // public static function generateDates($year, $month, $days)
    // {
    //     $dateList = [];
    //     for ($day = 1; $day <= $days; $day++) {
    //         // Tarih formatını ayarlama (d.m.Y)
    //         $formattedDate = sprintf("%02d.%02d.%d", $day, $month, $year);
    //         $dateList[] = $formattedDate;
    //     }
    //     return $dateList;
    // }

    public static function isWeekend($date)
    {
        $dateTime = new \DateTime($date);
        $dayOfWeek = $dateTime->format('N');
        return ($dayOfWeek == 7);
    }
    public static function gunAdi($gun)
    {
        $gun = date("D", strtotime($gun));
        $gunler = array(
            "Mon" => "Pzt",
            "Tue" => "Sal",
            "Wed" => "Çar",
            "Thu" => "Per",
            "Fri" => "Cum",
            "Sat" => "Cmt",
            "Sun" => "Paz"
        );
        return $gunler[$gun];
    }


    const MONTHS = [
        1 => "Ocak",
        2 => "Şubat",
        3 => "Mart",
        4 => "Nisan",
        5 => "Mayıs",
        6 => "Haziran",
        7 => "Temmuz",
        8 => "Ağustos",
        9 => "Eylül",
        10 => "Ekim",
        11 => "Kasım",
        12 => "Aralık"
    ];

    public static function monthName($month)
    {
        return self::MONTHS[$month];
    }

    public static function getMonthsSelect($name =  "months", $month = null,)
    {
        if ($month == null) {
            $month = date('m');
        }
        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="">Ay Seçiniz</option>';
        foreach (self::MONTHS as $key => $value) {
            $selected = $month == $key ? ' selected' : '';
            $select .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
        }
        $select .= '</select>';
        return $select;
    }


    public static function getYearsSelect($name =  "years", $year = null,)
    {
        if ($year == null) {
            $year = date('Y');
        }
        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="">Yıl Seçiniz</option>';
        for ($i = 2021; $i <= 2030; $i++) {
            $selected = $year == $i ? ' selected' : '';
            $select .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
        }
        $select .= '</select>';
        return $select;
    }
}
