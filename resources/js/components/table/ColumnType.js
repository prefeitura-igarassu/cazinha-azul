import dayjs from "dayjs";

//from: https://gist.github.com/marceloneppel/dd9c17a01c1a8031c760b034dad0efd9
function cnpjCpfFormat( value ){
  if( !value ) return ;

  const cnpjCpf = value.replace( /\D/g , '' );
  
  return cnpjCpf.length === 11
    ? cnpjCpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3-\$4")
    : cnpjCpf.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
}

const moneyFormatter   = new Intl.NumberFormat('pt-BR', { style: 'currency' , currency: 'BRL' } );
const numericFormatter = new Intl.NumberFormat( 'pt-BR' );

function booleanFormat( value ){ return value ? 'Sim': 'Não'; }
function dateFormat( value ){ return value ? dayjs( value ).format( 'DD/MM/YYYY' ) : ""; }
function datetimeFormat( value ){ return value ? dayjs( value ).format( 'DD/MM/YYYY HH:mm:ss' ) : ""; }
function percentageFormat( value ){ return (value ? value.toFixed( 2 ) : 0) + "%"; }

function optionsFormat( value , data , column ){
    if( !value && value !== 0 ) return null;

    return column.options.find 
      ? column.options?.find( el => el?.value == value )?.label
      : null;
}

export default {
    'date'    : { format: dateFormat     },
    'datetime': { format: datetimeFormat },
    'boolean' : { format: booleanFormat  },
    'cpf_cnpj': { format: cnpjCpfFormat  },
    'options' : { format: optionsFormat  },
    'money'   : { format: moneyFormatter.format   , class: 'text-right' },
    'numeric' : { format: numericFormatter.format , class: 'text-right' },
    'percentage': { format: percentageFormat      , class: 'text-right' },
    'default' : { format: ( v ) => v              , class: ''           },

    add( name , format , classz ){
      this[ name ] = {
        format: format,
        class: classz,
      };
      
      return this;
    },

    get( name ){
      return name && this.hasOwnProperty( name ) 
        ? this[ name ] 
        : this[ 'default' ];
    },
};