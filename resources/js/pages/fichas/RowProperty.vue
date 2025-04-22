<template>

    <div class="flex flex-row overflow-hidden gap-2">
        <b class="sm:w-40 border-black">{{ title }}:</b>
        <div class="flex-auto text-left">
            {{ valueStr }}
            <span v-if="url || to" class="pi pi-external-link ml-2 cursor-pointer" @click="clicou"></span>
        </div>
    </div>
    
</template>
    
<script>
import dayjs from 'dayjs';

const moneyFormatter   = new Intl.NumberFormat('pt-BR', { style: 'currency' , currency: 'BRL' } );
const numericFormatter = new Intl.NumberFormat( 'pt-BR' );

function booleanFormat( value ){ return value ? 'Sim': 'Não'; }
function dateFormat( value ){ return value ? dayjs( value ).format( 'DD/MM/YYYY' ) : ""; }
function datetimeFormat( value ){ return value ? dayjs( value ).format( 'DD/MM/YYYY HH:mm:ss' ) : ""; }
function percentageFormat( value ){ return (value ? value.toFixed( 2 ) : 0) + "%"; }

function cnpjCpfFormat( value ){
  if( !value ) return ;

  const cnpjCpf = value.replace( /\D/g , '' );
  
  return cnpjCpf.length === 11
    ? cnpjCpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3-\$4")
    : cnpjCpf.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
}





export default {
    props: {
        title   : { required: true }, 
        value   : { required: true },
        type    : {},
        options : {},
        url     : {},
        to      : {},
    },
    
    data() {
        return { };
    },

    computed: {
        valueStr(){
            if( this.options ){
                for ( let i = 0 ; i < this.options.length ; i++ ) {
                    if( this.options[ i ].value == this.value )
                        return this.options[i].label;
                }
            }

            switch( this.type ){
                case "datetime": return datetimeFormat( this.value );
                case "date"    : return dateFormat( this.value );
                case "cpf_cnpj": return cnpjCpfFormat( this.value );
                case "money"   : return moneyFormatter.format( this.value );
                case "integer" : 
                case "number"  : return numericFormatter.format( this.value );
                case "boolean" : return booleanFormat( this.value );
                case "array"   : return this.value?.join( ", " );
            }

            return this.value;
        },
    },

    methods: {
        clicou(){
            if( this.url ) window.open( this.url  , "_blank" );
            else           this.$router.push( this.to );
        },
    },

}
</script>