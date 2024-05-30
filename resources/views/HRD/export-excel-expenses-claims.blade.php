<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td>Periode Awal :</td>
                <td> {{ $information['startDate'] }}</td>
            </tr>
            <tr>
                <td>Periode Akhir :</td>
                <td>{{ $information['endDate'] }}</td>
            </tr>
            <tr>
                <td>Unit Bisnis :</td>
                <td>{{ $information['unit_bisnis'] }}</td>
            </tr>
            <tr>
                <td>Departemen :</td>
                <td> {{ $information['departement'] }}</td>
            </tr>
            <tr>
                <td>Nama Pegawai :</td>
                <td> {{ $information['employee_name'] }}</td>
            </tr>
        </thead>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">No</th>
                <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Nama Pegawai</th>
                <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Uang makan</th>
                <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Transportasi</th>
                <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Parkir dan Tol</th>
                <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Overtime</th>
                <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Grand Total</th>
            </tr>
        </thead>
        @php
            $index = 1;
            $total = 0;
            $total_uang_makan = 0;
            $total_transportasi = 0;
            $total_parkir_tol = 0;
            $total_lain_lain = 0;
            $grand_total = 0;
        @endphp
        <tbody>
            @foreach ($data as $employeeId => $employeeData)
            @php
            $overtime = $employeeData->total_lain_lain ? $employeeData->total_lain_lain :  0;
             $total = $employeeData->total_uang_makan + $employeeData->total_transportasi + $employeeData->total_parkir_tol +  $overtime;
            @endphp
                <tr>
                    <td style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">{{ $index }}</td>
                    <td style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">{{ $employeeData->nama_pegawai }}</td>
                    <td style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">{{ number_format($employeeData->total_uang_makan) }}</td>
                    <td style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">{{ number_format($employeeData->total_transportasi) }}</td>
                    <td style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">{{ number_format($employeeData->total_parkir_tol) }}</td>
                    <td style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">
                     {{ number_format($overtime) }}
                    </td>
                    <td style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">
                        {{ number_format($total) }}
                    </td>
                </tr>
                @php
                $index++;
                $total_uang_makan += $employeeData->total_uang_makan;
                $total_transportasi += $employeeData->total_transportasi;
                $total_parkir_tol += $employeeData->total_parkir_tol;
                $total_lain_lain += $overtime;
                $grand_total =  $total_uang_makan +  $overtime + $total_transportasi +  $total_parkir_tol + $total_lain_lain ;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td  colspan="2" style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">Grand Total</td>
                <td  style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">
                    {{ number_format($total_uang_makan) }}</td>
                <td  style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">
                {{ number_format($total_transportasi) }}</td>
                <td  style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">
                {{ number_format($total_parkir_tol) }}</td>
                <td  style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">
                {{ number_format($total_lain_lain) }}</td>
                <td  style="border: 1px solid black; margin: 5px;  text-align:center; border-collapse: collapse;">
                {{ number_format($grand_total) }}</td>
            </tr>
        </tfoot>
    </table>
    
</body>
</html>