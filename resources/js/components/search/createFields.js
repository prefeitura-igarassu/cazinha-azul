import { shallowRef } from 'vue';
import Field from "./forms/Field.vue";
import CpfCnpj from "./forms/CpfCnpj.vue";
import Number from "./forms/Number.vue";
import Money from "./forms/Money.vue";
import Calendar from "./forms/Calendar.vue";
import Select from "./forms/Select.vue";

import nao_sim from "../data/nao_sim.json";

// -------------------- //
// -------------------- // SESSION STORAGE
// -------------------- //

function isSet( field )
{
    return typeof field !== "undefined";
}

function load( id , fields ){
    if( !id ) return ;
    
    let data = sessionStorage.getItem( id );
    let loading = false;
    
    if( !data ) return loading;

    data = JSON.parse( data );

    Object.keys( fields ).forEach( key => {
        // caso não exista valor e nem o campo, ignorar
        if( !isSet( data[ key ] ) || !isSet( fields[ key ] ) ) return ;

        fields[ key ].value = data[ key ];
        loading = true;
    });

    return loading;
}

function save( id , fields ){
    if( !id ) return ;
    sessionStorage.setItem( id , JSON.stringify( fields ?? {} ) );
}

// -------------------- //
// -------------------- //
// -------------------- //

function createFields( columns ){
    let fields = {};

    columns.forEach( column => {
        fields[ column.field ] = createComponent( column );
    });

    return fields;
}

function createComponent( column ){
    switch( column.type ){
        case 'cpf_cnpj': return create( CpfCnpj  , column );
        case 'date'    :
        case 'datetime': return create( Calendar , column );
        case 'numeric' : return create( Number   , column );
        case 'money'   : return create( Money    , column );
        case 'boolean' : 
            if( !column.options ) column.options = nao_sim;
            return create( Select , column );
        case 'options' : return create( Select   , column );
        default        : 
            return create( Field    , column );
    }
}

function create( field , column , props ){
    if( !props ) props = {};

    return {
        type: column.type,
        field: column.field,
        component: shallowRef( field ),
        value: column.defaultValue,
        show: true,
        options: column.options,
        class: column.class,
        style: column.style,
        props: {
            col: column.col ?? 'sm:log-12 lg:col-4',
            id: 'search-' + column.field,
            title: column.title,
            description: column.description,
            ... props,
        }
    };
}

export { createFields , load , save , isSet };
export default createFields;