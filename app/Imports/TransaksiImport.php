<?php

namespace App\Imports;

use App\Transaksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TransaksiImport implements ToCollection
{
    protected $importedData = [];
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            if ($key >= 8) {
                $this->importedData[] = [
                    'bulan_tahun' => $row[1],
                    'kode_pelanggan' => $row[2],
                    'pemakaian_sebelumnya' => $row[3],
                    'pemakaian_saat_ini' => $row[4],
                ];
            }
        }
    }

    public function getData()
    {
        return $this->importedData;
    }
}
