<template>
    <div>
        <Table 
            tableId="andamentos"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            empty="Nenhum andamento foi encontrado."
            selectionMode="multiple"
            @visualizar="visualizar"
            @dialog="setDialog"
            :filters="filters"
            :hasChips="false"
            :hasGlobalFilter="false"
            :searchInline="[]"
            :with="['usuario']"
            ref="tabela"
        ></Table>

        <AndamentoDialog v-if="dialogVisible" v-model:visible="dialogVisible" :andamento="andamento" @cancelar="dialogVisible = false"  @salvou="pesquisar"></AndamentoDialog>
    </div>
</template>

<script>
import Page    from "@/components/Page.vue";
import Table   from "@/components/table/Table.vue";
import colunas from "./tabela.js";
import acoes   from "./acoes.js";
import service from "@/services/Service";
import AndamentoDialog from "./AndamentoDialog.vue";
import dayjs   from "dayjs";

export default {
    components: { Page, Table, AndamentoDialog },
    props: [ "ficha_id" ],

    data() {
        return {
            columns: colunas,
            actions: acoes( this ),
            service: service.fichas_andamentos,
            
            andamento: {},
            dialogVisible: false,
        };
    },

    computed: {
        filters(){
            return { 
                ficha_id: this.ficha_id 
            };
        },
    },

    methods: {
        visualizar( event ){
            this.setDialog( event.data );
        },

        setDialog( andamento ){
            this.dialogVisible = true;
            this.andamento = andamento;
        },

        setDialogNovo(){
            this.dialogVisible = true;

            this.andamento = { 
                ficha_id: this.ficha_id,
                ocorrido_em: dayjs().format( 'YYYY-MM-DD HH:mm:ss' ),
            };
        },

        pesquisar(){
            this.$refs.tabela.pesquisar();
        },
    },
}
</script>