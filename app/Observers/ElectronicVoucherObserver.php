<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\ElectronicVoucher;
use App\Models\ElectronicVoucherType;
use App\Traits\Printing;

class ElectronicVoucherObserver
{
    use Printing;

    /**
     * Handle the electronic voucher "created" event.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return void
     */
    public function created(ElectronicVoucher $electronicVoucher)
    {
        if($electronicVoucher->print){
            $apiBody = json_decode($electronicVoucher->api_body, true);
            $apiResponse = json_decode($electronicVoucher->api_response, true);
            $electronicVoucherType = ElectronicVoucherType::findOrFail($electronicVoucher->electronic_voucher_type_id);
            $baseCurrency = Currency::where('is_base', '=', true)->first();

            //$this->setBusinessInfo();

            foreach($apiBody['items'] as $item){
                $this->addItem($item['descripcion'], $item['cantidad'], $item['total']);
            }

            $this->setTax($apiBody['total_igv']);
            $this->setSubtotal($apiBody['total_gravada']);
            $this->setTotal($apiBody['total']);

            if(isset($apiResponse) && isset($apiResponse['cadena_para_codigo_qr'])){
                $this->setQR($apiResponse['cadena_para_codigo_qr'], $apiResponse['codigo_hash']);

                 //poner datos correctos
                \QRCode::text($apiResponse['cadena_para_codigo_qr'])
                ->setOutfile('./qr/'.$electronicVoucher->number.'.png')
                ->png();


            }


            //$this->printReceipt(strtoupper($electronicVoucherType->name), $electronicVoucher->number, $baseCurrency->symbol, $electronicVoucher->date_emitted, $apiBody['cliente_denominacion'], $apiBody['cliente_numero_de_documento']);
        }
    }

    /**
     * Handle the electronic voucher "updated" event.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return void
     */
    public function updated(ElectronicVoucher $electronicVoucher)
    {
        //
    }

    /**
     * Handle the electronic voucher "deleted" event.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return void
     */
    public function deleted(ElectronicVoucher $electronicVoucher)
    {
        //
    }

    /**
     * Handle the electronic voucher "restored" event.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return void
     */
    public function restored(ElectronicVoucher $electronicVoucher)
    {
        //
    }

    /**
     * Handle the electronic voucher "force deleted" event.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return void
     */
    public function forceDeleted(ElectronicVoucher $electronicVoucher)
    {
        //
    }
}
