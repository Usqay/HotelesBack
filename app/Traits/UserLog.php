<?php

namespace App\Traits;

use App\Models\Reservation;
use App\Models\UserActivity;
use App\Models\ReservationRoom;
use App\Mail\ReservationCreated;
use App\Mail\ReservationDeleted;
use App\Mail\ReservationUpdated;
use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

trait UserLog
{
    protected $reservation;
    protected $rooms = [];
    protected $businessHolder;
    protected $commercialName;
    protected $userName;

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

        $this->reservation = $reservation;
        $this->rooms = ReservationRoom::where('reservation_id', '=', $reservation->id)
        ->with('room')
        ->with('currency')
        ->get();
        $this->businessHolder = SystemConfiguration::where('key', '=', 'business_holder_name')->first();
        $this->commercialName = SystemConfiguration::where('key', '=', 'commercial_name')->first();
        $this->userName = auth()->user()->name;

        $vista=view('emails.reservations.created', [
            'reservation' => $this->reservation,
            'rooms' => $this->rooms,
            'businessHolder' => $this->businessHolder,
            'commercialName' => $this->commercialName,
            'userName' => $this->userName,
        ]);

        if($configuration->value){
            $notificationsEmails = SystemConfiguration::where('key', '=', 'notifications_emails')->first();
            $emails = json_decode($notificationsEmails->value);
            $this->sendEmail($vista,$emails[0]);
        }
    }

    public function notifyReservationUpdated(Reservation $reservation){
        $configuration = SystemConfiguration::where('key', '=', 'notify_reservation_updated')->first();


        $this->reservation = $reservation;
        $this->rooms = ReservationRoom::where('reservation_id', '=', $reservation->id)
        ->with('room')
        ->with('currency')
        ->get();
        $this->businessHolder = SystemConfiguration::where('key', '=', 'business_holder_name')->first();
        $this->commercialName = SystemConfiguration::where('key', '=', 'commercial_name')->first();
        $this->userName = auth()->user()->name;

        $vista=view('emails.reservations.updated', [
            'reservation' => $this->reservation,
            'rooms' => $this->rooms,
            'businessHolder' => $this->businessHolder,
            'commercialName' => $this->commercialName,
            'userName' => $this->userName,
        ]);

        if($configuration->value){
            $notificationsEmails = SystemConfiguration::where('key', '=', 'notifications_emails')->first();
            $emails = json_decode($notificationsEmails->value);

            /*Mail::to($emails)->locale('es')
            ->queue(new ReservationUpdated($reservation));*/
            $this->sendEmail($vista,$emails[0]);
        }
    }

    public function notifyReservationDeleted(Reservation $reservation){
        $configuration = SystemConfiguration::where('key', '=', 'notify_reservation_canceled')->first();

        $this->reservation = $reservation;
        $this->rooms = ReservationRoom::where('reservation_id', '=', $reservation->id)
        ->with('room')
        ->with('currency')
        ->get();
        $this->businessHolder = SystemConfiguration::where('key', '=', 'business_holder_name')->first();
        $this->commercialName = SystemConfiguration::where('key', '=', 'commercial_name')->first();
        $this->userName = auth()->user()->name;

        $vista=view('emails.reservations.deleted', [
            'reservation' => $this->reservation,
            'rooms' => $this->rooms,
            'businessHolder' => $this->businessHolder,
            'commercialName' => $this->commercialName,
            'userName' => $this->userName,
        ]);

        if($configuration->value){
            $notificationsEmails = SystemConfiguration::where('key', '=', 'notifications_emails')->first();
            $emails = json_decode($notificationsEmails->value);
            /*
            Mail::to($emails)->locale('es')
            ->queue(new ReservationDeleted($reservation));*/
            $this->sendEmail($vista,$emails[0]);
        }
    }

    public function sendEmail($vista,$destino){

        try {

            require base_path("vendor/autoload.php");
            $mail = new PHPMailer(true);
            //$mail->isSMTP();
            $mail->isHTML(true);
            $mail->CharSet = 'utf-8';
            $mail->SMTPAuth =true;
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Host = env('MAIL_HOST'); //gmail has host > smtp.gmail.com
            $mail->Port = env('MAIL_PORT'); //gmail has port > 587 . without double quotes
            $mail->Username = env('MAIL_USERNAME'); //your username. actually your email
            $mail->Password = env('MAIL_PASSWORD'); // your password. your mail password
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->Subject ='Reservacion'; //$request->subject;
            $mail->MsgHTML($vista); //$request->text
            $mail->addAddress($destino);
            $mail->send();
        } catch (Exception $e) {
            dd($e);
        }
    }
}
