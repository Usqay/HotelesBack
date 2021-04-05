@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <title>-</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../Public/css/impresion_style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

        body,
        table {
            font-family: "Lucida Console", Monaco, monospace;
            line-height: 0.8 !important;
        }


        table {

            margin: 0px;

        }

        body {
            zoom: 200%;
        }

        .logo {
            background-color: black;
            color: #fff;
        }

        body,
        html {
            border: 0;
        }

        .hr-header {
            border: 0;
            border-top: 1px solid black;
            /border-bottom: 1px solid #999;/ height: 0;
        }

        .hr-detalle {
            border: 1px dashed black;
            height: 0;
        }

        .hr-detalle-60p {
            border: 1px dashed black;
            height: 0;
            width: 60%;
        }


        .h2,
        h3 {
            font-size: 2.5em;
            font-weight: bold;
            text-align: center;
        }

        .h3 {
            font-size: 2.1em;
            font-weight: 800;
        }

        .txt-center {
            text-align: center;
        }

        .txt-left {
            text-align: left;
        }

        .txt-right {
            text-align: right;
        }

        .margin-left {
            margin-left: 98px;
        }

        .bold {
            font-weight: bold;
        }

        table {
            margin: auto;
            margin: 0px;
        }

        .table-border {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .arial {
            font-family: Arial;
            font-size: 1em;
        }

        .arial-12em {
            font-family: Arial;
            font-size: 1.2em;
            font-weight: bold;
        }

        .arial-14em {
            font-family: Arial;
            font-size: 1.4em;
        }

        .arial-16em {
            font-family: Arial;
            font-size: 2.5em;
            font-weight: bold;
        }

        .border-bottom {
            border-bottom: thin solid #333;
        }

        .border-top {
            border-top: thin solid #333;
        }
        .border-top-bottom {
            border-top: thin solid #333;
            border-bottom: thin solid #333;
        }

        .company_logo {
        max-height: 100px;

        }
        .company_logo_box {
        height: 100px;
        }
        .company_logo_ticket {
            max-width: 200px;
            max-height: 100px;
        }

    </style>
</head>

<body onLoad="window.print(); window.close();" style="margin: 0px !important;">



    <table border="0" width="100%">
            <tr style="text-align: center; color:#3d4852;font-size:8px;font-weight:bold;text-decoration:none;">
                <td colspan="5">Reporte de movimientos</td>
            </tr>
            <tr style="text-align: center; color:#3d4852;font-size:7px;font-weight:bold;text-decoration:none;">
                <td colspan="5">Desde : {{$request['f_inicio']}}</td>
            </tr>
            <tr style="text-align: center; color:#3d4852;font-size:7px;font-weight:bold;text-decoration:none;">
                <td colspan="5">Hasta:  {{$request['f_fin']}}</td>
            </tr>

            <tr class="txt-left " style="font-size: 6px;">
                <!--<td style="width: 8%">ID</td>-->
                <td >FECHA</td>
                <td>TIPO</td>
                <td>CAJA</td>
                <!--<td>-</td>-->
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
            @php
                 $fecha = Carbon::parse($item['created_at']);
            @endphp
                 <tr  class="txt-center " style="font-size: 5px; margin-top: 0px">
                   <!-- <td>
                         {{$item['id']}}
                    </td>-->
                    <td>
                        {{$fecha->format('Y-m-d')}}
                    </td>
                    <td>
                        {{$item->cash_register_movement_type['name']}}
                    </td>
                    <td>
                        {{$item->cash_register['name']}}
                    </td>
                    <!--<td>
                        {{$item->cash_register_movement_type['in_out'] == 0 ? 'Salida' : 'Ingreso'}}
                    </td>-->
                    <td>
                        {{$item['amount']}}
                    </td>

                </tr>
            @endforeach
            <tr><td colspan="5"></td></tr>
            <tr  style=" background-color:#ffffff;color:#33363b; font-size:5px;font-weight:bold">
                <td colspan="3" style="text-align: right">Total Ingreso S/:</td>

                <td class="txt-left">{{($total_in)}}</td>
            </tr>
            <tr  style=" background-color:#ffffff;color:#33363b; font-size:5px;font-weight:bold">
                <td colspan="3" style="text-align: right">Total Salida S/:</td>

                <td class="txt-left">{{($total_out)}}</td>
            </tr>
            <tr  style=" background-color:#ffffff;color:#33363b; font-size:5px;font-weight:bold">
                <td colspan="3" style="text-align: right">Total  S/:</td>

                <td class="txt-left">{{($total_in) - ($total_out)}}</td>
            </tr>


        </table>


</body>

</html>

