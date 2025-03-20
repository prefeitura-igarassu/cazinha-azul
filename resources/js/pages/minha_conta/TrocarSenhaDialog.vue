<template>

    <Dialog :visible="visible" :modal="true" @hide="cancelar" @update:visible="updatedVisible">
        <template #header>
            <h3>Trocar Senha</h3>
        </template>
    
        <div class="formgrid grid">
            <Field  col="col-12"  id="password" campo="Senha Atual" v-model:valor="current_password" :error="errors.current_password" type="password" />
            <Field  col="col-12"  id="password" campo="Senha Nova"  v-model:valor="password"         :error="errors.password"   type="password" />
            <Field  col="col-12"  id="password_confirmation" campo="Confirmação da Senha" v-model:valor="password_confirmation" type="password" />
        </div>
    
        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="cancelar" />
            <Button label="Alterar"  icon="pi pi-check" @click="salvar" />
        </template>
    </Dialog>
    
</template>
    
<script>
import Field from "@/components/forms/Field.vue";
import service from "@/services/Service";

import Button from "primevue/button";
import Dialog from "primevue/dialog";

export default {
    components: { Field, Button, Dialog },
    props: {
        visible: { type: Boolean, required: true }
    },

    data() {
        return {
            current_password: null,
            password: null,
            password_confirmation: null,
            errors: {},
        };
    },

    methods:{
        init(){
            this.current_password = null;
            this.password = null;
            this.password_confirmation = null;
            this.errors = {};
        },

        updatedVisible( value ){
            this.$emit( 'update:visible' , value );
        },

        salvar(){
            this.loading = true;

            service.minhaConta.alterarSenha( this.current_password , this.password , this.password_confirmation ).then( r =>{
                this.loading = false;
                this.init();

                setTimeout( () => this.$toast.add({severity:'info', summary:'Senha Alterada', detail:`A senha foi alterada com sucesso!`, life: 3000}) , 10 );

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
        }
    },

    mounted(){
        this.errors = {};
    }
}
</script>