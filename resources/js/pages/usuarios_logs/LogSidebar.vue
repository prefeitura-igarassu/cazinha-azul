<template>

<Sidebar :visible="visible" :title="title" :rows="rows" :baseZIndex="3000" @update:visible="updatedVisible">
    <template #buttons>
        <div class="flex justify-content-between my-2 flex-wrap">
            <div class="flex gap-1"></div>

            <div class="flex gap-1">
                <Button label="Voltar"  icon="pi pi-angle-left"  class="p-button-sm p-button-text"                 @click="$emit('voltar')"  :disabled="!isVoltar"  />
                <Button label="Próximo" icon="pi pi-angle-right" class="p-button-sm p-button-text" iconPos="right" @click="$emit('proximo')" :disabled="!isProximo" />
            </div>
        </div>
    </template>
</Sidebar>

</template>

<script>
import dayjs from "dayjs";
import Sidebar from "@/components/properties/Sidebar.vue";

import Button from "primevue/button";

export default {
    components: { Sidebar, Button },
    props: {
        'id'        : { default: 0 } , 
        'log'       : { default: null } , 
        'visible'   : { type: Boolean , default: false } , 
        'isVoltar'  : { type: Boolean , default: false } , 
        'isProximo' : { type: Boolean , default: false } ,
    },

    data() {
        return {
            
        };
    },

    computed: {
        title(){
            return this.log 
                ? "Registro Nº " + this.log.id 
                : "";
        },

        rows(){
            if( !this.log ){
                return [];
            }

            return [{
                title: 'Registro',
                subtitle: null,
                visible: true,
                rows: [
                    { title: 'Ocorrido em'  , value: dayjs( this.log.created_at ).format( 'DD/MM/YYYY [às] HH:mm:ss' ) },
                    { title: 'Referente à'  , value: this.log.externo_id },
                    { title: 'Usuário'      , value: this.log.usuario.nome },
                    { title: 'IP'           , value: this.log.ip },
                    { title: 'Módulo'       , value: this.log.modulo },
                    { title: 'Ação'         , value: this.log.acao },
                    { title: 'Descrição'    , value: this.log.descricao },
                ] ,
            } , {
                title: 'Parâmetros',
                subtitle: null,
                visible: true,
                rows: this.getParametros(),
            }];
        }
    },

    methods: {
        getParametros(){
            return Object.keys( this.log.parametros ).map( key =>{
                return { title: key , value: this.log.parametros[key ] };
            });
        },

        updatedVisible( value ){
            this.$emit( 'update:visible' , value );
        },
    },

    created(){
        if( !this.log && this.visible ){
            this.$router.push( "/logs" );
        }
    }
}
</script>