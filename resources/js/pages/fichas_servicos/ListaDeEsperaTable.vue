<template>
    <Table 
        tableId="lista-de-espera"
        :service="service" 
        :columns="columns"
        :actions="actions" 
        empty="Nenhuma ficha foi encontrada."
        selectionMode="multiple"
        @visualizar="visualizar"
        :filters="filters"
        :hasChips="false"
        :hasGlobalFilter="false"
        :searchInline="[]"
        :with="['ficha']"
        ref="tabela"
    ></Table>
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
    props: [ "servico_id" , "unidade_id" ],

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
                servico_id: this.servico_id,
                unidade_id: this.unidade_id,
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

        reposicionar(){
            let servico_id = this.servico_id;
            let unidade_id = this.unidade_id;

            service.fichas_servicos.getGeneric( `/fichas/servicos/reposicionar?servico_id=${servico_id}&unidade_id=${unidade_id}` ).then( r => {
                this.pesquisar();
            });
        },

        setDialogNovo(){
            // faz nada
        }
    },
}
</script>