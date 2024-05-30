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
                <th rowspan="2" style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">No</th>
                <th rowspan="2" style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Nama</th>
                @foreach ($allDates as $date)
                    <th colspan="2" style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">{{ $date }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($allDates as $date)
                    <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Check in</th>
                    <th style="border: 1px solid black; margin: 5px; border-collapse: collapse; text-align:center; font-weight:bold;">Check Out</th>
                @endforeach
            </tr>
        </thead>
        @php
            $index = 1;
        @endphp
        <tbody>
            @foreach ($data as $employeeId => $employeeData)
                <tr>
                    <td style="border: 1px solid black; margin: 5px; border-collapse: collapse;">{{ $index }}</td>
                    <td style="border: 1px solid black; margin: 5px; border-collapse: collapse;">{{ $employeeData['nama_pegawai'] }}</td>
                    @foreach ($allDates as $date)
                    @if($employeeData['attendance'][$date]['tipe_kerja'] !== 'BT')
                        <td style="border: 1px solid black; margin: 5px; border-collapse: collapse;">{{ $employeeData['attendance'][$date]['jam_masuk'] }}</td>
                        <td style="border: 1px solid black; margin: 5px; border-collapse: collapse;">{{ $employeeData['attendance'][$date]['jam_keluar'] }}</td>
                        @else
                        <td colspan="2" style="border: 1px solid black; margin: 5px; border-collapse: collapse; color:orange; text-align:center;">{{ $employeeData['attendance'][$date]['tipe_kerja'] }}</td>
                        {{-- <td style="border: 1px solid black; margin: 5px; border-collapse: collapse; color:orange">{{ $employeeData['attendance'][$date]['tipe_kerja'] }}</td> --}}
                        @endif
                    @endforeach
                </tr>
                @php
                $index++;
                @endphp
            @endforeach
        </tbody>
    </table>
    
</body>
</html>