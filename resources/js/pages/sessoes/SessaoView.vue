<template>

    <Page title="Sessão Atual" :errors="errors">
        <form @submit.prevent="salvar">
            <div class="formgrid grid mt-4">
                <Select   col="col-3"   id="status"       campo="Situação"     v-model:valor="sessao.status"       :error="errors.status"    :options="situacoes" />
                <Field    col="col-3"   id="ficha"        campo="Ficha"        :valor="sessao.ficha?.nome"         :readonly="true" />
                <Field    col="col-3"   id="terapeuta"    campo="Terapeuta"    :valor="sessao.terapeuta?.nome"     :readonly="true" />
                <Field    col="col-3"   id="serviço"      campo="Serviço"      :valor="sessao.servico?.nome"       :readonly="true" />
                
                <Editor   col="col-12"  id="evolucao"     campo="Evolução"     v-model:valor="sessao.evolucao"     :error="errors.evolucao"   v-if="sessao.status == 1" />
                <Textarea col="col-12"  id="observacao"   campo="Observações"  v-model:valor="sessao.observacao"   :error="errors.observacao" v-if="sessao.status > 1"  />
            </div>
        
            <div class="flex gap-3">
                <Button label="Salvar" :loading="loading"    @click.prevent="salvar" type="submit" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </form>
    </Page>
    
    <SessaoTimeline v-if="sessao.id" :sessao="sessao" />

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
import SessaoTimeline from "./SessaoTimeline.vue";

export default {
    components: { Page, Field, Editor, Button, Textarea, Errors, Select, SessaoTimeline },
    props: [ "id" ],

    data() {
        return {
            sessao: {},
            loading: false,
            errors : {},

            situacoes,
        };
    },

    watch: {
        id(){
            this.init();
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
            this.$router.push( "/sessoes" );
        }
    },

    mounted(){
        this.init();
    }
}
</script>