<?php

namespace App\Imports;

use App\Pelanggan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PelangganImport implements ToCollection
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
                    'kode_pelanggan' => $row[1],
                    'nama_lengkap' => $row[2],
                    'nomor_hp_wa' => $row[3],
                    'alamat' => $row[4],
                ];
            }
        }
    }

    public function getData()
    {
        return $this->importedData;
    }
}
