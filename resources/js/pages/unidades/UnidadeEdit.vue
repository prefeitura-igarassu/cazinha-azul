<template>

    <Page :title="title" :errors="errors">
        <form @submit.prevent="salvar">
            <div class="formgrid grid mt-4">
                <Field    col="sm:col-3 col-12" id="nome"         campo="Nome"         v-model:valor="unidade.nome"         :error="errors.nome"     />
                <Field    col="sm:col-6 col-12" id="endereco"     campo="Endereço"     v-model:valor="unidade.endereco"     :error="errors.endereco" />
                <Field    col="sm:col-3 col-12" id="telefone"     campo="Telefones"    v-model:valor="unidade.telefone"     :error="errors.telefone" />
                <Textarea col="col-12"          id="observacao"   campo="Observações"  v-model:valor="unidade.observacao"   :error="errors.observacao"></Textarea>
            </div>
        
            <div class="flex gap-3">
                <Button label="Salvar" :loading="loading"    @click.prevent="salvar" type="submit" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </form>
    </Page>
    
</template>

<script>
import Page     from "@/components/Page.vue";
import Errors   from "@/components/Errors.vue";
import Field    from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";
import Editor   from 'primevue/editor';
import Button   from "primevue/button";
import service  from "@/services/Service";

export default {
    components: { Page, Field, Editor, Button, Textarea, Errors },
    props: [ "id" ],

    data() {
        return {
            unidade: {},
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
            return this.id ? `Unidade Nº ${this.id}` : "Unidade";
        },
    },

    methods: {
        init(){
            this.unidade = {};

            if( this.id ){
                service.unidades.get( this.id ).then( r => {
                    this.unidade = r;
                }).catch( r => console.log( r ) );
            }
        },

        salvar(){
            this.loading = true;

            let request = this.id 
                ? service.unidades.update( this.unidade )
                : service.unidades.insert( this.unidade );
            
            request.then( r =>{
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`unidade foi salva com sucesso!`, life: 3000}) , 10 );

                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        voltar(){
            this.$router.push( "/unidades" );
        }
    },

    mounted(){
        this.init();
    }
}
</script>