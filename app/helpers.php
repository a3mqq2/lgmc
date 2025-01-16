<?php 

use App\Models\Invoice;


function get_area_name() {
    if (auth()->check()) {
        return str_replace("-", "_", explode('/', request()->url())[3]);
    }
}

function last_invoice_id()
{
    $lastInvoice = Invoice::latest()->first();
    if($lastInvoice) {
        return $lastInvoice->id + 1;
    } else {
        return 1;
    }
}