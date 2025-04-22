<template>

<Dialog :visible="visible" :modal="true" @hide="cancelar" @update:visible="updatedVisible">
    <template #header>
		<h3>Trocar Senha</h3>
	</template>

	<div class="formgrid grid">
        <Field  col="col-6"  id="nome"     campo="Nome"       v-model:valor="usuario.nome"      :error="errors.nome" />
        <Field  col="col-6"  id="email"    campo="E-mail"     v-model:valor="usuario.email"     :error="errors.email"    type="email" />
        <Field  col="col-6"  id="telefone" campo="Telefone"   v-model:valor="usuario.telefone"  :error="errors.telefone" />
        <Field  col="col-6"  id="endereco" campo="Endereço"   v-model:valor="usuario.endereco"  :error="errors.endereco" />
    </div>

	<template #footer>
		<Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="cancelar" />
        <Button label="Alterar"  icon="pi pi-check" @click="salvar"      :loading="loading" />
	</template>
</Dialog>

</template>

<script>
import Field from "@/components/forms/Field.vue";
import service from "@/services/Service";

import Button from "primevue/button";
import Dialog from "primevue/dialog";

export default {
    components: { Field , Button, Dialog, },
    props: {
        visible: { type: Boolean, required: true }
    },

    data() {
        return {
            usuario: {},
            errors : {},
            loading: false,
        };
    },

    watch: {
        visible( newValue ){
            this.init();
        },
    },

    methods:{
        init(){
            this.usuario = service.usuarioLogado;
            this.errors  = {};
        },

        salvar(){
            this.loading = true;

            service.minhaConta.update( this.usuario ).then( r =>{
                this.loading = false;
                service.usuarioLogado = this.usuario;
                
                setTimeout( () => this.$toast.add({severity:'info', summary:'Dados alterados', detail:`Os dados pessoais foram com sucesso!`, life: 3000}) , 10 );

                this.$emit( "update:visible" , false );
            }).catch( err =>{
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        cancelar(){
            this.init();
            this.$emit( "update:visible" , false );
        },

        updatedVisible( value ){
            this.$emit( 'update:visible' , value );
        },
    },

    mounted(){
        this.init();
    }
}
</script>