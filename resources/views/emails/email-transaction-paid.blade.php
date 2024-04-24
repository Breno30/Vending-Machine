<x-mail::message>
<p>{!! __('Purchase of the product <b>":productName"</b> paid by <b>:transactionType</b> with the value of <b>:currency :price</b>',
        [
            'productName' => $event->product->name,
            'transactionType' => $event->transaction->type,
            'currency' => env('CURRENCY'),
            'price' => $event->product->price
        ]) !!}</p>
<iframe src="https://maps.google.com/maps?q={{ $event->machine->latitude }},{{ $event->machine->longitude }}&amp;hl=es;z=14&amp;output=embed" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">></iframe>
<x-mail::button :url="$event->transaction->id">{{ __('Click here to see the transaction') }}</x-mail::button>
</x-mail::message>