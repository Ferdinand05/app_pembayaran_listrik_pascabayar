<div>
    <div>
        <div class="row mt-5 mb-5">
            <div class="col-md-4 mx-auto ">
                <form wire:submit='cariPelanggan'>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Masukkan Nomor KWH"
                            aria-label="Masukkan Nomor KWH" wire:model='nomor_kwh' aria-describedby="button-addon2">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">Cari</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div>

        <div class="row mb-5">
            <div class="col-md-6 mx-auto">
                <div class=" mt-1 mb-1 ">
                    Nomor KWH :
                    <span class="fw-semibold">
                        {{ $pelanggan->nomor_kwh ?? '-' }}
                    </span>
                </div>
                <div class=" mt-1 mb-1 ">
                    Atas Nama :
                    <span class="fw-semibold">
                        {{ $pelanggan->nama ?? '-' }}
                    </span>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Total </th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jml. Meter</th>
                            <th>Biaya Adm</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @if ($pelanggan !== null)
                            @foreach ($pelanggan->tagihan as $item)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ number_format($item->total_tagihan, '0', ',', '.') }}</td>
                                    <td>{{ $item->bulan }}</td>
                                    <td>{{ $item->tahun }}</td>
                                    <td>{{ $item->jumlah_meter }}</td>
                                    <td>{{ number_format($item->biaya_admin, '0', ',', '.') }}</td>
                                    <td>

                                        @if ($item->status == 'belum dibayar')
                                            <span class=" text-danger fw-medium">
                                                {{ $item->status }}
                                            </span>
                                        @else
                                            <span class=" text-danger fw-medium">
                                                {{ $item->status }}
                                            </span>
                                        @endif



                                    </td>
                                    <td>
                                        <div>
                                            @if ($item->status == 'belum dibayar')
                                                <button class="btn btn-primary btn-sm"
                                                    wire:click='openModalBayar({{ $item->id }})'>
                                                    Bayar
                                                </button>
                                            @elseif($item->status == 'lunas')
                                                <div>
                                                    <div class="badge text-bg-success">Lunas</div>
                                                    <a href="{{ route('cetak.tagihan', $item->id) }}"
                                                        class="btn btn-secondary btn-sm">Cetak</a>
                                                </div>
                                            @else
                                                <div class="badge text-bg-secondary">Menunggu Validasi</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div x-data="{ showModal: @entangle('showModal') }" x-cloak>
        <template x-if="showModal">
            <div class="modal d-block" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content shadow">
                        <div class="modal-header">
                            <h5 class="modal-title">Pembayaran Tagihan</h5>
                            <button type="button" class="btn-close" @click="showModal = false"></button>
                        </div>
                        <form wire:submit="simpanPembayaran" enctype="multipart/form-data">
                            <div class="modal-body">
                                <p>Bulan/Tahun: <strong>{{ $bulan ?? '-' }} {{ $tahun ?? '-' }}</strong>
                                </p>
                                <p>
                                    Tarif per KWH : {{ $tarif_per_kwh ?? 0 }}
                                </p>
                                <p>
                                    Jumlah Meter : {{ $jumlah_meter ?? 0 }}
                                </p>
                                <p>Total Tagihan: <strong>Rp
                                        {{ number_format($selectedTagihan?->total_tagihan + $selectedTagihan?->biaya_admin) }}</strong>
                                <div>
                                    <small>*sudah termasuk admin</small>
                                </div>

                                </p>


                                <select class="form-select" wire:model='metode_bayar'>
                                    <option selected>Pilih Metode Bayar</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                                <div class="mt-2">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Masukan Bukti Pembayaran</label>
                                        <input type="file" class="form-control" wire:model='photo' accept="image/*">
                                        @error('photo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @if ($photo)
                                            <div class="mt-2">
                                                <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail"
                                                    width="200">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" @click="showModal = false">Tutup</button>
                                <button class="btn btn-primary" type="submit">Bayar Sekarang</button>
                            </div>
                        </form>
                    </div>
                    <small class=" text-primary">{{ $message ?? '' }}</small>
                </div>
            </div>
        </template>

        <div x-show="showModal" class="modal-backdrop fade show"></div>
    </div>



    {{-- {{ $token ?? '-' }}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        function midtransComponent() {
            return {
                token: @entangle('token'),

                init() {
                    this.$watch('token', (value) => {
                        if (!value) return;

                        snap.pay(value, {
                            onSuccess(result) {
                                alert('Pembayaran berhasil!');
                                console.log(result);
                            },
                            onPending(result) {
                                alert('Menunggu pembayaran...');
                                console.log(result);
                            },
                            onError(result) {
                                alert('Pembayaran gagal!');
                                console.log(result);
                            },
                            onClose() {
                                alert('Kamu menutup popup pembayaran.');
                            }
                        });
                    });
                }
            };
        }
    </script> --}}

</div>
