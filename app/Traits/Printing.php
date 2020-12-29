<?php

namespace App\Traits;

use App\Models\Printer;
use App\Models\SystemConfiguration;
use Mike42\Escpos\Printer as PrinterLibrary;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Luecano\NumeroALetras\NumeroALetras;

trait Printing
{
    public $MAX_PRINTER_LENGTH = 48;
    public $MAX_ITEM_LENGTH = 33;
    public $MAX_QUANTITY_LENGTH = 6;
    public $MAX_TOTAL_LENGTH = 9;
    public $ITEMS = [];
    public $TAX = 0;
    public $SUBTOTAL = 0;
    public $TOTAL = 0;
    public $LOGO = null;
    public $BSUINESS_NAME = "---";
    public $COMMERCIAL_NAME = "---";
    public $BUSINESS_ADDRESS = null;
    public $BUSINESS_PHONE = null;
    public $BUSINESS_RUC = "---";
    public $QR_STRING = null;
    public $HASH_CODE = null;

    public function getPrinter($printerFor = 'base_printer')
    {
        $configurationPrinter = SystemConfiguration::where('key', '=', $printerFor)->first();
        $printerModel = Printer::findOrFail($configurationPrinter->value);

        if ($printerModel->printer_type_id == 1) {
            //Network printer
            $connector = new NetworkPrintConnector($printerModel->ip_address);
        } else {
            //USB Printer
            $serverIP = request()->server('SERVER_ADDR');
            $connector = new WindowsPrintConnector("smb://$serverIP/$printerModel->name");
        }

        $printer = new PrinterLibrary($connector);

        return $printer;
    }

    public function printReceipt($receiptType, $receiptNumber, $currencySymbol, 
    $emissionDate, $clientName, $clientDocument)
    {
        switch($receiptType){
            case 'BOLETA' : $printer = $this->getPrinter('ballot_printer'); break;
            case 'FACTURA' : $printer = $this->getPrinter('invoice_printer'); break;
            case 'NOTA DE VENTA' : $printer = $this->getPrinter('ticket_printer'); break;
            default : $printer = $this->getPrinter(); break;
        }
        $receiptNumber = str_pad($receiptNumber, 8, '0', STR_PAD_LEFT);

        if ($this->LOGO != null) {
            $printer->setJustification(PrinterLibrary::JUSTIFY_CENTER);
            $printer->graphics($this->LOGO);
            $printer->feed();
        }

        /* Business info */
        $printer->setJustification(PrinterLibrary::JUSTIFY_CENTER);
        $printer->text("$this->BSUINESS_NAME\n");
        $printer->text("$this->COMMERCIAL_NAME\n");
        if ($this->BUSINESS_ADDRESS != null) {
            $printer->text("$this->BUSINESS_ADDRESS\n");
        }
        if ($this->BUSINESS_PHONE != null) {
            $printer->text("Telf.: $this->BUSINESS_PHONE\n");
        }

        /* Title of receipt */
        $printer->setEmphasis(true);
        $printer->text(str_pad("", $this->MAX_PRINTER_LENGTH, '-')."\n");
        $printer->text("$receiptType - $receiptNumber\n");
        $printer->text(str_pad("", $this->MAX_PRINTER_LENGTH, '-')."\n");
        $printer->setEmphasis(false);
        $printer->text("Fecha de emisión: $emissionDate\n");
        $printer->text(str_pad("", $this->MAX_PRINTER_LENGTH, '-')."\n");
        $printer->text("Cliente: $clientName\n");
        $printer->text("Documento: $clientDocument\n");
        $printer->text(str_pad("", $this->MAX_PRINTER_LENGTH, '-')."\n");

        /* Items */
        if (count($this->ITEMS) > 0) {

            $header = str_pad("Ítem", $this->MAX_ITEM_LENGTH)
            .str_pad("Cant.", $this->MAX_QUANTITY_LENGTH, ' ', STR_PAD_LEFT)
            .str_pad("Total", $this->MAX_TOTAL_LENGTH, ' ', STR_PAD_LEFT)
            ."\n";
            $formatter = new NumeroALetras();

            $printer->setJustification(PrinterLibrary::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            $printer->text($header);
            $printer->setEmphasis(false);
            foreach ($this->ITEMS as $item) {
                $this->printItem($printer, $item['item'], false, $item['quantity'], $item['total']);
            }
            $printer->feed();

            $printer->selectPrintMode(PrinterLibrary::MODE_DOUBLE_WIDTH);
            if($receiptType != 'NOTA DE VENTA'){
                $printer->text(str_pad('Subtotal', 12) . str_pad($currencySymbol, 4). str_pad($this->SUBTOTAL, 8, ' ', STR_PAD_LEFT)."\n");
                $printer->text(str_pad('I.G.V', 12) . str_pad($currencySymbol, 4). str_pad($this->TAX, 8, ' ', STR_PAD_LEFT)."\n");
            }
            $printer->text(str_pad('Total', 12) . str_pad($currencySymbol, 4). str_pad($this->TOTAL, 8, ' ', STR_PAD_LEFT)."\n");

            $printer->setJustification(PrinterLibrary::JUSTIFY_CENTER);
            $printer->feed();
            $printer->text($formatter->toMoney($this->TOTAL, 2, 'SOLES', 'CENTIMOS'));
            $printer->selectPrintMode();
            $printer->feed(2);
        }

        /* Footer */        
        switch($receiptType){
            case 'BOLETA' : 
                $printer->text("Representación impresa de la BOLETA DE VENTA ELECTRONICA.");
                $printer->text("Autorizado por la sunat mediante resolución de Intendencia No. 034-0050005315\n\n");
                $printer->feed();
                $printer->text("Revisa este documento en: https://www.nubefact.com/buscar\n");
                break;
            case 'FACTURA' : 
                $printer->text("Representación impresa de la FACTURA DE VENTA ELECTRONICA. ");
                $printer->text("Autorizado por la sunat mediante resolución de Intendencia No. 034-0050005315\n\n");
                $printer->feed();
                $printer->text("Revisa este documento en: https://www.nubefact.com/buscar\n");
                break;
            case 'NOTA DE VENTA' :
                $printer->text("Documento electronico sin valor.\n\n");
                break;
        }
        
        /** QR Code and Hash Code */
        if ($this->QR_STRING != null) {
            $printer->setJustification(PrinterLibrary::JUSTIFY_CENTER);
            $printer->feed();
            $printer->qrCode($this->QR_STRING, PrinterLibrary::QR_ECLEVEL_L, 4);
            $printer->feed();
            $printer->text("Hash: $this->HASH_CODE\n");
        }

        /* Cut the receipt and open the cash drawer */
        $printer->cut();
        $printer->pulse();

        $printer->close();
    }

    public function addItem($item, $quantity, $total)
    {
        $this->ITEMS[] = ['quantity' => $quantity, 'item' => $item, 'total' => $total];
    }

    public function setBusinessInfo()
    {
        $print_logo = SystemConfiguration::where('key', '=', 'print_logo')->first();
        $business_name = SystemConfiguration::where('key', '=', 'business_name')->first();
        $commercial_name = SystemConfiguration::where('key', '=', 'commercial_name')->first();
        $business_address = SystemConfiguration::where('key', '=', 'business_address')->first();
        $ruc = SystemConfiguration::where('key', '=', 'ruc')->first();
        $business_phone_number = SystemConfiguration::where('key', '=', 'business_phone_number')->first();

        if($print_logo->value){
            $this->LOGO = EscposImage::load(asset("logo.png"), false);
        }

        $this->BSUINESS_NAME = strtoupper($business_name->value);
        $this->COMMERCIAL_NAME = strtoupper($commercial_name->value);
        $this->BUSINESS_ADDRESS = $business_address->value;
        $this->BUSINESS_RUC = $ruc->value;

        if(isset($business_phone_number)){
            $this->BUSINESS_PHONE = $business_phone_number->value;
        }
    }

    public function setTax($tax){    
        $this->TAX = $tax;
    }

    public function setSubtotal($subtotal){    
        $this->SUBTOTAL = $subtotal;
    }

    public function setTotal($total){    
        $this->TOTAL = $total;
    }

    public function setQR($qrCode, $hash){
        $this->QR_STRING = $qrCode;
        $this->HASH_CODE = $hash;
    }

    private function printItem($printing, $item, $onlyItem = false, $quantity = null, $total = null)
    {
        if ($onlyItem) {
            if (strlen($item) > $this->MAX_ITEM_LENGTH) {
                $toPrint = substr($item, 0, $this->MAX_ITEM_LENGTH);
                $toRepeat = substr($item, $this->MAX_ITEM_LENGTH);
                $printing->text($toPrint . "\n");
                $this->printItem($printing, $toRepeat, true);
            } else {
                $printing->text($item . "\n");
            }
        } else {
            if (strlen($item) > $this->MAX_ITEM_LENGTH) {
                $toPrint = substr($item, 0, $this->MAX_ITEM_LENGTH);
                $toRepeat = substr($item, $this->MAX_ITEM_LENGTH);

                $quantity = str_pad($quantity, $this->MAX_QUANTITY_LENGTH, ' ', STR_PAD_LEFT);
                $total = str_pad($total, $this->MAX_TOTAL_LENGTH, ' ', STR_PAD_LEFT);
                $printing->text($toPrint . $quantity . $total . "\n");

                $this->printItem($printing, $toRepeat, true);
            } else {
                $item = str_pad($item, $this->MAX_ITEM_LENGTH);
                $quantity = str_pad($quantity, $this->MAX_QUANTITY_LENGTH, ' ', STR_PAD_LEFT);
                $total = str_pad($total, $this->MAX_TOTAL_LENGTH, ' ', STR_PAD_LEFT);
                $printing->text($item . $quantity . $total . "\n");
            }
        }

        return;
    }
}
