<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tagihan</title>
</head>

<body>

    <table border="1">
        <thead>
            <tr>
                <td>Kode</td>
                <td>Nama</td>
                <td>Bulan</td>
                <td>Tahun</td>
                <td>Tarif</td>
                <td>Jumlah Meter</td>
                <td>Total Tagihan</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $record?->kode_tagihan }}</td>
                <td>{{ $record?->pelanggan?->nama }}</td>
                <td>{{ $record?->bulan }}</td>
                <td>{{ $record?->tahun }}</td>
                <td>{{ intval($record?->pelanggan?->tarif?->tarif_per_kwh) }}</td>
                <td>{{ $record?->jumlah_meter }}</td>
                <td>{{ intval($record?->total_tagihan) }}</td>
            </tr>
        </tbody>
    </table>
    <small>Dicetak : {{ $tgl_cetak }}</small>

</body>

</html>
