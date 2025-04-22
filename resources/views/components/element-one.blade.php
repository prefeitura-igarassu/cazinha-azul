@php
    $attr    = array_diff_key( $data , [ 'element' => '' , 'value' => '' ] );
    $attrStr = '';

    foreach ($attr as $key => $value) {
        if( is_array( $value ) ) $value = implode( " " , $value );
        $attrStr .= " $key=\"". $value ."\"";
    }
@endphp
<{{ $data['element'] }}{!! $attrStr !!}><x-element :data="$data['value']"></x-element></{{ $data['element'] }}>