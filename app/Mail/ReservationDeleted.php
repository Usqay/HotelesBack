<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\SystemConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationDeleted extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;
    protected $rooms = [];
    protected $businessHolder;
    protected $commercialName;
    protected $userName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
        $this->rooms = ReservationRoom::where('reservation_id', '=', $reservation->id)
        ->with('room')
        ->with('currency')
        ->get();
        $this->businessHolder = SystemConfiguration::where('key', '=', 'business_holder_name')->first();
        $this->commercialName = SystemConfiguration::where('key', '=', 'commercial_name')->first();
        $this->userName = auth()->user()->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Reservación anulada")->
        markdown('emails.reservations.deleted', [
            'reservation' => $this->reservation,
            'rooms' => $this->rooms,
            'businessHolder' => $this->businessHolder,
            'commercialName' => $this->commercialName,
            'userName' => $this->userName,
        ]);
    }
}
