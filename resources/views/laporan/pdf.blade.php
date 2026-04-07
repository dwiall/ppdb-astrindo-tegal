<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail PPDB</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        h4 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
        }

        td {
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>

    <h2>LAPORAN DETAIL PPDB</h2>
    <h4>SMK Astrindo Tegal</h4>

    <table>
        <thead>
            <tr>
                <th style="width: 8%">No</th>
                <th style="width: 12%">Tahun</th>
                <th style="width: 20%">Kategori</th>
                <th>Sub Kategori</th>
                <th style="width: 15%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row->tahun }}</td>
                    <td>{{ $row->kategori }}</td>
                    <td>{{ $row->sub_kategori }}</td>
                    <td class="text-center">{{ $row->jumlah }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data detail PPDB belum tersedia
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if($data->count() > 0)
        <tfoot>
            <tr>
                <th colspan="4" class="text-center">Total</th>
                <th class="text-center">
                    {{ $data->sum('jumlah') }}
                </th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i') }}
    </div>

</body>
</html>
