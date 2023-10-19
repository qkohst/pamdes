<?php

namespace App\Helpers;

class OptionPeriodeHelper
{
    public static function getOptionBulan()
    {
        return [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }

    public static function getOptionTahun()
    {
        $endYear = date('Y');
        $startYear = 2023;

        $yearOptions = [];
        for ($year = $startYear; $year <= $endYear; $year++) {
            $yearOptions[$year] = $year;
        }
        return $yearOptions;
    }

    public static function getOptionBulanTahun()
    {
        $tahun = 2023;
        $batas = date('Y');
        $bulan_sekarang = date('m');
        $data = [];
        for ($i = $batas; $i >= $tahun; $i--) {
            for ($j = 12; $j >= 1; $j--) {
                if ($i == $batas) {
                    if ($j <= $bulan_sekarang) {
                        $data[$i . '-' . sprintf("%02d", $j)] = self::tglIndo($i . '-' . sprintf("%02d", $j));
                    }
                } else {
                    $data[$i . '-' . sprintf("%02d", $j)] = self::tglIndo($i . '-' . sprintf("%02d", $j));
                }
            }
        }
        return $data;
    }

    public static function tglIndo($tanggal)
    {
        $bulan = array(
            1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        );
        $pecahkan = explode('-', $tanggal);
        return $bulan[(int)$pecahkan[1]] . " " . $pecahkan[0];
    }
}
