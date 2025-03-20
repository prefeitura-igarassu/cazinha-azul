<template>

<Page :title="title">
    <form @submit.prevent="salvar">
        <div class="formgrid grid mt-4">
            <Field  col="col-12 sm:col-3"  id="nome"     campo="Nome"       v-model:valor="usuario.nome"     :error="errors.nome" />
            <Field  col="col-12 sm:col-3"  id="funcao"   campo="Função"     v-model:valor="usuario.funcao"   :error="errors.funcao"   :options="funcoes" />
            <Select col="col-12 sm:col-3"  id="grupo_id" campo="Grupo"      v-model:valor="usuario.grupo_id" :error="errors.grupo_id" :options="grupos"    :required="true"                 v-if="isAdmin"></Select>
            <div class="col-12 sm:col-3"></div>
            
            <Field  col="col-12 sm:col-3"  id="email"    campo="E-mail"     v-model:valor="usuario.email"    :error="errors.email"    type="email" />
            <Field  col="col-12 sm:col-3"  id="senha"    campo="Senha"      v-model:valor="usuario.password" :error="errors.password" type="password" />
        </div>

        <div class="flex gap-3">
            <Button label="Salvar"   :loading="loading"    @click.prevent="salvar" type="submit" />
            <Button label="Cancelar" class="p-button-text" @click="voltar" />
        </div>
    </form>
</Page>

</template>

<script>
import funcoes from "./funcoes.json"
import Page from "../../components/Page.vue";
import Field from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";
import Select from "@/components/forms/Select.vue";

import nao_sim from "@/components/data/nao_sim.json";
import service from "@/services/Service";

import Button from "primevue/button";

export default {
    components: { Page , Field, Textarea , Select, Button },
    props: [ "id" ] ,

    data() {
        return {
            usuario: {},
            loading: false,
            errors: {},

            grupos: [],
            funcoes: funcoes,
            nao_sim: nao_sim,
        };
    },

    computed: {
        title(){
            return this.id ? `"Editar - Usuário Nº ${this.id}` : "Adicionar - Usuário";
        },

        isAdmin(){
            return service.usuarioLogado?.isAdmin;
        }
    },

    methods: {
        salvar(){
            this.loading = true;
            
            let request = this.id 
                ? service.usuarios.update( this.usuario )
                : service.usuarios.insert( this.usuario );
            
            request.then( r =>{
                this.loading = false;
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`Usuário foi salvo com sucesso!`, life: 3000}) , 10 );
                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        } ,

        voltar(){
            this.$router.push( "/usuarios" );
        }
    },

    mounted(){
        if( this.id ){
            service.usuarios.get( this.id )
                .then( r => this.usuario = r )
                .catch( r => console.log( r ) );
        }

        service.grupos.getOptions( false ).then( r => this.grupos = r );
    }
}
</script>