<x-mail::message>
# Halo {{ $order->customer_name ?? 'Pelanggan' }},

Terima kasih telah melakukan pembelian produk **{{ $productName }}**.

@if($customMessage)
<x-mail::panel>
{!! nl2br(e($customMessage)) !!}
</x-mail::panel>
@endif

Berikut adalah akses ke produk Anda:

@if($downloadUrl)
<x-mail::button :url="$downloadUrl">
Akses / Download Produk
</x-mail::button>
@elseif($filePath)
<x-mail::button :url="asset('storage/'.$filePath)">
Download File
</x-mail::button>
@else
_Produk ini tidak memiliki file atau URL yang dilampirkan. Silakan hubungi penjual jika ada pertanyaan._
@endif

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
