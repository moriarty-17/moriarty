@extends('template.frontend')
@section('content')
<div class="mx-auto w-full max-w-[1400px] px-4 py-8 sm:px-8 sm:py-10 lg:py-14">
    <div class="mx-auto w-full max-w-[512px] rounded-[10px] border-4 border-line bg-card p-5 sm:p-6 lg:p-8">

        @php
        $hargaAsli = (float) $penawaran->harga;
        $fee = $hargaAsli * 0.10;
        $totalHarga = $hargaAsli + $fee;
        @endphp

        <div class="flex items-center justify-between gap-4 py-[10px]">
            <span class="text-base font-medium text-light sm:text-xl">Harga</span>
            <span class="text-right text-base font-bold text-light sm:text-xl">
                IDR {{ number_format($hargaAsli, 0, ',', '.') }}
            </span>
        </div>

        <div class="flex items-center justify-between gap-4 border-y border-line py-[10px]">
            <span class="text-base font-medium text-light sm:text-xl">Pajak</span>
            <span class="text-right text-base font-bold text-light sm:text-xl">
                IDR {{ number_format($fee, 0, ',', '.') }} (10%)
            </span>
        </div>

        <div class="flex items-center justify-between gap-4 py-[10px]">
            <span class="text-base font-medium text-light sm:text-xl">Total</span>
            <span class="text-right text-base font-bold text-sky sm:text-xl">
                IDR {{ number_format($totalHarga, 0, ',', '.') }}
            </span>
        </div>

        {{-- Tampilkan Info USD Khusus untuk PayPal --}}
        @if($pembayaran->metode_pembayaran === 'paypal')
        <div class="flex items-center justify-between gap-4 border-t border-line py-[10px]">
            <span class="text-base font-medium text-light sm:text-xl">Konversi Tagihan</span>
            <span class="text-right text-base font-bold text-sky sm:text-xl">
                $ {{ $hargaUSD ?? 0 }} <span class="text-sm font-normal text-muted">(USD)</span>
            </span>
        </div>
        @endif

        <div class="mt-5 flex items-center justify-between gap-4 py-[10px] sm:mt-6">
            <span class="text-base font-medium text-light sm:text-xl">Metode</span>
            <span class="text-right text-base font-bold text-light sm:text-xl uppercase">
                {{ $pembayaran->metode_pembayaran }}
            </span>
        </div>

        <!-- TOMBOL KONFIRMASI -->
        <!-- TOMBOL KONFIRMASI -->
        @if($pembayaran->metode_pembayaran === 'paypal')
            {{-- Tombol PayPal disamakan stylenya dengan tombol Midtrans --}}
            <a href="{{ $paypalLink ?? '#' }}"
                class="mt-7 flex min-h-11 w-full items-center justify-center rounded-[10px] bg-brand px-5 py-2 text-xl font-semibold text-white transition-all duration-300 hover:bg-brand/90 hover:shadow-lg hover:shadow-brand/20 sm:mt-8 sm:text-2xl cursor-pointer">
                Lanjutkan ke PayPal
            </a>
        @else
            <button type="button" id="pay-button"
                class="btn-animasi mt-7 flex min-h-11 w-full items-center justify-center rounded-[10px] bg-brand px-5 py-2 text-xl font-semibold text-white transition-all duration-300 hover:bg-brand/90 hover:shadow-lg hover:shadow-brand/20 sm:mt-8 sm:text-2xl cursor-pointer">
                Lanjutkan Pembayaran
            </button>
        @endif

        <p class="mx-auto mt-5 max-w-[294px] text-center text-sm font-normal leading-5 text-white sm:mt-6 sm:text-base">
            Dengan melakukan pembayaran, saya menyetujui kebijakan &amp; peraturan ini
        </p>
    </div>
</div>

{{-- SCRIPT MIDTRANS SNAP (Hanya dirender jika metode QRIS/Gopay) --}}
@if(isset($snapToken))
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    fetch("{{ url('/pembayaran/sukses-snap/' . $pembayaran->kode_pembayaran) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                kode_penawaran: '{{ $pembayaran->kode_penawaran }}'
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data); 
                            if (data.status === 'success' && data.id_pemesanan) {
                                window.location.href = "{{ url('/pembayaran/invoice') }}/" + data.id_pemesanan;
                            } else {
                                alert("Status bukan success: " + JSON.stringify(data));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.location.href = "{{ url('/riwayat-pemesanan') }}";
                        });
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran diselesaikan.");
                    window.location.href = "{{ url('/riwayat-pemesanan') }}";
                },
                onError: function(result) {
                    alert("Pembayaran Gagal!");
                },
                onClose: function() {
                    alert('Anda menutup pop-up sebelum menyelesaikan pembayaran.');
                }
            });
        });
    }
</script>
@endif
@endsection