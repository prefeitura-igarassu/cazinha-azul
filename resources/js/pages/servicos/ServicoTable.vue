<template>
    <Page title="Serviços">
        
        <Table 
            tableId="serviços"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasGlobalFilter="true"
            empty="Nenhum serviço foi encontrado."
            selectionMode="multiple"
            @visualizar="visualizar"
            :hasChips="false"
            :searchInline="[]"
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
            service: service.servicos,
        };
    },

    methods: {
        visualizar( event ){
            this.$router.push( `/servicos/${event.data.id}` );
        },
    },
}
</script>