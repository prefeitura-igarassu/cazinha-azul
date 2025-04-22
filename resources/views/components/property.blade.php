@php

if( !function_exists( "value_format" ) )
{
    function value_format( $type , $value ){
        switch( $type )
        {
            case "datetime": return datetimeFormat( $value );
            case "date"    : return dateFormat( $value );
            case "cpf_cnpj": return cpfCnpjCormat( $value );
            case "money"   : return moneyFormat( $value );
            case "integer" : 
            case "number"  : return numberFormat( $value );
            case "boolean" : return booleanFormat( $value );
            case "array"   : return is_array( $value ) ? implode( ", " , $value ) : $value;
            default        : return $value;
        }
    }

    function booleanFormat   ( $value ){ return $value ? "Sim" : "Não"; }
    function dateFormat      ( $value ){ return date( "" ); }
    function datetimeFormat  ( $value ){ return date( "" ); }
    function percentageformat( $value ){ return ; }
    function numberFormat    ( $value ){ return number_format( $number , 2, ',', '.' ); }
    function moneyFormat     ( $value ){ return "R$ " . numberFormat( $number , 2, ',', '.' ); }

    function cpfCnpjCormat( $value )
    {
        if ( !$value ) 
        {
            return null;
        }

        $cnpjCpf = preg_replace( '/\D/' , '' , $value );

        return strlen($cnpjCpf) === 11 
            ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cnpjCpf)
            : preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpjCpf);
    }
}

$valueStr = isset( $type ) 
    ? value_format( $type , $value ) 
    : $value;

@endphp

<div class="flex flex-row overflow-hidden gap-2">
    <b class="sm:w-40 border-black">{{ $title }}:</b>
    <div class="flex-auto text-left">{{ $valueStr }}</div>
</div>