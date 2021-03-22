<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PhpmailerController extends Controller
{
    public function email(Request $request)
    {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->CharSet = 'utf-8';
            $mail->SMTPAuth =true;
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Host = env('MAIL_HOST'); //gmail has host > smtp.gmail.com
            $mail->Port = env('MAIL_PORT'); //gmail has port > 587 . without double quotes
            $mail->Username = env('MAIL_USERNAME'); //your username. actually your email
            $mail->Password = env('MAIL_PASSWORD'); // your password. your mail password
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->Subject ='Reservacion';// $request->subject;
            $mail->MsgHTML('Esto es un mensaje de prueba en PHPMailer'); //$request->text
            $mail->addAddress('stoner6593@gmail.com', 'nombre del destinatario');
            $mail->send();
        } catch (Exception $e) {
            dd($e);
        }

        return ["result", $mail ? "success" : "failed"];
    }
}
