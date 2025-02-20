<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DoctorsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            2 => new DoctorsSheetImport(), // 0 is the first sheet, change index as needed
        ];
    }
}
