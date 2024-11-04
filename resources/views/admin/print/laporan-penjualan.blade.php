<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Laporan Barang {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('F Y') }}</title>
</head>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 3cm 0.5cm 1cm 0.5cm;
    }

    header {
        position: fixed;
        top: -10px;
        left: 1cm;
        right: 1cm;
        height: 100px;
        padding: 0px;
        z-index: 700;
        vertical-align: bottom;
    }

    .table-end {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
    }

    .table-barang {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
        line-height: 0.6cm;
    }

    .page-break {
        page-break-before: always;
    }
</style>


<body>
    <header>
        <table width="100%">
            <tr align="center">
                <td>
                    <img src="{{ url('logo_hd_invert.png') }}" alt="logo" height="100" />
                </td>
                <td style="line-height: 0.2cm">
                    <h2>WARDROBE RACING MX</h2>
                    <h3><i>Laporan Barang</i></h3>
                    <h3><i>Bulan: </i> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('F Y') }}</h3>
                    <p>Dibuat Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                </td>
            </tr>
        </table>
    </header>
    <br>
    <table width="100%" border="1" class="table-barang">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Reference</th>
                <th>Nama Barang</th>
                <th style="padding-left: 2px; padding-right: 2px">Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $key = 0;
                $pemasukan = 0;
            @endphp
            @foreach ($data as $group)
                @php
                    $total = 0;
                @endphp
                @foreach ($group as  $d)
                @php
                    $total += $d->qty;
                    $pemasukan += $d->subtotal;
                @endphp
                <tr>
                    <td align="center">{{ ++$key }}</td>
                    <td align="center">{{ $d->created_at->format('d/m/Y') }}</td>
                    <td align="left" style="padding-left: 4px">{{ $d->invoice->kode_invoice }}</td>
                    <td align="left" style="padding-left: 2px">{{ $d->barang->nama }}</td>
                    <td align="center">{{$d->qty }}</td>
                    <td align="center">{{ number_format($d->harga) }}</td>
                    <td align="center">{{ number_format($d->subtotal) }}</td>
                </tr>
                @if ($loop->last)
                <tr>
                    <td align="right" style="padding-right: 4px" colspan="4">Jumlah</td>
                    <td style="padding-left: 4px" colspan="3">{{$total}}</td>
                </tr>
                @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
    <br>
    <table width="100%" style="font-size: 18px; font-weight: bold;">
        <tr>
            <td align="right" style="width: 70%"> Total Pemasukan Barang</td>
            <td align="right" style="padding-left: 10px">Rp. {{number_format($pemasukan)}}</td>
        </tr>
    </table>
</body>

</html>
