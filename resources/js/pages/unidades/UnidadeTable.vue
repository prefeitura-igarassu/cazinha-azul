<template>
    <Page title="Unidades">
        
        <Table 
            tableId="unidades"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasGlobalFilter="true"
            empty="Nenhuma unidade foi encontrada."
            selectionMode="multiple"
            @visualizar="visualizar"
            :hasChips="false"
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
            service: service.unidades,
        };
    },

    methods: {
        visualizar( event ){
            this.$router.push( `/unidades/${event.data.id}` );
        },

        imprimir( selected ){
            selected.forEach( empresa => {
                service.unidades.imprimir( empresa.id );
            } );
        },
    },
}
</script>