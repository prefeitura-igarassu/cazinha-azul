<template>

<Page :title="title">
    <div class="grid">
        <div class="col-12 lg:col-6">
            <div class="formgrid grid">
                <Field    col="col-12" id="nome"      campo="Nome"      v-model:valor="grupo.nome"      :error="errors.nome"    />
                <Textarea col="col-12" id="descricao" campo="Descrição" v-model:valor="grupo.descricao" :error="errors.descricao" />
            </div>

            <div class="flex gap-3">
                <Button label="Salvar"   :loading="loading"    @click="salvar" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </div>

        <div class="col-12 lg:col-6 mb-6">
            <Permissoes :grupo_id="this.id" @setPermissoes="setPermissoes"></Permissoes>
        </div>
    </div>
</Page>

</template>

<script>
import Page from "@/components/Page.vue";
import Field from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";
import CpfCnpj from "@/components/forms/CpfCnpj.vue";
import Permissoes from "@/pages/usuarios_permissoes/Permissoes.vue";

import Button from "primevue/button";
import service from "@/services/Service";

export default {
    components: { Page , Field, Textarea, CpfCnpj , Permissoes, Button },
    props: [ "id" ] ,

    data() {
        return {
            grupo : {},
            loading: false,
            errors: {},
            permissoes: [],
        };
    },

    watch: {
        id(){
            this.init();
        },

        grupo( value ){
            //console.log( "grupo mudou" , value );
        }
    },

    computed: {
        title(){
            return this.id ? `"Editar - Grupo Nº ${this.id}` : "Adicionar - Grupo";
        }
    },

    methods: {
        salvar(){
            this.loading = true;

            let request = this.id 
                ? service.grupos.update( this.grupo )
                : service.grupos.insert( this.grupo );

            request.then( this.salvarPermissoes );
            
            request.then( r => {
                this.loading = false;
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`O grupo foi salvo com sucesso!`, life: 3000}) , 10 );

                 this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        salvarPermissoes( r ){
            return Promise.all( Object.keys( this.permissoes ).map( nome => {
                let permissao = this.permissoes[ nome ];
                permissao.grupo_id = r.id;

                return permissao.id 
                    ? service.permissoes.update( permissao )
                    : service.permissoes.insert( permissao );
            }) );
        },

        init(){
            service.grupos.get( this.id ).then( r => {
                this.grupo = r;
            }).catch( r => console.log( r ) );
        },

        setPermissoes( permissoes ){
            this.permissoes = permissoes;
            //console.log( permissoes );
        },

        voltar(){
            this.$router.push( "/usuarios" );
        }
    },

    mounted(){
        if( this.id ){
            this.init();
        }
    }
}
</script>