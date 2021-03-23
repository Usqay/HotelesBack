<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte caja diaria</title>
    <style>
        div {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
        }

    </style>
</head>

<body onLoad="window.print(); window.close();">
    <div
        style=" background-color:#ffffff;color:#718096;height:100%;line-height:1.4;margin:0;padding:0;width:100%!important">


        <table width="100%" cellpadding="1" cellspacing="1" role="presentation"
            style=" background-color:#edf2f7;margin:0;padding:0;width:100%">
            <tr style="text-align: center; color:#3d4852;font-size:25px;font-weight:bold;text-decoration:none;">
                <td colspan="6">Reporte de movimientos</td>
            </tr>
            <tr style="text-align: center; color:#3d4852;font-size:14px;font-weight:bold;text-decoration:none;">
                <td colspan="6">Desde : {{$request['f_inicio']}} - Hasta:  {{$request['f_fin']}}</td>
            </tr>
            <tr  style=" background-color:#ffffff;color:#33363b; font-size:18px;font-weight:bold">
                <td style="width: 8%">ID</td>
                <td >FECHA</td>
                <td>TIPO</td>
                <td>DESCRIPCIÓN</td>
                <td style="width: 10%">-</td>
                <td>TOTAL</td>
            </tr>
            @php
                $total_in = 0;
                $total_out = 0;
            @endphp
            @foreach ($datos as $item)
            @php
                $item->cash_register_movement_type['in_out'] == 0 ?
                $total_out += $item['amount']
                 :
                $total_in += $item['amount']
            @endphp
                 <tr>
                    <td>
                         {{$item['id']}}
                    </td>
                    <td>
                        {{$item['created_at']}}
                    </td>
                    <td>
                        {{$item->cash_register_movement_type['name']}}
                    </td>
                    <td>
                        {{$item['description']}}
                    </td>
                    <td>
                        {{$item->cash_register_movement_type['in_out'] == 0 ? 'Salida' : 'Ingreso'}}
                    </td>
                    <td>
                        {{$item['amount']}}
                    </td>

                </tr>
            @endforeach
            <tr><td colspan="6"></td></tr>
            <tr  style=" background-color:#ffffff;color:#33363b; font-size:12px;font-weight:bold">
                <td colspan="4" style="text-align: right">Total Salida S/:</td>
                <td></td>
                <td>{{number_format($total_in,2)}}</td>
            </tr>
            <tr  style=" background-color:#ffffff;color:#33363b; font-size:12px;font-weight:bold">
                <td colspan="4" style="text-align: right">Total Ingreso S/:</td>
                <td></td>
                <td>{{number_format($total_out,2)}}</td>
            </tr>
            <tr  style=" background-color:#ffffff;color:#33363b; font-size:12px;font-weight:bold">
                <td colspan="4" style="text-align: right">Total  S/:</td>
                <td></td>
                <td>{{number_format($total_in,2) - number_format($total_out,2)}}</td>
            </tr>


        </table>
    </div>
    </div>
</body>

</html>

