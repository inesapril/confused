<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cash Flow</title>

<style>

body{
    font-family: DejaVu Sans;
    font-size:11px;
    color:#333;
}

/* HEADER */
.header{
    text-align:center;
}

.header h2{
    margin:0;
    font-size:20px;
}

.header p{
    margin-top:4px;
    font-size:12px;
}

/* GARIS */
.line{
    border-top:2px solid #000;
    margin-top:18px;
    margin-bottom:18px;
}

/* PERIODE */
.period{
    margin-bottom:15px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#eeeeee;
    border:1px solid #ddd;
    padding:6px;
    text-align:center;
}

td{
    border:1px solid #ddd;
    padding:6px;
}

tbody tr:nth-child(even){
    background:#f8f8f8;
}

/* SUMMARY */
.summary{
    margin-top:20px;
    text-align:right;
    font-weight:bold;
    line-height:1.8;
}

</style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <h2>CASH FLOW</h2>
        <p>Alur Confused</p>
    </div>

    <!-- GARIS -->
    <div class="line"></div>

    <!-- PERIODE -->
    <div class="period">
        <strong>Periode:</strong>
        {{ $startDate->format('d/m/Y') }}
        -
        {{ $endDate->format('d/m/Y') }}
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th width="50">No</th>
                <th width="100">Tanggal</th>
                <th width="130">Jenis</th>
                <th>Sumber</th>
                <th width="120">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach($rows as $i => $row)
            <tr>
                <td style="text-align:center;">
                    {{ $i+1 }}
                </td>

                <td style="text-align:center;">
                    {{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}
                </td>

                <td>
                    {{ $row['jenis'] }}
                </td>

                <td>
                    {{ $row['sumber'] }}
                </td>

                <td style="text-align:right;">
                    Rp {{ number_format($row['total'],0,',','.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SUMMARY -->
    <div class="summary">
        <div>
            Pemasukan:
            Rp {{ number_format($pemasukan,0,',','.') }}
        </div>

        <div>
            Pengeluaran:
            Rp {{ number_format($pengeluaran,0,',','.') }}
        </div>

        <div>
            Profit:
            Rp {{ number_format($profit,0,',','.') }}
        </div>
    </div>

</body>
</html>