@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Perbaiki Pembayaran
        </h1>

        <p class="text-gray-500">
            Upload ulang bukti pembayaran yang benar.
        </p>
    </div>


    <div class="bg-white rounded-xl shadow p-6">

        <div class="mb-5">

            <p class="text-gray-500">
                Kode Pembayaran
            </p>

            <h2 class="text-xl font-bold">
                {{ $pembayaran->kode_pembayaran }}
            </h2>

        </div>


        <form action="{{ url('/perbaiki-pembayaran/'.$pembayaran->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf


            <div class="mb-5">

                <label class="block text-sm mb-2">
                    Upload Bukti Pembayaran
                </label>

                <input type="file"
                       name="bukti_pembayaran"
                       class="border rounded-lg p-3 w-full">

            </div>


            <button
                class="bg-blue-600 text-white px-5 py-3 rounded-lg">

                Kirim Ulang Pembayaran

            </button>


        </form>


    </div>

</div>

@endsection