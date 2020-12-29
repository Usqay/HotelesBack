<?php

namespace App\Traits;

use App\Mail\ReservationCreated;
use App\Mail\ReservationDeleted;
use App\Mail\ReservationUpdated;
use App\Models\Reservation;
use App\Models\SystemConfiguration;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

trait UserLog
{
    public function saveUserLog($model, $operation = 'store')
    {
        
        $className = get_class($model);
        $userId = null;

        if(Auth::user()){
            $userId = Auth::user()->id;
        }

        UserActivity::create([
            'user_id' => $userId,
            'operation' => $operation,
            'model' => $className,
            'model_id' => $model->id ? $model->id : null,
        ]);
    }

    public function notifyReservationCreated(Reservation $reservation){
        $configuration = SystemConfiguration::where('key', '=', 'notify_reservation_created')->first();

        if($configuration->value){
            $notificationsEmails = SystemConfiguration::where('key', '=', 'notifications_emails')->first();
            $emails = json_decode($notificationsEmails->value);

            Mail::to($emails)->locale('es')
            ->queue(new ReservationCreated($reservation));
        }
    }

    public function notifyReservationUpdated(Reservation $reservation){
        $configuration = SystemConfiguration::where('key', '=', 'notify_reservation_updated')->first();

        if($configuration->value){
            $notificationsEmails = SystemConfiguration::where('key', '=', 'notifications_emails')->first();
            $emails = json_decode($notificationsEmails->value);

            Mail::to($emails)->locale('es')
            ->queue(new ReservationUpdated($reservation));
        }
    }

    public function notifyReservationDeleted(Reservation $reservation){
        $configuration = SystemConfiguration::where('key', '=', 'notify_reservation_canceled')->first();

        if($configuration->value){
            $notificationsEmails = SystemConfiguration::where('key', '=', 'notifications_emails')->first();
            $emails = json_decode($notificationsEmails->value);

            Mail::to($emails)->locale('es')
            ->queue(new ReservationDeleted($reservation));
        }
    }
}
