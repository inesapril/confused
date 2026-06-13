<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
/* Mengatur kertas dan margin */
@if($template == 'thermal')
@page {
    size: 58mm 30mm;
    margin: 0;
}
@else
@page {
    size: A6;
    margin: 3mm;
}
@endif

html, body {
    margin: 0;
    padding: 0;
    font-family: DejaVu Sans, sans-serif;
    background: #fff;
}

/* ===== GRID TABLE ===== */
.grid-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.grid-table td {
    padding: 0;
    margin: 0;
    vertical-align: top;
    text-align: center;
}

/* ===== LABEL BOX ===== */
.label-box {
    display: inline-block;
    background: #fff;
    border: 1px dashed #cbd5e1;
    vertical-align: top;
    text-align: left;
}

@if($template == 'a6_24')
.grid-table td {
    height: 17mm;
    padding: 0.3mm;
}
.label-box {
    width: 31mm;
    height: 16mm;
}
@elseif($template == 'a6_12')
.grid-table td {
    height: 23mm;
    padding: 0.4mm;
}
.label-box {
    width: 47mm;
    height: 22mm;
}
@elseif($template == 'single')
.grid-table td {
    height: 47mm;
    padding: 0.5mm;
}
.label-box {
    width: 95mm;
    height: 45mm;
}
@elseif($template == 'thermal')
.label-box {
    width: 58mm;
    height: 27mm;
    border-bottom: 1px dashed #ea580c;
}
@endif

/* ===== LABEL INNER TABLE ===== */
.label-container {
    width: 100%;
    height: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.label-container td {
    padding: 0;
    margin: 0;
    vertical-align: middle;
}

/* ===== TEMPLATE-SPECIFIC STYLES ===== */
@if($template == 'a6_24')
.label-container { padding: 1mm; }
.td-qr { width: 11mm; text-align: center; }
.qr-img { width: 10mm; height: 10mm; display: block; margin: 0 auto; }
.td-info { padding-left: 1mm; padding-right: 0.5mm; text-align: left; }
.kode { font-size: 6.5pt; font-weight: bold; line-height: 1.1; margin-bottom: 0.5mm; color: #000; }
.nama { font-size: 5.5pt; line-height: 1.2; color: #000; word-wrap: break-word; }

@elseif($template == 'a6_12')
.label-container { padding: 1.5mm; }
.td-qr { width: 16mm; text-align: center; }
.qr-img { width: 15mm; height: 15mm; display: block; margin: 0 auto; }
.td-info { padding-left: 1.5mm; padding-right: 1mm; text-align: left; }
.kode { font-size: 10pt; font-weight: bold; line-height: 1.1; margin-bottom: 1mm; color: #000; }
.nama { font-size: 8pt; line-height: 1.2; color: #000; word-wrap: break-word; }

@elseif($template == 'single')
.label-container { padding: 3mm; }
.td-qr { width: 35mm; text-align: center; }
.qr-img { width: 30mm; height: 30mm; display: block; margin: 0 auto; }
.td-info { padding-left: 4mm; padding-right: 2mm; text-align: left; }
.kode { font-size: 16pt; font-weight: bold; line-height: 1.1; margin-bottom: 2mm; color: #000; }
.nama { font-size: 12pt; line-height: 1.2; color: #000; word-wrap: break-word; }

@elseif($template == 'thermal')
.label-container { padding: 1.2mm 2.8mm; height: auto; }
.td-qr { width: 20mm; text-align: center; }
.qr-img { width: 18mm; height: 18mm; display: block; margin: 0 auto; }
.td-info { padding-left: 2.5mm; padding-right: 1mm; text-align: left; }
.kode { font-size: 14pt; font-weight: bold; line-height: 1.1; margin-bottom: 1.5mm; color: #000; }
.nama { font-size: 11pt; line-height: 1.2; color: #000; word-wrap: break-word; }
@endif
</style>
</head>
<body>

@php
    // Membuat list data label sesuai quantity
    $labels = [];
    foreach($selectedBarang as $item){
        for($i=0; $i < $item['qty']; $i++){
            $labels[] = $item['barang'];
        }
    }

    // Tentukan jumlah kolom per template
    if($template == 'a6_24') $cols = 3;
    elseif($template == 'a6_12') $cols = 2;
    elseif($template == 'single') $cols = 1;
    else $cols = 1; // thermal

    // Pisahkan labels ke dalam baris-baris (chunks)
    $rows = array_chunk($labels, $cols);
@endphp

@if($template == 'thermal')
    {{-- Thermal: 1 label per halaman --}}
    @foreach($labels as $barang)
        <div class="label-box">
            <table class="label-container">
                <tr>
                    <td class="td-qr">
                        <img class="qr-img" src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->generate($barang->kode_barang)) !!}">
                    </td>
                    <td class="td-info">
                        <div class="kode">{{ $barang->kode_barang }}</div>
                        <div class="nama">{{ $barang->nama_barang }}</div>
                    </td>
                </tr>
            </table>
        </div>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
@else
    {{-- A6 layouts: gunakan table grid untuk centering sempurna --}}
    <table class="grid-table">
        @foreach($rows as $row)
            <tr>
                @foreach($row as $barang)
                    <td>
                        <div class="label-box">
                            <table class="label-container">
                                <tr>
                                    <td class="td-qr">
                                        <img class="qr-img" src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->generate($barang->kode_barang)) !!}">
                                    </td>
                                    <td class="td-info">
                                        <div class="kode">{{ $barang->kode_barang }}</div>
                                        <div class="nama">{{ $barang->nama_barang }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                @endforeach
                {{-- Isi kolom kosong jika baris terakhir tidak penuh --}}
                @for($i = count($row); $i < $cols; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
    </table>
@endif

</body>
</html>