<template>

    <div class="flex justify-content-between flex-wrap">
        <div class="flex text-4xl font-bold">{{ devolutiva?.ficha?.nome }}</div>
        <div class="flex">
            <Actions :actions="actions" :selected="[ this.devolutiva ]" @pesquisar="voltar" />
        </div>
    </div>
        
    <div class="rounded-lg bg-white p-4 mt-4">
        <div class="flex gap-8 flex-wrap my-4">
            <property col="" title="Feita em"   :value="devolutiva?.feito_em"        type="datetime"></property>
            <property col="" title="Serviço"    :value="devolutiva?.servico?.nome"   ></property>
            <property col="" title="Terapeuta"  :value="devolutiva?.terapeuta?.nome" :to="`/terapeutas/${devolutiva.terapeuta_id}`"></property>
            <property col="" title="Ficha"      :value="devolutiva?.ficha?.nome"     :to="`/fichas/${devolutiva.ficha_id}`"></property>
        </div>

        <div>
            <b class="block">Evolução</b>
            <div v-html="devolutiva.evolucao"></div>
        </div>
    </div>
</template>
    
<script>
import View    from "@/components/views/View.vue";
import service from "@/services/Service";
import acoes   from "./acoes";
import colunas from "./tabela";

import Table   from "@/components/table/Table.vue";
import Actions from "@/components/table/Actions.vue";
import Property  from "../fichas/Property.vue";

export default {
    components: { 
        View, Property, Actions, Table
    },

    props: [ "id" ] ,

    data() {
        return {
            devolutiva: {},
            actions: acoes( this ),
            columns: colunas,

            service: service.devolutivas
        };
    },

    watch: {
        id( value ){
            this.init();
        },
    },

    computed: {
        filter(){
            return {
                ficha_id: this.devolutiva.ficha_id,
                servico_id: this.devolutiva.servico_id,
            };
        },
    },

    methods: {
        voltar(){
            this.$router.push( "/devolutivas" );
        },

        init(){
            service.devolutivas.get( this.id , { with: [ 'ficha' , 'servico' , 'terapeuta' ] } )
                .then( r => this.devolutiva = r );
        },

        visualizar( event ){
            this.$router.push( `/devolutivas/${event.data.id}` );
        },

        imprimir( selected ){
            selected.forEach( devolutiva => {
                service.devolutivas.imprimir( devolutiva.id );
            } );
        },
    },

    mounted(){
        if( this.id ){
            this.init();
        }
    }
}
</script>