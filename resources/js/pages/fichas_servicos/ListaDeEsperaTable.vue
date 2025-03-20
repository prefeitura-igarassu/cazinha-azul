<template>
    <Page title="Lista de Espera">
        <Table 
            tableId="lista-de-espera"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            empty="Nenhuma ficha foi encontrada."
            selectionMode="multiple"
            @visualizar="visualizar"
            @dialog="setDialog"
            :filters="filters"
            :hasChips="false"
            :hasGlobalFilter="false"
            :searchInline="[]"
            :with="['ficha']"
            ref="tabela"
        ></Table>
    </Page>
</template>

<script>
import Page    from "@/components/Page.vue";
import Table   from "@/components/table/Table.vue";
import colunas from "./tabela.js";
import acoes   from "./acoes.js";
import service from "@/services/Service";
import dayjs   from "dayjs";

export default {
    components: { Page, Table },
    props: [ "terapeuta_id" ],

    data() {
        return {
            columns: colunas,
            actions: acoes( this ),
            service: service.fichas_servicos,
        };
    },

    computed: {
        filters(){
            return { 
                terapeuta_id: this.terapeuta_id,
                dia: null,
                status: 0,

                orderBy: "posicao,asc"
            };
        },
    },

    methods: {
        visualizar( event ){
            //this.setDialog( event.data );
        },

        pesquisar(){
            this.$refs.tabela.pesquisar();
        },
    },
}
</script>