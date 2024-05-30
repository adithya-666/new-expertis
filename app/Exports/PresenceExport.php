<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PresenceExport implements FromView, ShouldAutoSize
{
    protected $data;
    protected $employees;
    protected $allDates;
    protected $information;


    public function __construct($data, $employees, $allDates, $information)
    {
        $this->data = $data;
        $this->employees = $employees;
        $this->allDates = $allDates;
        $this->information = $information;

        // dd($this->employees );
    }

    public function view(): View
    {
        return view('HRD.export-excel-presence', [
            'data' => $this->data,
            'employees' => $this->employees,
            'allDates' => $this->allDates,
            'information' => $this->information,
        ]);
    }
}
