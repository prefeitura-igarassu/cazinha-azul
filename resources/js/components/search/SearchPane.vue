<template>

<div>
    <Search :columns="columns" v-model:fields="fields" :errors="errors" />

    <Button label="Pesquisar" class="w-full" icon="pi pi-search" @click="pesquisar" />
</div>

</template>

<script>
import Search from './Search.vue';
import Button from "primevue/button";

export default {
    components: { Search, Button },

    props: {
        columns: { required: true },
    },
    
    data(){
        return {
            fields: {},
            errors: {},
        };
    },

    watch: {
        visible ( value ){ 
            this.$emit( "update:visible"  , value );
        },
    },

    methods: {
        pesquisar(){
            let params = {};
            
            Object.keys( this.fields ).forEach( key => {
                let field = this.fields[ key ];
                
                if( field.value ) 
                    params[ field.field ] = field.value;
            });

            this.$emit( "pesquisar" , params );
            this.$emit( "update:visible"  , false );
        },

        cancelar(){
            this.$emit( "update:visible" , false );
        }
    },

    mounted(){
        
    }
}
</script>