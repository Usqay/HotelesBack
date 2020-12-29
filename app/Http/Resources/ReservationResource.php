<?php

namespace App\Http\Resources;

use App\Models\Client;
use App\Models\Currency;
use App\Models\ReservationOrigin;
use App\Models\ReservationState;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $active = $this->deleted_at == null ? true : false;
        $client = new ReservationClientResource($this->client);
        $origin = ReservationOrigin::findOrFail($this->reservation_origin_id);
        $state = ReservationState::findOrFail($this->reservation_state_id);

        $guests = ReservationGuestResource::collection($this->guests);
        $rooms = ReservationRoomResource::collection($this->rooms);
        $totals = ReservationTotalResource::collection($this->totals);

        $paymentsQuery = DB::table('reservation_payments')
        ->where('reservation_id', '=', $this->id)
        ->groupBy('currency_id')
        ->get([
            DB::raw("SUM(total) as total"),
            'currency_id'
        ]);

        $payments = [];

        foreach($paymentsQuery as $payment){
            $payment->currency = Currency::findOrFail($payment->currency_id);
            $payments[] = $payment;
        }

        return [
            'id' => $this->id,
            'active' => $active,
            'description' => $this->description,
            'rooms' => $rooms,
            'guests' => $guests,
            'totals' => $totals,
            'payments' => $payments,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_days' => $this->total_days,
            'total_hours' => $this->total_hours,
            'client_id' => $this->client_id,
            'client' => $client,
            'reservation_origin_id' => $this->reservation_origin_id,
            'reservation_origin' => $origin,
            'coupon_id' => $this->coupon_id,
            'reservation_state_id' => $this->reservation_state_id,
            'reservation_state' => $state,
            'turn_change_id' => $this->turn_change_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
