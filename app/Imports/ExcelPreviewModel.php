<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Importable;

class ExcelPreviewModel implements ToArray
{
    use Importable;

    public $data;

    public function array(array $array)
    {
        $this->data = $array;
    }
}

?>