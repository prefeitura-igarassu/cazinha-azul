<template>
    <div>
        <div>
            <h5>Grupos de Usuários</h5>
        </div>

        <Table 
            tableId="grupos"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasGlobalFilter="true"
            empty="Nenhum grupo foi encontrado."
            selectionMode="multiple"
            @visualizar="visualizar"
        ></Table>
    </div>
</template>

<script>
import Page from "@/components/Page.vue";
import Table from "@/components/table/Table.vue";
import colunas from "./tabela";
import acoes from "./acoes";
import service from "@/services/Service";

export default {
    components: { Page , Table },

    data() {
        return {
            columns: colunas ,
            actions: acoes( this ),
            service: service.grupos,
        };
    } ,

    methods: {
        visualizar( event ){
            this.$router.push( `/usuarios_grupos/${event.data.id}` );
        },
    },
}
</script>