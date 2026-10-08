<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductBarcodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('product_barcodes.create');
    }

    public function rules(): array
    {
        return [
            'barcode' => ['required', 'string', 'max:100', 'unique:product_barcodes,barcode'],

            'type' => ['required', 'in:internal,ean,upc,code128,other'],

            'is_primary' => ['required', 'boolean'],
        ];
    }
}
