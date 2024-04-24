<x-mail::message>
<p>Purchase of the product <b>"{{$event->product->name}}"</b> paid by <b>{{$event->transaction->type}}</b> with the value of <b>{{env('CURRENCY')}} {{$event->product->price}}</b></p>
<iframe src="https://maps.google.com/maps?q=10.305385,77.923029&amp;hl=es;z=14&amp;output=embed" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">></iframe>
<x-mail::button :url="$event->transaction->id">Click here to see the transaction</x-mail::button>
</x-mail::message>