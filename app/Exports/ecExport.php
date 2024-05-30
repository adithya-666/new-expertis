<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ecExport implements FromView, ShouldAutoSize
{
    protected $data;
    protected $employees;
    protected $allDates;
    protected $information;


    public function __construct($data,  $information)
    {
        $this->data = $data;
        $this->information = $information;

        // dd($this->employees );
    }

    public function view(): View
    {
        return view('HRD.export-excel-expenses-claims', [
            'data' => $this->data,
            'information' => $this->information,
        ]);
    }
}