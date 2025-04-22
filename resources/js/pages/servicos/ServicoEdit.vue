<template>

    <Page :title="title" :errors="errors">
        <form @submit.prevent="salvar">
            <div class="formgrid grid mt-4">
                <Field  col="sm:col-3 col-12"  id="nome"        campo="Nome"        v-model:valor="servico.nome"       :error="errors.nome"      />
                <Field  col="sm:col-9 col-12"  id="descricao"   campo="Descrição"   v-model:valor="servico.descricao"  :error="errors.descricao" />
            </div>
        
            <div class="flex gap-3 mt-3">
                <Button label="Salvar" :loading="loading"    @click.prevent="salvar" type="submit" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </form>
    </Page>
    
</template>

<script>
import Page    from "@/components/Page.vue";
import Field   from "@/components/forms/Field.vue";
import Editor  from 'primevue/editor';
import Button  from "primevue/button";
import Tag     from "primevue/tag";
import Message from "primevue/message";
import service from "@/services/Service";

export default {
    components: { Page, Field, Editor, Button, Tag, Message },
    props: [ "id" ],

    data() {
        return {
            servico: {
                nome: null,
                descricao: null,
            },

            loading: false,
            errors : {},
        };
    },

    watch: {
        id(){
            this.init();
        },
    },

    computed: {
        title(){
            return this.id ? `Serviço Nº ${this.id}` : "Serviço";
        },
    },

    methods: {
        init(){
            this.servico = {
                nome: null,
                descricao: null,
            };

            if( this.id ){
                service.servicos.get( this.id ).then( r => {
                    this.servico = r;
                }).catch( r => console.log( r ) );
            }
        },

        salvar(){
            this.loading = true;

            let request = this.id 
                ? service.servicos.update( this.servico )
                : service.servicos.insert( this.servico );
            
            request.then( r =>{
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`servico foi salva com sucesso!`, life: 3000}) , 10 );

                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        voltar(){
            this.$router.push( "/servicos" );
        },
    },

    mounted(){
        this.init();
    }
}
</script>