<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\People;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationPaymentResource extends JsonResource
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
        $currency = Currency::findOrFail($this->currency_id);
        $paymentMethod = PaymentMethod::findOrFail($this->payment_method_id);
        $people = null;
        if($this->people_id){
            $people = People::findOrFail($this->people_id);
        }

        return [
            'id' => $this->id,
            'active' => $active,
            'description' => $this->description,
            'reservation_id' => $this->reservation_id,
            'currency_id' => $this->currency_id,
            'currency' => $currency,
            'payment_method_id' => $this->payment_method_id,
            'payment_method' => $paymentMethod,
            'cash_register_movement_id' => $this->cash_register_movement_id,
            'people_id' => $this->people_id,
            'people' => $people,
            'total' => $this->total,
            'payment_by' => $this->payment_by,
            'print_payment' => $this->print_payment,
            'document_type' => $this->document_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
