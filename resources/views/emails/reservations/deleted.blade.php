@component('mail::message')
# Reservación anulada

Hola **{{$businessHolder->value}}**,
se acaba de anular una reservación en **{{$commercialName->value}}**.

Anulada por: **{{$userName}}** <br>
Fecha de ingreso: **{{$reservation->start_date}}** <br>
Fecha de salida: **{{$reservation->end_date}}**

@component('mail::table')
| Habitación    | Precio total          |
| ------------- |:-----------------:|
@foreach($rooms as $room)
| {{$room->room->name}}   | {{$room->currency->symbol}}{{$room->total_price}} |
@endforeach
@endcomponent

gracias,<br>
el equipo de {{ config('app.name') }}
@endcomponent
