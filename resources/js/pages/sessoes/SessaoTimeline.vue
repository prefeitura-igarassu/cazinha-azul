<template>

<div class="mt-7 bg-white rounded-lg pt-4">
    <div class="ml-4 text-2xl font-medium">Sessões Anteriores</div>

    <div v-if="loading" class="m-6 text-center text-5xl font-medium">
        <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
        Carregando...
    </div>

    <div v-else-if="resultado.data.length == 0" class="m-6 text-center text-5xl font-medium">
        Nenhum sessão anterior foi encontrada!
    </div>

    <Timeline v-else :value="resultado.data" align="alternate">
        <template #content="slotProps">
            <Card class="mt-4">
                <template #title>
                    {{ dateFormat( slotProps.item.agendado_para ) }}
                </template>
                
                <template #subtitle>
                    {{ situacaoFormat( slotProps.item.status ) }}
                </template>

                <template #content>
                    <p v-if="slotProps.item.evolucao" v-html="slotProps.item.evolucao"></p>

                    <p v-if="slotProps.item.observacao">
                        <b class="block">Observação:</b>
                        <div>{{ slotProps.item.observacao }}</div>
                    </p>
                </template>
            </Card>
        </template>
    </Timeline>

    <Paginator 
        class="mt-4"
        :rows="rows" 
        :totalRecords="resultado.total" 
        :rowsPerPageOptions="[3, 5, 7, 10]"
        @page="setPage"
    ></Paginator>
</div>

</template>

<script>
import service from "@/services/Service";
import Timeline  from 'primevue/timeline';
import Paginator from 'primevue/paginator';
import Card from 'primevue/card';
import dayjs from "dayjs";
import situacoes from "./situacoes";

export default {
    components: { Timeline, Paginator, Card },
    props: [ "sessao" ] ,

    data() {
        return {
            resultado: {
                data: [],
                total: 0,
            },

            page: 0,
            rows: 3,
            loading: false,
        };
    },

    watch: {
        sessao(){
            this.init();
        },
    },

    methods: {
        init(){
            console.log( "sessão" , this.sessao );

            const p = {
                ficha_id     : this.sessao.ficha_id,        // pegar dados da mesma ficha
                servico_id   : this.sessao.servico_id,      // e do mesmo serviço
                agendado_para: {                            // só tenho interesse no anteriores
                    "lt": this.sessao.agendado_para,
                },

                perPage: this.rows,
                page: this.page,
            };

            this.loading = true;

            service.sessoes.search( p ).then( r => {
                this.resultado = r;
                this.loading = false;
            });
        },

        setPage( event ){
            this.rows = event.rows;
            this.page = event.page;
            this.init();
        },

        dateFormat( data ){
            return data ? dayjs( data ).format( "DD/MM/YYYY [às] HH:mm" ) : "";
        },

        situacaoFormat( situacao ){
            return situacoes[ situacao ].label;
        },
    },

    mounted(){
        if( this.sessao ){
            this.init();
        }
    }
}
</script>