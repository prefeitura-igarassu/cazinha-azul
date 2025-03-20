<template>
    <div>
        <div v-if="hasTitulo">
            <h5>Registros de Atividades dos Usuários</h5>
        </div>

        <SearchInline id="logs" :search="search" @pesquisar="setFilters"></SearchInline>

        <Table 
            :tableId="tableId"
            :service="service" 
            :columns="columns" 
            :filters="filters"
            :hasGlobalFilter="false"
            :hasChips="false"
            :searchInline="[]"
            with="usuario"
            vazio="Nenhum registro foi encontrado."
            @visualizar="visualizar"
        ></Table>

        <LogSidebar 
            v-model:visible="sidebar.visible" 
            :log="sidebar.selecionado"
            :isVoltar="sidebar.index > 0"
            :isProximo="sidebar.index + 1 < logs.length"
            @voltar ="setSelecionar( sidebar.index - 1 )"
            @proximo="setSelecionar( sidebar.index + 1 )"
        ></LogSidebar>
    </div>
</template>

<script>
import Page from "@/components/Page.vue";
import Table from "@/components/table/Table.vue";
import SearchInline from "@/components/search/SearchLine.vue";
import colunas from "./tabela";
import search from "./search";
import LogSidebar from "./LogSidebar.vue";
import service from "@/services/Service";

export default {
    components: { Page , Table , LogSidebar, SearchInline },
    props: [ 'usuario_id' ],

    data() {
        return {
            columns: colunas,
            search : search,
            service: service.logs,
            sidebar:{
                visible: false,
                index: -1,
                selecionado: null,
            },

            logs: [],
            filters: {},
        };
    } ,

    computed: {
        tableId(){
            return this.usuario_id ? "logs-usuarios" : "logs";
        },

        hasTitulo(){
            return !this.usuario_id;
        },
    },

    methods: {
        visualizar( event , data , selected ){
            this.logs = data;
            this.setSelecionar( event.index );
        },

        setSelecionar( index ){
            this.sidebar = {
                visible: true,
                index: index,
                selecionado: this.logs[ index ],
            };
        },

        setFilters( filters ){
            this.filters = {... filters};

            if( this.usuario_id ){
                this.filters.usuario_id = this.usuario_id;
            }

            return this.filters;
        },
    },

    mounted(){
        this.setFilters( {} );
    }
}
</script>