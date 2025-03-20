<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helpers\CpfCnpjHelper;

class CpfCnpjRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes( $attribute , $value )
    {
        $validador = new CpfCnpjHelper( $value );
        return $validador->isValido();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O CPF/CNPJ é inválido.';
    }
    
}
