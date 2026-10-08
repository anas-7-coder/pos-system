<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductBarcodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('product_barcodes.update');
    }

    public function rules(): array
    {
        $barcode = $this->route('productBarcode');

        return [
            'barcode' => ['required', 'string', 'max:100', Rule::unique('product_barcodes', 'barcode')->ignore($barcode->id)],

            'type' => ['required', 'in:internal,ean,upc,code128,other'],

            'is_primary' => ['required', 'boolean'],
        ];
    }
}
