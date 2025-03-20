<template>

<div class="grid">

    <div class="col-12 sm:col-4">
        <b>Atualização dos dados</b>
        <div>Atualizar os dados pessoais do usuário.</div>
    </div>

    <div class="card col-12 sm:col-8">
        <form class="formgrid grid" @submit.prevent="salvarDados">
            <Field  col="col-12" id="nome"       campo="Nome"       v-model:valor="usuario.nome"     :error="errors.nome" />

            <Field  col="col-12 sm:col-4"  id="email"      campo="E-mail"     v-model:valor="usuario.email"    :error="errors.email"    type="email" />
            <Field  col="col-12 sm:col-4"  id="funcao"     campo="Função"     v-model:valor="usuario.funcao"   :error="errors.funcao"   />
            <Select col="col-12 sm:col-4"  id="grupo_id"   campo="Grupo"      v-model:valor="usuario.grupo_id" :error="errors.grupo_id"    :options="grupos"    v-if="isAdmin"></Select>
            
            <div class="col-12">
                <div class="flex gap-3">
                    <Button label="Salvar"   :loading="loading"    @click.prevent="salvarDados" type="submit" />
                    <Button label="Cancelar" class="p-button-text" @click="voltar" />
                </div>
            </div>
        </form>
    </div>

    <!-- -->
    <!-- -->
    <!-- -->

    <div class="col-12 sm:col-4 mt-8 sm:mt-0">
        <b>Atualização da Senha</b>
        <div>Deve-se ter cuidado com esta função, porque ela será .</div>
    </div>

    <div class="card col-12 sm:col-8 formgrid grid mt-4">
        <Field  col="col-12 sm:col-6"  id="password"              campo="Senha"                v-model:valor="senha.password" :error="errors.password" type="password" />
        <Field  col="col-12 sm:col-6"  id="password_confirmation" campo="Confirmação da Senha" v-model:valor="senha.password_confirmation"             type="password" />

        <div class="col-12">
            <div class="flex gap-3">
                <Button label="Salvar"   :loading="loading"    @click="salvarSenha" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </div>
    </div>

    </div>

</template>

<script>
import funcoes from "./funcoes.json"
import Page from "../../components/Page.vue";
import Field from "@/components/forms/Field.vue";
import Select from "@/components/forms/Select.vue";
import Textarea from "@/components/forms/Textarea.vue";

import nao_sim from "@/components/data/nao_sim.json";
import service from "@/services/Service";

import Button from "primevue/button";

export default {
    components: { Page , Field, Textarea, Select, Button },
    props: [ "id" ] ,

    data() {
        return {
            usuario: {},
            senha:{
                password: null,
                password_confirmation: null,
            },
            loading: false,
            errors: {},
            grupos: [],
            funcoes: funcoes,
            nao_sim: nao_sim,
        };
    },

    computed: {
        title(){
            return `"Editar - Usuário Nº ${this.id}`;
        },

        isAdmin(){
            return service.usuarioLogado?.isAdmin;
        }
    },

    methods: {
        setFile( event ){
            let reader = new FileReader();

            reader.onload = ( event ) => {
                this.upload( event.target.result );
            };

            reader.readAsDataURL( event.files[0] );
        },

        upload( imagem ){
            service.usuarios.upload( this.id , imagem ).then( () => {
                setTimeout( () => this.$toast.add({severity:'info', summary:'Enviado', detail:`A imgem foi atualizada!`, life: 3000}) , 10 );
            }).catch( err => {
                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );
            });
        },

        salvarDados(){
            this.loading = true;
            this.errors = {};

            service.usuarios.update( this.usuario ).then( r =>{
                this.loading = false;
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`Usuário foi salvo com sucesso!`, life: 3000}) , 10 );
                this.voltar();
            }).catch( err => {
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        salvarSenha(){
            this.loading = true;
            this.errors = {};
            
            service.usuarios.alterarSenha( this.usuario.id , this.senha.password , this.senha.password_confirmation ).then( r =>{
                this.loading = false;
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`Usuário foi salvo com sucesso!`, life: 3000}) , 10 );
                this.voltar();
            }).catch( err => {
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        voltar(){
            this.$router.push( "/usuarios" );
        }
    },

    mounted(){
        service.grupos   .getOptions( false ).then( r => this.grupos = r    );
        
        if( this.id ){
            service.usuarios.get( this.id )
                .then( r => this.usuario = r )
                .catch( r => console.log( r ) );
        }
    }
}
</script>