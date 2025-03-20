<template>

<Page title="Adicionar - Usuário">
    <div class="grid">
        <div class="col-2">
            <FileUpload mode="basic" name="imagem" accept="image/*" :multiple="false" :customUpload="true" @uploader="setFile" :auto="true" class="w-full p-button-sm" />
            <img v-if="usuario.imagem" :src="usuario.imagem" class="border-circle w-full mt-3" />
        </div>

        <div class="col-10">
            <div class="formgrid grid">
                <Field  col="col-12" id="nome"     campo="Nome"       v-model:valor="usuario.nome"     :error="errors.nome" />
                <Field  col="col-3"  id="email"    campo="E-mail"     v-model:valor="usuario.email"    :error="errors.email"    type="email" />
                <Field  col="col-3"  id="password" campo="Senha"      v-model:valor="usuario.password" :error="errors.password" type="password" />
                <Field  col="col-3"  id="password_confirmation" campo="Confirmação da Senha" v-model:valor="usuario.password_confirmation" type="password" />

                <Field  col="col-3"  id="funcao"   campo="Função"     v-model:valor="usuario.funcao"   :error="errors.funcao"   />
                <Field  col="col-3"  id="telefone" campo="Telefone"   v-model:valor="usuario.telefone" :error="errors.telefone" />
                <Field  col="col-6"  id="endereco" campo="Endereço"   v-model:valor="usuario.endereco" :error="errors.endereco" />
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <Button label="Salvar"   :loading="loading"    @click="salvar" />
        <Button label="Cancelar" class="p-button-text" @click="voltar" />
    </div>
</Page>

</template>

<script>
import Page from "@/components/Page.vue";
import Field from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";

import Button from "primevue/button";

import service from "@/services/Service";

export default {
    components: { Page , Field, Textarea, Button },
    
    data() {
        return {
            usuario: {},
            loading: false,
            errors: {},
        };
    },

    methods: {
        setFile( event ){
            let reader = new FileReader();

            reader.onload = ( event ) => {
                this.usuario.imagem = event.target.result;
            };

            reader.readAsDataURL( event.files[0] );
        },

        salvar(){
            this.loading = true;

            let request = service.minhaConta.insert( this.usuario );
            
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
        },

        voltar(){
            this.$router.push( "/" );
        }
    },
}
</script>