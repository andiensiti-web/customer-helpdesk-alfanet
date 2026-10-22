<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $laporan->id_laporan }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .actions {
            margin-bottom: 20px;
        }

        button {
            border: 0;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 8px;
        }

        .print {
            background: #2563eb;
            color: white;
        }

        .back {
            background: #e5e7eb;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
            vertical-align: top;
            font-size: 13px;
        }

        th {
            width: 25%;
            background: #f3f4f6;
        }

        .foto {
            max-width: 400px;
            max-height: 400px;
            border: 1px solid #aaa;
        }

        @media print {
            .actions {
                display: none;
            }

            body {
                margin: 15px;
            }
        }
    </style>
</head>

<body>

<div class="actions">
    <button type="button" class="print" onclick="window.print()">
        Cetak / Download PDF
    </button>

    <button
        type="button"
        class="back"
        onclick="window.location.href='/laporan-admin'">
        Kembali
    </button>
</div>

<h2>Laporan Customer Helpdesk Alfa Net</h2>

<table>
    <tr>
        <th>ID Laporan</th>
        <td>{{ $laporan->id_laporan ?? '-' }}</td>
    </tr>

    <tr>
        <th>Pelanggan</th>
        <td>
            {{ $laporan->pelanggan?->user?->name ?? '-' }}
            <br>
            ID: {{ $laporan->pelanggan?->id_pelanggan ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Teknisi</th>
        <td>
            {{ $laporan->teknisi?->nama ?? '-' }}
            <br>
            ID: {{ $laporan->teknisi?->id_teknisi ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ str_replace('_', ' ', $laporan->status ?? '-') }}</td>
    </tr>

    <tr>
        <th>Tanggal</th>
        <td>{{ $laporan->tanggal_laporan?->format('d-m-Y') ?? '-' }}</td>
    </tr>

    <tr>
        <th>Waktu</th>
        <td>{{ $laporan->created_at?->format('H:i') ?? '-' }} WIB</td>
    </tr>

    <tr>
        <th>Hasil Perbaikan</th>
        <td>{{ $laporan->hasil_perbaikan ?? '-' }}</td>
    </tr>

    <tr>
        <th>Tindakan Teknisi</th>
        <td>{{ $laporan->tindakan ?? '-' }}</td>
    </tr>

    <tr>
        <th>Catatan</th>
        <td>{{ $laporan->catatan ?? '-' }}</td>
    </tr>

    <tr>
        <th>Bukti Perbaikan</th>
        <td>
            @if($laporan->penugasan?->perbaikan?->bukti_foto)
                <img
                    src="{{ asset('storage/'.$laporan->penugasan->perbaikan->bukti_foto) }}"
                    class="foto"
                    alt="Bukti Perbaikan">
            @else
                Tidak ada foto bukti perbaikan.
            @endif
        </td>
    </tr>
</table>

</body>
</html>