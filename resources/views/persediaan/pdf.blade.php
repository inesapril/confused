<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Opname</title>
    <style>
        body{
            font-family: sans-serif;
            font-size:12px;
        }

        h2,h4{
            text-align:center;
            margin:0;
        }

        .tanggal{
            margin-top:10px;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th,td{
            border:1px solid #000;
            padding:6px;
        }

        th{
            background:#f2f2f2;
        }

        .center {
            text-align: center;
        }

    </style>
</head>
<body>

    <h2>DAFTAR BARANG</h2>
    <h4>Alur Confused</h4>
    <hr>

    <div class="tanggal">
        Tanggal: {{ now()->format('d-m-Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 30%;">Nama Barang</th>
                <th style="width: 16%;">Stok Sistem</th>
                <th style="width: 38%;">Stok Fisik</th>
                <th style="width: 10%;">Jumlah</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $i => $item)
            <tr>
                <td class="center">{{ $i+1 }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td class="center">{{ $item->stock }}</td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>