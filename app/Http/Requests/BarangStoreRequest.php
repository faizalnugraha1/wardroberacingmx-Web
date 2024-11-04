<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BarangStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'berat' => isset($this->berat) ? (int)Str::replace(',', '', $this->berat):$this->berat,
            'harga_jual' => isset($this->harga_jual) ? (int)Str::replace(',', '', $this->harga_jual):$this->harga_jual,
            'harga_asal' => isset($this->harga_asal) ? (int)Str::replace(',', '', $this->harga_asal): $this->harga_asal,
            'brand_id' => isset($this->brand_id) ? (($this->brand_id == '-') ? null : $this->brand_id) : null,
        ]);
    }
}
