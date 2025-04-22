<template>

    <div>Seja bem-vindo, <span class="block text-4xl font-bold">{{ usuario.nome }}</span></div>

    <div class="rounded-lg p-4 bg-white mt-4">

        <Tabs v-model:value="tab" :lazy="true" scrollable>
            <TabList>
                <Tab value="0">Agenda do dia</Tab>
                <Tab value="1">Fichas</Tab>
            </TabList>

            <TabPanels>
                <TabPanel value="0">
                    <DataView :value="sessoes">
                        <template #list="slotProps">
                            <div class="flex flex-col gap-2">
                                <Button v-for="(sessao, index) in slotProps.items" :key="index" outlined @click="editar( sessao )">
                                    <div class="w-full flex justify-content-between gap-2 flex-wrap">
                                        <div class="flex gap-2">
                                            <Tag  icon="pi pi-clock"                 severity="contrast">{{ horarioFormat( sessao.agendado_para ) }}</Tag>
                                            <Tag :icon="getSituacao( sessao ).icon" :severity="getSituacao( sessao ).severity">{{ getSituacao( sessao ).label }}</Tag>
                                        </div>
                                        <span class="text-lg font-medium">{{ sessao.ficha_nome }}</span>
                                    </div>
                                </Button>
                            </div>
                        </template>

                        <template #empty>
                            <div class=" flex align-items-center justify-content-center text-2xl m-6">Nenhuma sessão foi agendada para hoje!</div>
                        </template>

                        <template #loading>
                            <div class=" flex align-items-center justify-content-center text-2xl m-6">Carregando...</div>
                        </template>
                    </DataView>
                </TabPanel>

                <TabPanel value="1">
                    
                    <div class="flex flex-wrap gap-5 mt-3 mb-5">
                        <template v-if="loading">
                            <div class=" flex align-items-center justify-content-center text-2xl m-6">Carregando...</div>
                        </template>

                        <div v-else v-for="solicitacao in solicitacoes" :key="solicitacao.id" class="p-2 border-2 rounded-lg border-white hover:border-gray-50">
                            <div class="flex">
                                <Avatar icon="pi pi-calendar-clock" class="mr-2 cursor-pointer" size="xlarge" @click="$router.push( `/fichas/${solicitacao.ficha_id}` )" />

                                <div>
                                    <div><b>{{ solicitacao.ficha_nome }}</b></div>
                                    <small class="block">{{ solicitacao.servico_nome }}</small>
                                    <small class="block">{{ solicitacao.terapeuta_nome }}</small>
                                    <small class="block">{{ diaFormat( solicitacao.dia ) }} às {{ solicitacao.horario }}h</small>

                                    <div class="flex gap-2">
                                        <Button icon="pi pi-eye"  size="small" text label="Ficha"      @click="$router.push( `/fichas/${solicitacao.ficha_id}` )"></Button>
                                        <Button icon="pi pi-plus" size="small" text label="Devolutiva" @click="$router.push( `/devolutivas/adicionar?ficha_id=${solicitacao.ficha_id}&terapeuta_id=${solicitacao.terapeuta_id}` )"></Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>
</template>

<script>
import service   from "@/services/Service";
import Select    from "@/components/forms/Select.vue";
import situacoes from "../sessoes/situacoes";
import dias      from "../fichas_servicos/dias";

import dayjs     from "dayjs";
import Message   from "primevue/message";
import Tabs      from 'primevue/tabs';
import TabList   from 'primevue/tablist';
import Tab       from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel  from 'primevue/tabpanel';
import DataView  from 'primevue/dataview';
import Button    from 'primevue/button';
import Tag       from 'primevue/tag';
import Avatar    from "primevue/avatar";

export default {
    components: { 
        Message, Select,
        Tabs, TabList, Tab, TabPanels, TabPanel,
        DataView, Button, Tag, Avatar
    },
    
    data() {
        return {
            sessoes: [],
            solicitacoes: [],
            loading: false,
            tab: "0",
        };
    },

    watch: {
        tab(){
            this.pesquisar();
        },
    },

    computed: {
        usuario(){
            return service.usuarioLogado;
        },
    },

    methods: {
        init(){
            let params = {
                "terapeuta.usuario_id": service.usuarioLogado.id,       // selecionar os que estão associados a este usuário
                "agendado_para": {
                    "gt": dayjs().format( "YYYY-MM-DD 00:00:00" ),
                    "lt": dayjs().format( "YYYY-MM-DD 23:59:59" ),
                },

                "orderBy": "agendado_para,asc"
            };

            service.sessoes.search( params ).then( sessoes => {
                this.sessoes = sessoes;
            });
        },

        horarioFormat( date ){
            return date ? dayjs( date ).format( "HH:mm" ) : null;
        },

        getSituacao( sessao ){
            return situacoes[ sessao.status ];
        },

        editar( sessao ){
            this.$router.push( `/sessoes/${sessao.id}` );
        },


        // -------------- //
        // -------------- //
        // -------------- //

        pesquisarPorFichas(){
            this.solicitacoes = [];
            
            const params = {
                "terapeuta.usuario_id": service.usuarioLogado.id,       // selecionar os que estão associados a este usuário
                "dia": { "ne": null },
                "orderBy": "ficha_nome,asc",
            };

            this.loading = true;
            service.fichas_servicos.search( params ).then( solicitacoes => {
                this.solicitacoes = solicitacoes;
                this.loading = false;
            });
        },

        diaFormat( dia ){
            return dia == null 
                ? "" 
                : dias[ dia ].label;
        },

        dateFormat( dia ){
            return dayjs( dia ).format( "DD/MM/YYYY [às] HH:mm" );
        },

        pesquisar()
        {
            switch( this.tab )
            {
                case "0": this.init(); break;
                case "1": this.pesquisarPorFichas(); break;
            }
        }
    },

    mounted(){
        this.init();
    }
}
</script>