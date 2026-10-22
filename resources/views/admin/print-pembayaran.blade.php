<!DOCTYPE html>
<html>
<head>

<title>
    Bukti Pembayaran Alfa Net
</title>

<style>

body{
    font-family: Arial, sans-serif;
}

.container{
    width:600px;
    margin:auto;
}

.card{
    border:1px solid #ddd;
    padding:25px;
    border-radius:10px;
}

h2{
    text-align:center;
}

img{
    max-width:300px;
    margin-top:15px;
}

table{
    width:100%;
}

td{
    padding:8px;
}

</style>

</head>


<body onload="window.print()">


<div class="container">

<div class="card">


<h2>
    Bukti Pembayaran
</h2>


<hr>


<table>

<tr>
<td>
Kode Pembayaran
</td>

<td>
:
{{ $pembayaran->kode_pembayaran }}
</td>

</tr>


<tr>

<td>
Pelanggan
</td>

<td>
:
{{ $pembayaran->pelanggan?->user?->name }}
</td>

</tr>


<tr>

<td>
Jumlah
</td>

<td>
:
Rp {{ number_format($pembayaran->jumlah,0,',','.') }}
</td>

</tr>


<tr>

<td>
Metode
</td>

<td>
:
{{ ucfirst($pembayaran->metode) }}
</td>

</tr>


<tr>

<td>
Tanggal
</td>

<td>
:
{{ $pembayaran->created_at->format('d F Y') }}
</td>

</tr>


<tr>

<td>
Status
</td>

<td>
:
{{ $pembayaran->status }}
</td>

</tr>


</table>



<h3>
Bukti Transfer
</h3>


@if($pembayaran->bukti_pembayaran)

<img src="{{ asset('storage/'.$pembayaran->bukti_pembayaran) }}">


@else

<p>
Tidak ada bukti pembayaran
</p>

@endif



</div>

</div>


</body>
</html>