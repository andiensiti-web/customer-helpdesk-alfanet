<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>
    Laporan Alfa Net
</title>


<style>

body {
    font-family: Arial, sans-serif;
    margin: 30px;
    color: #222;
}


h2 {
    text-align: center;
    margin-bottom: 5px;
}


.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 25px;
    font-size: 13px;
}


.actions {
    margin-bottom: 20px;
}


.btn {
    border: 0;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
}


.btn-print {
    background: #2563eb;
    color: white;
}


.btn-back {
    background: #e5e7eb;
    color: #222;
}


.laporan {

    border: 1px solid #333;
    margin-bottom: 25px;
    page-break-inside: avoid;

}


.laporan-header {

    background: #f3f4f6;
    border-bottom: 1px solid #333;
    padding: 10px 12px;
    font-weight: bold;

}


.info {

    width: 100%;
    border-collapse: collapse;

}


.info td {

    border-bottom: 1px solid #ddd;
    padding: 8px 10px;
    font-size: 12px;

}


.info td:first-child {

    width: 25%;
    font-weight: bold;

}


.isi {

    padding: 12px;
    border-bottom: 1px solid #ddd;

}


.judul {

    font-weight: bold;
    margin-bottom: 6px;
    font-size: 13px;

}


.teks {

    font-size: 12px;
    line-height: 1.6;

}


.foto {

    max-width: 350px;
    max-height: 350px;
    border: 1px solid #aaa;
    border-radius: 6px;

}


.status {

    text-transform: capitalize;
    font-weight: bold;

}


@media print {

    body {
        margin: 15px;
    }


    .actions {
        display:none;
    }

}


</style>

</head>


<body>


<div class="actions">

<button class="btn btn-print"
        onclick="window.print()">

    Cetak / Download PDF

</button>


<button class="btn btn-back"
        onclick="window.location.href='/laporan-admin'">

    Kembali

</button>

</div>




<h2>
    Laporan Customer Helpdesk Alfa Net
</h2>


<p class="subtitle">

    Rekapitulasi laporan dan pembayaran pelanggan

</p>





@forelse($laporans as $laporan)



<div class="laporan">


<div class="laporan-header">


@if($laporan->jenis_data == 'pembayaran')

    Pembayaran 
    {{ $laporan->kode_pembayaran ?? '-' }}

@else

    Laporan 
    {{ $laporan->id_laporan ?? '-' }}

@endif


</div>





<table class="info">



@if($laporan->jenis_data == 'pembayaran')



<tr>

<td>
Kode Pembayaran
</td>

<td>

{{ $laporan->kode_pembayaran ?? '-' }}

</td>

</tr>



<tr>

<td>
Pelanggan
</td>

<td>

{{ $laporan->pelanggan?->user?->name ?? '-' }}

</td>

</tr>



<tr>

<td>
Jumlah
</td>

<td>

Rp {{ number_format($laporan->jumlah,0,',','.') }}

</td>

</tr>



<tr>

<td>
Metode
</td>

<td>

{{ ucfirst($laporan->metode ?? '-') }}

</td>

</tr>



<tr>

<td>
Status
</td>

<td class="status">

{{ str_replace('_',' ', $laporan->status ?? '-') }}

</td>

</tr>



<tr>

<td>
Tanggal
</td>

<td>

{{ $laporan->created_at?->format('d F Y') }}

</td>

</tr>



@else




<tr>

<td>
ID Laporan
</td>

<td>

{{ $laporan->id_laporan ?? '-' }}

</td>

</tr>



<tr>

<td>
Pelanggan
</td>

<td>

{{ $laporan->pelanggan?->user?->name ?? '-' }}

</td>

</tr>




<tr>

<td>
Teknisi
</td>

<td>

{{ $laporan->teknisi?->nama ?? '-' }}

</td>

</tr>




<tr>

<td>
Status
</td>

<td class="status">

{{ str_replace('_',' ', $laporan->status ?? '-') }}

</td>

</tr>




<tr>

<td>
Tanggal Laporan
</td>

<td>

{{ $laporan->tanggal_laporan?->format('d F Y') ?? '-' }}

</td>

</tr>



@endif



</table>







@if($laporan->jenis_data == 'pembayaran')



<div class="isi">

<div class="judul">
Bukti Pembayaran
</div>



@if($laporan->bukti_pembayaran)


<img src="{{ asset('storage/'.$laporan->bukti_pembayaran) }}"
     class="foto">


@else


<div class="teks">

Tidak ada bukti pembayaran.

</div>


@endif


</div>





@else




<div class="isi">

<div class="judul">

Hasil Perbaikan

</div>


<div class="teks">

{{ $laporan->hasil_perbaikan ?? '-' }}

</div>


</div>




<div class="isi">

<div class="judul">

Tindakan Teknisi

</div>


<div class="teks">

{{ $laporan->tindakan ?? '-' }}

</div>


</div>




<div class="isi">

<div class="judul">

Catatan

</div>


<div class="teks">

{{ $laporan->catatan ?? '-' }}

</div>


</div>




<div class="isi">

<div class="judul">

Bukti Perbaikan

</div>



@if($laporan->penugasan?->perbaikan?->bukti_foto)


<img src="{{ asset('storage/'.$laporan->penugasan->perbaikan->bukti_foto) }}"
     class="foto">


@else


<div class="teks">

Tidak ada foto bukti perbaikan.

</div>


@endif


</div>



@endif



</div>



@empty


<p style="text-align:center">

Belum ada data laporan.

</p>


@endforelse



</body>
</html>