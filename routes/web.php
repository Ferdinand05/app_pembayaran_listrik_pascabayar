<?php

use App\Models\Tagihan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/cek-tagihan', function () {
    return view('view-tagihan');
});

Route::get('/cetak/tagihan/{id}', function ($id) {
    $tagihan = Tagihan::where('id', $id)->first();
    $pdf   = Pdf::loadView('bukti_bayar', ['record' => $tagihan, 'tgl_cetak' => Date::now()]);
    return $pdf->stream();
})->name('cetak.tagihan');
