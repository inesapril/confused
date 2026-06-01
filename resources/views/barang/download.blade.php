<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
/* Mengatur kertas A4 dan mengunci margin agar muat pas 24 label */
@if($template == 'thermal')
@page {
    size: 58mm 30mm;
    margin: 0;
}
@else
@page {
    size: A4;
    margin: 10mm;
}
@endif

body {
    margin: 0;
    padding: 0;
    font-family: DejaVu Sans, sans-serif;
    background: #fff;
}

@if($template == 'a4_24')
.label-box{
    width:61mm;
    height:30mm;
    margin-right:1mm;
    margin-bottom:3mm;
    display:inline-block;
    vertical-align:top;
    box-sizing:border-box;
}
@elseif($template == 'a4_12')
.label-box{
    width:90mm;
    height:43mm;
    margin-right:2mm;
    margin-bottom:1mm;
    display:inline-block;
    vertical-align:top;
    box-sizing:border-box;
}
@elseif($template == 'single')
.label-box{
    width:180mm;
    height:45mm;
    margin-bottom:2mm;
    box-sizing:border-box;
}
@elseif($template == 'thermal')
.label-box{
    width:58mm;
    height:30mm;
    box-sizing:border-box;
}
@endif

.label-container {
    width: 100%;
    height: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    
    /* PERBAIKAN: Menambahkan padding 2.5mm di sekeliling sub-tabel 
       agar QR dan teks otomatis berjarak dari garis tepi label-box */
    padding: 2.8mm; 
    box-sizing: border-box;
}

.label-container td {
    padding: 0;
    margin: 0;
    vertical-align: middle; 
}

/* KUNCI QR KECIL: Kolom QR disesuaikan agar pas dengan gambar */
.td-qr {
    width: 20mm;
    text-align: center;
}

/* Gambar QR */
.qr-img {
    width: 18mm;
    height: 18mm;
    display: block;
    margin: 0 auto;
}

/* Kolom teks informasi barang */
.td-info {
    padding-left: 2.5mm; /* Jarak aman dari QR ke teks setelah diberi padding luar */
    padding-right: 1mm;
    text-align: left;
}

/* Kode Barang (14pt Tebal) */
.kode {
    font-size: 14pt;
    font-weight: bold;
    line-height: 1.1;
    margin-bottom: 1.5mm;
    color: #000;
}

/* Nama Barang (11pt) */
.nama {
    font-size: 11pt;
    line-height: 1.2;
    color: #000;
    word-wrap: break-word;
}

@if($template == 'thermal')
.label-box {
    width: 58mm;
    height: 27mm;
    box-sizing: border-box;
    overflow: hidden;
}
.label-container {
    height: auto;
    padding: 1.2mm 2.8mm;
}
body {
    font-size: 0;
    line-height: 0;
}
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
@endphp

{{-- Loop langsung tanpa bungkus TR/TD tabel luar agar tidak memicu page-break otomatis per baris --}}
@foreach($labels as $barang)
    <div class="label-box" @if($template == 'thermal' && !$loop->last) style="page-break-after: always;" @endif>
        <table class="label-container">
            <tr>
                <td class="td-qr">
                    <img class="qr-img" src="data:image/svg+xml;base64,{!!
                        base64_encode(
                            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                ->size(120)
                                ->generate($barang->kode_barang)
                        )
                    !!}">
                </td>
                <td class="td-info">
                    <div class="kode">{{ $barang->kode_barang }}</div>
                    <div class="nama">{{ $barang->nama_barang }}</div>
                </td>
            </tr>
        </table>
    </div>
@endforeach

</body>
</html>