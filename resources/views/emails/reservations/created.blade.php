<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nueva reservación registrada</title>
    <style>
        div {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
        }

    </style>
</head>

<body>
    <div
        style=" background-color:#ffffff;color:#718096;height:100%;line-height:1.4;margin:0;padding:0;width:100%!important">


        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style=" background-color:#edf2f7;margin:0;padding:0;width:100%">
            <tbody>
                <tr>
                    <td align="center">
                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                            style=" margin:0;padding:0;width:100%">
                            <tbody>
                                <tr>
                                    <td style="padding:25px 0;text-align:center">
                                        <a href="#m_1570847629338170368_"
                                            style=" color:#3d4852;font-size:19px;font-weight:bold;text-decoration:none;display:inline-block">
                                            Hoteles
                                        </a>
                                    </td>
                                </tr>


                                <tr>
                                    <td width="100%" cellpadding="0" cellspacing="0"
                                        style=" background-color:#edf2f7;border-bottom:1px solid #edf2f7;border-top:1px solid #edf2f7;margin:0;padding:0;width:100%">
                                        <table align="center" width="570" cellpadding="0" cellspacing="0"
                                            role="presentation"
                                            style=" background-color:#ffffff;border-color:#e8e5ef;border-radius:2px;border-width:1px;margin:0 auto;padding:0;width:570px">

                                            <tbody>
                                                <tr>
                                                    <td style=" max-width:100vw;padding:32px">
                                                        <h1
                                                            style=" color:#3d4852;font-size:18px;font-weight:bold;margin-top:0;text-align:left">
                                                            Reservación registrada</h1>
                                                        <p
                                                            style=" font-size:16px;line-height:1.5em;margin-top:0;text-align:left">
                                                            Hola <strong>
                                                                {{ $businessHolder->value }}</strong>,
                                                            se acaba de registrar una nueva reservación en
                                                            <strong>**{{ $commercialName->value }}**</strong>.
                                                        </p>
                                                        <p
                                                            style=" font-size:16px;line-height:1.5em;margin-top:0;text-align:left">
                                                            Creada por: <strong>
                                                                {{ $userName }}</strong> <br>
                                                            Fecha de ingreso: <strong>
                                                                {{ $reservation->start_date }}</strong> <br>
                                                            Fecha de salida:
                                                            <strong>{{ $reservation->end_date }}</strong></p>
                                                        <div>
                                                            <table style=" margin:30px auto;width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th
                                                                            style="border-bottom:1px solid #edeff2;margin:0;padding-bottom:8px">
                                                                            Habitación</th>
                                                                        <th align="center"
                                                                            style=" border-bottom:1px solid #edeff2;margin:0;padding-bottom:8px">
                                                                            Precio total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($rooms as $room)
                                                                        <tr
                                                                            class="color: #74787e;font-size:15px;line-height:18px;margin:0;padding:10px 0">
                                                                            <td>
                                                                                {{ $room->room->name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $room->currency->symbol }}{{ $room->total_price }}
                                                                            </td>
                                                                        </tr>

                                                                    @endforeach

                                                                    <tr>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <p
                                                            style=" font-size:16px;line-height:1.5em;margin-top:0;text-align:left">
                                                            gracias,<br>
                                                            el equipo de {{ config('app.name') }}</p>



                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>

</body>

</html>
