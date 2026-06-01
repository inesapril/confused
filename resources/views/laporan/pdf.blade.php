<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan</title>

<style>
    body{
        font-family: DejaVu Sans;
        font-size:11px;
        color:#333;
    }

    /* HEADER */
    .header{
        text-align:center;
        margin-bottom:25px;
        padding-bottom:15px;
        border-bottom:2px solid #000;
    }

    .header h2{
        margin:0;
        font-size:20px;
        letter-spacing:1px;
    }

    .header p{
        margin-top:6px;
        font-size:12px;
        color:#666;
    }

    /* INFO */
    .info{
        width:100%;
        margin-bottom:20px;
    }

    .info td{
        padding:4px 0;
        vertical-align:top;
    }

    /* TABLE */
    table.data{
        width:100%;
        border-collapse:collapse;
        margin-top:10px;
    }

    table.data th{
        background:#f2f2f2;
        border:1px solid #ddd;
        padding:8px;
        text-align:center;
    }

    table.data td{
        border:1px solid #ddd;
        padding:7px;
    }

    table.data tbody tr:nth-child(even){
        background:#f9f9f9;
    }

    .text-center{
        text-align:center;
    }

    .text-right{
        text-align:right;
    }

    /* TOTAL SECTION */
    .total-wrap{
        margin-top:25px;
        text-align:right;
    }

    .line{
        width:300px;
        border-top:2px solid #000;
        margin-left:auto;
        margin-bottom:8px;
    }

    .total{
        font-size:14px;
        font-weight:bold;
    }

    /* FOOTER */
    .footer{
        position:fixed;
        bottom:15px;
        right:20px;
        font-size:10px;
        color:#777;
    }
</style>

</head>
<body>

    <div class="header">
        <h2>LAPORAN TRANSAKSI</h2>
        <p>Alur Confused</p>
    </div>

    <table class="info">
        <tr>
            <td width="160"><b>Laporan</b></td>
            <td>: {{ strtoupper($jenis) }}</td>
        </tr>

        @if($jenis == 'supplier')
        <tr>
            <td><b>Supplier</b></td>
            <td>:
                {{ optional($data->first()->barangMasuk->supplier ?? null)->nama_supplier ?? '-' }}
            </td>
        </tr>
        @endif

        @if($jenis == 'reseller')
        <tr>
            <td><b>Reseller</b></td>
            <td>:
                {{ optional($data->first()->reseller ?? null)->nama_reseller ?? '-' }}
            </td>
        </tr>
        @endif

        @if($jenis != 'stok_menipis')
        <tr>
            <td><b>Periode</b></td>
            <td>: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</td>
        </tr>

        <tr>
            <td><b>Total Data</b></td>
            <td>: {{ $data->count() }}</td>
        </tr>
        @endif {{-- <-- PERBAIKAN: Menambahkan @endif yang hilang di sini --}}
    </table>

    <table class="data">
        <thead>
            @if($jenis == 'stok_menipis')
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Stok Sekarang</th>
                    <th>Safety Stock</th>
                </tr>
            @else
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            @endif
        </thead>

        <tbody>
        @php
            $grandTotal = 0;
            $no = 1;
        @endphp

        {{-- 1. LAPORAN SUPPLIER (Barang Masuk) --}}
        @if($jenis == 'supplier')
            @foreach($data as $item)
                @php
                    $qty = $item->qty;
                    $harga = $item->harga;
                    $subtotal = $qty * $harga;
                    $grandTotal += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->barangMasuk->tanggal_masuk)->format('d/m/Y') }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td class="text-center">{{ $qty }}</td>
                    <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach

        {{-- 2. LAPORAN RESELLER (Barang Keluar) --}}
        @elseif($jenis == 'reseller')
            @foreach($data as $keluar)
                @foreach($keluar->details as $detail)
                    @php
                        $qty = $detail->qty;
                        $harga = $detail->harga;
                        $subtotal = $qty * $harga;
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->format('d/m/Y') }}</td>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td class="text-center">{{ $qty }}</td>
                        <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach

        {{-- 3. LAPORAN RETURN --}}
        @elseif($jenis == 'return')
            @foreach($data as $retur)
                @foreach($retur->details as $detail)
                    @php
                        $qty = $detail->qty;
                        $harga = $detail->barang->harga; /* Mengambil harga dari tabel barang sesuai index */
                        $subtotal = $qty * $harga;
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($retur->tanggal_return)->format('d/m/Y') }}</td>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td class="text-center">{{ $qty }}</td>
                        <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach

        {{-- 4. LAPORAN PESANAN --}}
        @elseif($jenis == 'pesanan')
            @foreach($data as $pesanan)
                @foreach($pesanan->details as $detail)
                    @php
                        $qty = $detail->qty;
                        $harga = $detail->harga;
                        $subtotal = $qty * $harga;
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($pesanan->tanggal_keluar)->format('d/m/Y') }}</td>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td class="text-center">{{ $qty }}</td>
                        <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach

        {{-- 5. LAPORAN STOK MENIPIS --}}
        @elseif($jenis == 'stok_menipis')
            @foreach($data as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td class="text-center">{{ $item->stock }}</td>
                <td class="text-center">{{ $item->safety_stock }}</td>
            </tr>
            @endforeach
        @endif
        </tbody>    
    </table>

    @if($jenis != 'stok_menipis')
    <div class="total-wrap">
        <div class="line"></div>
        <div class="total">
            TOTAL KESELURUHAN: Rp {{ number_format($grandTotal,0,',','.') }}
        </div>
    </div>
    @endif 

    <div class="footer">
        Dicetak {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>