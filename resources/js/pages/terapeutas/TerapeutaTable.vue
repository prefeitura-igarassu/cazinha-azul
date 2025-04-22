<template>
    <Page title="Terapeutas">
        
        <Table 
            tableId="terapeutas"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasGlobalFilter="true"
            empty="Nenhum terapeuta foi encontrado."
            selectionMode="multiple"
            @visualizar="visualizar"
            :hasChips="false"
            :with="['servico', 'unidade']"
            :searchInline="[]"
            ref="table"
        ></Table>
    </Page>
</template>

<script>
import Page  from "@/components/Page.vue";
import Table from "@/components/table/Table.vue";
import colunas from "./tabela";
import acoes   from "./acoes";
import service from "@/services/Service";

export default {
    components: { Page, Table },

    data() {
        return {
            columns: colunas,
            actions: acoes( this ),
            service: service.terapeutas,
        };
    },

    methods: {
        visualizar( event ){
            this.$router.push( `/terapeutas/${event.data.id}` );
        },
    },
}
</script>