<template>
    
<div v-if="hasParametros" class="flex gap-1 mb-2">
    <Chip v-for="(value,key) of parametros" :key="key" removable @remove="removed( key )">
        <span class="mr-1">{{ getTitle( key ) }}:</span> 
        <span>{{ getValue( key , value ) }}</span> 
    </Chip>

    <Button label="limpar" icon="pi pi-filter-slash" class="p-button-sm p-button-text" @click="limpar"></Button>
</div>

</template>

<script>
import ColumnType from './ColumnType';
import Chip from "primevue/chip";
import Button from "primevue/button";

export default {
    components: { Chip, Button },

    props: [ 'colunas' , 'parametros' ] ,

    computed: {
        hasParametros(){
            return this.parametros 
                ? Object.keys( this.parametros ).length > 0
                : false;
        },
    },

    methods: {
        getColuna( key ){
            return this.colunas.filter( k => k.field == key )[0];
        },

        getTitle( key ){
            let column = this.getColuna( key );
            return column ? column.title : key;
        },

        getValue( key , value ){
            if( typeof value === 'object' ){
                if( 'le' in value ){ 
                    let str = "";

                    if( 'ge' in value ) str += this.format( key , value.ge );
                    if( 'gt' in value ) str += this.format( key , value.gt );
                    if( 'le' in value ) str += " à " + this.format( key , value.le );
                    if( 'lt' in value ) str += " à " + this.format( key , value.lt );

                    return str;
                }
                else if( '**' in value ) return this.format( key , value['**'] );
                else if( '*.' in value ) return this.format( key , value['*.'] );
                else if( '.*' in value ) return this.format( key , value['.*'] );
            }
            
            return this.format( key , value );
        },

        format( key , value ){
            try{
                let c = this.getColuna( key );
                let f = c && c.type
                    ? ColumnType.get( c.type ).format 
                    : null;
                
                return f ? f( value ) : value;
            }
            catch( e ){
                console.error( e );
                return "";
            }
        },

        removed( key ){
            let p = this.parametros;
            delete p[ key ];

            this.$emit( "removed" , p );
        },

        limpar(){
            this.$emit( "removed" , {} );
        }
    },
}
</script>