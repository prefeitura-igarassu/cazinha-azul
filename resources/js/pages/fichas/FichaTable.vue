<template>
    <Page title="Fichas">
        
        <Table 
            tableId="fichas"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasGlobalFilter="true"
            empty="Nenhuma ficha foi encontrada."
            selectionMode="multiple"
            @visualizar="visualizar"
            :hasChips="false"
            :searchInline="[]"
            :with="['unidade']"
            ref="table"
        ></Table>
    </Page>
</template>

<script>
import Page    from "@/components/Page.vue";
import Table   from "@/components/table/Table.vue";
import colunas from "./tabela";
import acoes   from "./acoes";
import service from "@/services/Service";

export default {
    components: { Page, Table },

    data() {
        return {
            columns: colunas,
            actions: acoes( this ),
            service: service.fichas,
        };
    },

    methods: {
        visualizar( event ){
            this.$router.push( `/fichas/${event.data.id}` );
        },

        imprimir( selected ){
            selected.forEach( ficha => {
                service.fichas.imprimir( ficha.id );
            } );
        },
    },
}
</script>