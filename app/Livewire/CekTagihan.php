<?php

namespace App\Livewire;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Tagihan;
use Livewire\Component;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use App\Service\MidtransService;
use Livewire\Attributes\Validate;

class CekTagihan extends Component
{
    use WithFileUploads;

    public function render()
    {
        return view('livewire.cek-tagihan');
    }



    public $nomor_kwh = '';
    public $pelanggan;
    public function cariPelanggan()
    {
        $pelanggan = Pelanggan::with('tagihan')->where('nomor_kwh', $this->nomor_kwh)->first();
        $this->pelanggan = $pelanggan;
        $this->dispatch('update-pelanggan');
    }




    public $selectedTagihan;
    public $bulan;
    public $tahun;
    public $jumlah_meter;
    public $metode_bayar;
    public $tarif_per_kwh;
    public $jumlah_bayar;
    public $showModal = false;


    public function openModalBayar($tagihanId)
    {
        $this->selectedTagihan = Tagihan::findOrFail($tagihanId);
        $this->bulan = Carbon::create()->month($this->selectedTagihan->bulan)->monthName;
        $this->tahun = $this->selectedTagihan->tahun;
        $this->jumlah_meter = $this->selectedTagihan->jumlah_meter;
        $this->tarif_per_kwh = $this->selectedTagihan->penggunaan->tarif_per_kwh;
        $this->jumlah_bayar = $this->selectedTagihan->total_tagihan + $this->selectedTagihan->biaya_admin;
        $this->showModal = true;
    }


    public $photo;
    public $message;
    public function simpanPembayaran()
    {
        $this->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB Max
            'metode_bayar' => 'required|in:cash,transfer',
        ]);

        // Simpan file
        $path = $this->photo->store('bukti-bayar', 'public');

        // Update status tagihan
        $this->selectedTagihan->update([
            'status' => 'menunggu validasi'
        ]);

        // Simpan data pembayaran
        Pembayaran::create([
            'tagihan_id' => $this->selectedTagihan->id,
            'pelanggan_id' => $this->selectedTagihan->pelanggan->id,
            'jumlah_meter' => $this->jumlah_meter,
            'metode_bayar' => $this->metode_bayar,
            'total_bayar' => $this->jumlah_bayar,
            'tarif_per_kwh' => $this->tarif_per_kwh,
            'bukti_bayar' => $path
        ]);

        // Reset form
        $this->reset(['selectedTagihan', 'jumlah_bayar', 'showModal', 'photo', 'metode_bayar']);

        // Refresh data
        $this->dispatch('update-pelanggan');

        // Beri feedback ke user
        $this->message = 'Tagihan berhasil di Submit. Menunggu validasi Admin';
    }

    #[On('update-pelanggan')]
    public function updatePelanggan() {}
}
