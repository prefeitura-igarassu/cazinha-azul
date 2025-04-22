@if ( is_array( $data ) && array_key_exists( 'value' , $data ) )
<x-element-one :data="$data"></x-element-one>
@elseif ( is_array( $data ) )
<x-element-many :data="$data"></x-element-many>
@else
<x-element-text :data="$data"></x-element-text>
@endif