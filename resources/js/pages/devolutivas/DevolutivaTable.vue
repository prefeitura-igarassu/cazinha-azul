<template>
    <div v-if="ficha_id">
        <Table 
            tableId="devolutivas"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasGlobalFilter="true"
            :filters="filters"
            empty="Nenhuma devolutiva foi encontrada."
            selectionMode="multiple"
            @visualizar="visualizar"
            :with="['usuario','ficha','servico','terapeuta']"
            ref="table"
        ></Table>
    </div>

    <Page v-else title="Devolutivas">
        <div class="grid mt-2">
            <Field   col="col-12 sm:col-3"  id="feito_em"   campo="Feito em"   v-model:valor="params.feito_em"      type="date"           @changed="pesquisar" />
            <Select  col="col-12 sm:col-3"  id="ficha"      campo="Ficha"      v-model:valor="params.ficha_id"      :options="fichas"     @changed="pesquisar" />
            <Select  col="col-12 sm:col-3"  id="serviço"    campo="Serviço"    v-model:valor="params.servico_id"    :options="servicos"   @changed="pesquisar" />
            <Select  col="col-12 sm:col-3"  id="terapeuta"  campo="Terapeuta"  v-model:valor="params.terapeuta_id"  :options="terapeutas" @changed="pesquisar" />
        </div>

        <Table 
            tableId="devolutivas"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasGlobalFilter="true"
            :filters="filters"
            empty="Nenhuma devolutiva foi encontrada."
            selectionMode="multiple"
            @visualizar="visualizar"
            :with="['usuario','ficha','servico','terapeuta']"
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

import Select   from "@/components/forms/Select.vue";
import Field    from "@/components/forms/Field.vue";

export default {
    components: { Page, Table, Select, Field },
    props: [ "ficha_id" ],

    data() {
        return {
            columns: colunas,
            actions: acoes( this ),
            service: service.devolutivas,

            params : {},
            fichas : [], servicos: [], terapeutas: [],
        };
    },

    watch: {
        ficha_id( value ){
            this.params.ficha_id = value;
        },
    },

    computed: {
        filters(){
            const p = {};

            if( this.params.feito_em )
            {
                p.feito_em = {
                    gt: this.params.feito_em + " 00:00:00",
                    lt: this.params.feito_em + " 23:59:59",
                };
            }

            if( this.params.servico_id   ) p.servico_id   = this.params.servico_id;
            if( this.params.ficha_id     ) p.ficha_id     = this.params.ficha_id;
            if( this.params.terapeuta_id ) p.terapeuta_id = this.params.terapeuta_id;

            return p;
        },
    },

    methods: {
        init(){
            service.fichas    .getOptions( { setNullLabel: "Todos as fichas"     } ).then( r => this.fichas     = r );
            service.servicos  .getOptions( { setNullLabel: "Todos os serviços"   } ).then( r => this.servicos   = r );
            service.terapeutas.getOptions( { setNullLabel: "Todos os terapeutas" } ).then( r => this.terapeutas = r );
        },

        visualizar( event ){
            this.$router.push( `/devolutivas/${event.data.id}` );
        },

        imprimir( selected ){
            selected.forEach( devolutiva => {
                service.devolutivas.imprimir( devolutiva.id );
            } );
        },

        pesquisar(){
            this.$refs.table.setFilters( {} );
            this.$refs.table.pesquisar();
        }
    },

    mounted(){
        this.params.ficha_id = this.ficha_id;
        this.init();
    }
}
</script>