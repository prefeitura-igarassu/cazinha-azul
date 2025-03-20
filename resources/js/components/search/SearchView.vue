<template>

<Drawer v-if="sidebar" :visible="visible" position="right" class="p-sidebar-lg" @update:visible="updatedVisible">
    <template #header>
        <h2>Pesquisar</h2>
    </template>

    <Search :columns="columns" v-model:fields="fields" :errors="errors" />

    <div class="flex gap-1">
        <Button label="Pesquisar"  icon="pi pi-search" @click="pesquisar" />
        <Button label="Cancelar"   icon="pi pi-times"  @click="cancelar"  class="p-button-text" />
    </div>
</Drawer>

</template>

<script>
import Search from './Search.vue';
import Button from "primevue/button";
import Drawer from 'primevue/drawer';

export default {
    components: { Search, Button, Drawer },

    props: {
        sidebar: { default : true , type: Boolean },
        visible: { required: true , type: Boolean },
        columns: { required: true },
        parameters: { required: true },
        hasGlobalFilter: { default : false , type: Boolean },
        globalFilter: {},
    },

    data(){
        return {
            fields: {},
            errors: {},
        };
    },

    watch: {
        parameters( value ){
            this.fields = this.parameters 
                ? (this.parameters.fields || {})
                : {};
        },
    },

    methods: {
        updatedVisible( value ){ 
            this.$emit( "update:visible"  , value );
        },
        
        pesquisar(){
            let params = {};

            Object.keys( this.fields ).forEach( key => {
                let field = this.fields[ key ];
                
                if( field.value ) 
                    params[ field.field ] = field.value;
            });

            this.$emit( "pesquisar"       , params );
            this.$emit( "update:visible"  , false  );
        },

        cancelar(){
            this.$emit( "update:visible" , false );
        }
    },

    mounted(){
        this.fields = this.parameters 
            ? (this.parameters.fields || {})
            : {};
    }
}
</script>