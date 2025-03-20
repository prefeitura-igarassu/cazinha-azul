<template>

    <div class="flex justify-content-between align-items-center flex-wrap">
        <div class="flex text-4xl font-bold align-items-center">
            <Avatar :image="`/fichas/${id}.png`" shape="circle" class="mr-2" size="xlarge" />
            Ficha Nº {{ ficha.id }} - {{ ficha.nome }}
        </div>
        <div class="flex">
            <Actions :actions="actions" :selected="[ ficha ]" @pesquisar="voltar" />
        </div>
    </div>

    <div class="rounded-lg bg-white p-4 my-4">
        <div class="flex gap-4">
            <div class="flex flex-column flex-wrap my-4">
                <property title="Unidade"        :value="ficha.unidade?.nome"></property>
                <property title="Cadastrado em"  :value="ficha.cadastrado_em" type="datetime"></property>
                <property title="CPF Nº"         :value="ficha.cpf"           type="cpf_cnpj"></property>
                <property title="Nascido em"     :value="ficha.nascido_em"    type="date"    ></property>
                <property title="SUS Nº"         :value="ficha.sus"                          ></property>
                <property title="NIS Nº"         :value="ficha.nis"                          ></property>
                <property title="Posto de Saúde" :value="ficha.posto_saude"                  ></property>
            </div>

            <div class="flex flex-column flex-wrap my-4">
                <property title="Mãe"            :value="ficha.mae_nome"                     ></property>
                <property title="Pai"            :value="ficha.pai_nome"                     ></property>
                <property title="Responsável"    :value="ficha.responsavel"                  ></property>
                <property title="Escola"         :value="ficha.escola"                       ></property>
                <property title="Endereço"       :value="ficha.endereco"                     ></property>
                <property title="Telefone"       :value="ficha.telefone"                     ></property>
                <property title="E-mail"         :value="ficha.email"                        ></property>
            </div>
        </div>

        <property title="CID's"                 :value="cids"              type="array"     ></property>
        <property col="" title="Observação"     :value="ficha.observacao"                   ></property>
    </div>

    <FichaServicoList :ficha="ficha"></FichaServicoList>
    
    <div class="bg-white rounded-lg p-4">
        <Tabs value="0" :lazy="true">
            <TabList>
                <Tab value="0">Andamentos</Tab>
                <Tab value="1">Arquivos</Tab>
                <Tab value="2">Devolutivas</Tab>
            </TabList>

            <TabPanels>
                <TabPanel value="0">
                    <AndamentoTable :ficha_id="id"></AndamentoTable>
                </TabPanel>

                <TabPanel value="1">
                    <ArquivoTable :ficha_id="id"></ArquivoTable>
                </TabPanel>

                <TabPanel value="2">
                    <DevolutivaTable :ficha_id="id"></DevolutivaTable>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>
    
</template>
    
<script>
import View    from "@/components/views/View.vue";
import service from "@/services/Service";
import acoes   from "./acoes";
import colunas from "./tabela";
import dayjs   from "dayjs";

import Resume  from '@/components/dashboard/Resume.vue';
import Select from "@/components/forms/Select.vue";
import Field  from "@/components/forms/Field.vue";

import Property         from "./RowProperty.vue";
import AndamentoTable   from "../fichas_andamentos/AndamentoTable.vue";
import ArquivoTable     from "../fichas_arquivos/ArquivoTable.vue";
import DevolutivaTable  from "../devolutivas/DevolutivaTable.vue";
import FichaServicoList from "../fichas_servicos/FichaServicoList.vue";

import Actions from "@/components/table/Actions.vue";

import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import Image from 'primevue/image';
import Avatar from 'primevue/avatar';

export default {
    components: { 
        View, Property, Select, Actions, Field, Resume, ArquivoTable,
        Tabs, TabList, Tab, TabPanels, TabPanel, Image, Avatar, AndamentoTable,
        DevolutivaTable, FichaServicoList
    },
    props: [ "id" ] ,

    data() {
        return {
            ficha: {},
            actions: acoes( this ),
            columns: colunas,

            cids: [],
        };
    },

    watch: {
        id( value ){
            this.init();
        },
    },

    methods: {
        voltar(){
            this.$router.push( "/fichas" );
        },
        
        init(){
            service.fichas.get( this.id , { with: ['unidade' ]} ).then( ficha => {
                this.ficha = ficha;
                this.cids  = ficha.cid;

                service.fichas.getGeneric( `/cid/codigo/${ficha.cid}` ).then( cids => {
                    this.cids = cids ?? ficha.cid;
                });
            });
        },
    },

    mounted(){
        if( this.id ){
            this.init();
        }
    }
}
</script>