<template>

    <Page :title="title" :errors="errors">
        <form @submit.prevent="salvar">
            <div class="formgrid grid mt-4">
                <Select   col="col-12 sm:col-3"   id="status"       campo="Situação"     v-model:valor="sessao.status"       :error="errors.status"    :options="situacoes" />
                <Field    col="col-12 sm:col-3"   id="ficha"        campo="Ficha"        :valor="sessao.ficha?.nome"         :readonly="true" />
                <Field    col="col-12 sm:col-3"   id="terapeuta"    campo="Terapeuta"    :valor="sessao.terapeuta?.nome"     :readonly="true" />
                <Field    col="col-12 sm:col-3"   id="serviço"      campo="Serviço"      :valor="sessao.servico?.nome"       :readonly="true" />
                
                <Editor   col="col-12"  id="evolucao"     campo="Evolução"     v-model:valor="sessao.evolucao"     :error="errors.evolucao"   v-if="sessao.status == 1" />
                <Textarea col="col-12"  id="observacao"   campo="Observações"  v-model:valor="sessao.observacao"   :error="errors.observacao" v-if="sessao.status > 1"  />
            </div>
        
            <div class="flex gap-3">
                <Button label="Salvar" :loading="loading"    @click.prevent="salvar" type="submit" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </form>
    </Page>

    <div class="bg-white rounded-lg p-4 mt-4">
        <Accordion :value="tab">
            <AccordionPanel value="0">
                <AccordionHeader>Sessões Anteriores</AccordionHeader>
                <AccordionContent><SessaoTimeline :sessao="sessao" /></AccordionContent>
            </AccordionPanel>

            <AccordionPanel value="1">
                <AccordionHeader>Ficha</AccordionHeader>
                <AccordionContent><FichaResumo :ficha_id="sessao.ficha_id" /></AccordionContent>
            </AccordionPanel>

            <AccordionPanel value="2">
                <AccordionHeader>Arquivos</AccordionHeader>
                <AccordionContent><ArquivoTable :ficha_id="sessao.ficha_id" /></AccordionContent>
            </AccordionPanel>

            <AccordionPanel value="3">
                <AccordionHeader>Serviços</AccordionHeader>
                <AccordionContent><FichaServicoList :sessao="sessao" /></AccordionContent>
            </AccordionPanel>

            <AccordionPanel value="4">
                <AccordionHeader>Devolutivas</AccordionHeader>
                <AccordionContent><DevolutivaTable :sessao="sessao" /></AccordionContent>
            </AccordionPanel>
        </Accordion>
    </div>
</template>

<script>
import Page     from "@/components/Page.vue";
import Errors   from "@/components/Errors.vue";
import Select   from "@/components/forms/Select.vue";
import Field    from "@/components/forms/Field.vue";
import Editor   from "@/components/forms/Editor.vue";
import Textarea from "@/components/forms/Textarea.vue";
import Button   from "primevue/button";
import service  from "@/services/Service";
import situacoes from "./situacoes";
import dayjs    from "dayjs";

import Tabs      from 'primevue/tabs';
import TabList   from 'primevue/tablist';
import Tab       from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel  from 'primevue/tabpanel';

import SessaoTimeline   from "./SessaoTimeline.vue";
import FichaResumo      from "../fichas/FichaResumo.vue";
import ArquivoTable     from "../fichas_arquivos/ArquivoTable.vue";
import DevolutivaTable  from "../devolutivas/DevolutivaTable.vue";
import FichaServicoList from "../fichas_servicos/FichaServicoList.vue";

import Accordion from 'primevue/accordion';
import AccordionPanel from 'primevue/accordionpanel';
import AccordionHeader from 'primevue/accordionheader';
import AccordionContent from 'primevue/accordioncontent';

export default {
    components: { 
        Page, Field, Editor, Button, Textarea, Errors, Select, SessaoTimeline,
        Tabs, TabList, Tab, TabPanels, TabPanel,
        Accordion, AccordionPanel, AccordionHeader, AccordionContent,
        FichaResumo, ArquivoTable, DevolutivaTable, FichaServicoList
    },

    props: [ "id" ],

    data() {
        return {
            sessao: {},
            loading: false,
            errors : {},

            tab: "0",
            tabs: [
                { value: "0", label: "Sessões Anteriores" },
                { value: "1", label: "Ficha"              },
                { value: "2", label: "Arquivos"           },
                { value: "3", label: "Serviços"           },
                { value: "4", label: "Devolutivas"        }
            ],

            situacoes,
        };
    },

    watch: {
        id(){
            this.init();
        },
    },

    computed: {
        title(){
            return this.sessao 
                ? "Sessão do dia " + dayjs( this.sessao.agendado_para ).format( "DD/MM/YYYY [das] HH:mm[h]" )
                : "Sessão";
        },
    },

    methods: {
        init(){
            this.sessao = {};

            if( this.id ){
                service.sessoes.get( this.id , { with: ['terapeuta','servico','ficha'] } ).then( r => {
                    this.sessao = r;
                }).catch( r => console.log( r ) );
            }
        },

        salvar(){
            this.loading = true;

            let request = this.id 
                ? service.sessoes.update( this.sessao )
                : service.sessoes.insert( this.sessao );
            
            request.then( r =>{
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`A sessão foi salva com sucesso!`, life: 3000}) , 10 );

                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        voltar(){
            this.$router.back();
        }
    },

    mounted(){
        this.init();
    }
}
</script>