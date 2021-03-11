<?php
use App\Models\Currency;
use App\Models\SystemConfiguration;
$currency = Currency::find($datos['moneda'])
->get()
->toArray();
$print_logo = SystemConfiguration::where('key', '=', 'print_logo')->first();
$business_name = SystemConfiguration::where('key', '=', 'business_name')->first();
$commercial_name = SystemConfiguration::where('key', '=', 'commercial_name')->first();
$business_address = SystemConfiguration::where('key', '=', 'business_address')->first();
$ruc = SystemConfiguration::where('key', '=', 'ruc')->first();
$business_phone_number = SystemConfiguration::where('key', '=', 'business_phone_number')->first();

switch ($datos['tipo_de_comprobante']) {
case 2:
$documento = 'BOLETA ELECTRÓNICA';
$glosa= 'BOLETA ELECTRÓNICA';
$correlativo = strtoupper ($datos['serie']) . '-' .str_pad($datos['numero'], 8, '0', STR_PAD_LEFT);
break;
case 3:
$documento = 'NOTA DE VENTA';
$glosa= 'Documento electrónico sin valor';
$correlativo = str_pad($datos['numero'], 8, '0', STR_PAD_LEFT);
break;

case 1:
$documento = 'FACTURA ELECTRÓNICA';
$glosa= 'FACTURA ELECTRÓNICA';
$correlativo = strtoupper ($datos['serie']) . '-' . str_pad($datos['numero'], 8, '0', STR_PAD_LEFT);
break;
}
?>
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

        body {
            font-size: 10px;
            font-weight: 200;
            font-family: Arial, sans-serif;
            margin: 0px;
        }

        table {
            font-size: 10px;
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
            /*margin: auto;*/
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

<body onLoad="window.print(); window.close();"; style="margin: 0px !important;">
    <?php
    $image = base64_encode(file_get_contents(asset('logo.png')));
    //echo asset('storage/qr/'.$datos['numero'].'.png');

    ?>


    <div class="txt-center company_logo_ticket pt-4">
        <img width="80%" height="90%" src="data:image/png;base64,{{base64_encode(file_get_contents(asset('logo.png')))}}" alt="">
    </div>


    <!-- definimos el formato de la boleta mediante una tabla -->
    <table border="0" width="100%">
        <tr>
            <!-- Cabecera, contiene datos de la empresa -->
            <td colspan="5" class="txt-center">
                <div style="font-size: 12px; font-weight: bold;">
                    {{ $business_name->value }}


                </div>
                {{ $business_name->value }}<br><br>
                {{ $ruc->value }}<br /><br>
                {{ $business_address->value }}<br /><br>
                {{ $business_phone_number->value }}<br />
                <br />
                <!--                    <br>-->
                <hr class="border-top" />
            </td>
            <!-- Fin cabecera -->
        </tr>
        <tr >
            <td colspan="5" class="txt-center bold">
                <?php echo $documento; ?><br /><br>
                <?php echo $correlativo; ?><br />
                <hr class="border-top" />
                <!--                    <br>-->
            </td>
        </tr>
        <tr style="font-size: 8px;">
            <td colspan="2">Emisión</td>
            <td colspan="1">:</td>
            <td colspan="2">@php echo ($datos['fecha_de_emision']);@endphp</td>
        </tr>
        <tr style="font-size: 8px;">
            <td colspan="2">Moneda</td>
            <td colspan="1">:</td>
            <td colspan="2"><?php echo $currency[0]['plural_name']; ?></td>
        </tr>
        <tr style="font-size: 8px;">
            <td colspan='2'>Documento</td>
            <td colspan='1'>:</td>
            <td colspan='2'>@php echo ($datos['cliente_numero_de_documento']);@endphp</td>
        </tr>
        <tr style="font-size: 8px;">
            <td colspan='2'>Cliente</td>
            <td colspan='1'>:</td>
            <td colspan='2'>@php echo ($datos['cliente_denominacion']);@endphp</td>
        </tr>
        <tr style="font-size: 8px;">
            <td colspan='2'>Dirección</td>
            <td colspan='1'>:</td>
            <td colspan='2'>-</td>
        </tr>

    </table>

    <!-- definimos la tabla que muestra los detalles del pedido (producto, descripcion y total) -->
    <table border="0" width="100%">
        <tr class="txt-left " style="font-size: 8px;">
            <td colspan='2' class="border-top-bottom ">Descripción</td>
            <td class="border-top-bottom ">Cant.</td>
            <td class="border-top-bottom ">Prec.</td>
            <td class="border-top-bottom ">Total</td>
        </tr>
        @foreach ($datos['items'] as $item)
            <tr class="txt-center " style="font-size: 8px; margin-top: 0px">
                <td colspan="2">
                    {{ $item['descripcion'] }}
                </td>
                <td>
                    {{ $item['cantidad'] }}
                </td>
                <td>
                    {{ $item['precio_unitario'] }}
                </td>
                <td>
                    {{ $item['total'] }}
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="5">
                <hr class="border-top" />
            </td>
        </tr>
    </table>
    <table border="0" width="100%" style="font-size: 8px;">

        <tr class='txt-left bold'>
            <td colspan="5"  class='txt-right'>Op. Gravadas:</td>
            <td colspan='1' class='txt-center'>{{ $currency[0]['symbol'] }} {{ $datos['total_gravada'] }}</td>
        </tr>
        <tr class="txt-left bold">
            <td colspan="5"  class="txt-right">I.G.V:</td>
            <td colspan="1" class="txt-center">{{ $currency[0]['symbol'] }} {{ $datos['total_igv'] }}</td>
        </tr>
        <tr class="txt-left bold">
            <td colspan="5" class="txt-right">Total:</td>
            <td colspan="1"  class="txt-center">{{ $currency[0]['symbol'] }} {{ $datos['total'] }}</td>
        </tr>

        <tr>
            <td colspan="6">
                <hr class="border-top" />
            </td>
        </tr>
        <?php if($datos['tipo_de_comprobante'] != 3):?>
        <tr>
            <td colspan="6">
                Son: {{ $datos['monto_letras']}}
            </td>
        </tr>
        <?php endif;?>
        <!-- Mostramos medios de pago -->
        <!--Buscamos los medios de pago para propinas (si es que aplica)-->
        <!-- si pago con efectivo mostramos la moneda-->

        <!--<tr class="txt-center bold">
                <td></td>
                <td class="txt-right">EFECTIVO</td>
                <td colspan="2" class="txt-right">S/ 15.00</td>
            </tr>-->
    </table>
    <?php if($datos['tipo_de_comprobante'] != 3):?>
    <div class="txt-center company_logo_ticket pt-4">
        <img width="60%" height="95%" src="data:image/png;base64,{{base64_encode(file_get_contents(asset('qr/'.$datos['numero'].'.png')))}}" alt="">
    </div>
    <?php endif; ?>

    <table border="0" class="txt-center" width="100%" style="font-size: 8px;">
        <tr >
            <td colspan="4" style=" line-height: 120%">
                Representacion impresa de la<br>
                {{$glosa}}<br>
               <br>
               Revisa este documento en: https://www.nubefact.com/buscar
            </td>
        </tr>

        <tr>
            <td colspan="5" style=" line-height: 120%">
                Autorizado mediante Resolucion <b>0340050005315/SUNAT</b>
            </td>
        </tr>
        <tr>
            <td colspan="5" style=" line-height: 120%">
                13BovyR4xLW/QTEJu7QxiG9k8gTCf/ImO5UT3xnsd4M=
            </td>
        </tr>
        <tr>
            <td colspan="5" style=" line-height: 120%">
                www.sistemausqay.com
            </td>
        </tr>
        <tr>
            <td colspan="5" style=" line-height: 120%">
                www.facebook.com/UsqayPeru
            </td>
        </tr>

    </table>


</body>

</html>
