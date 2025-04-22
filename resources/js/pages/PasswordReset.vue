<template>

    <div class="flex justify-content-center align-items-center" style="min-height: 100vh">
        <Page title="Alterar a Senha" style="width: 50vw">
            <Message v-if="error.message" severity="error" :closable="false">{{ error.message }}</Message>
    
            <div class="formgrid grid">
                <Field  col="col-6"  id="password"              campo="Senha Nova"           v-model:valor="password"              type="password">
                    <Password class="block w-full" toogleMask promptLabel="Digite a senha" weakLabel="Senha fraca!" mediumLabel="Senha média!" strongLabel="Senha Forte!" v-model="password">
                        <template #header>
                            <h6>Digite uma senha forte</h6>
                        </template>
                        <template #footer>
                            <Divider />
                            <p class="mt-2">Sugestões</p>
                            <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                                <li>Pelo menos, 1 letra minuscula</li>
                                <li>Pelo menos, 1 letra maiuscula</li>
                                <li>Pelo menos, 1 número</li>
                                <li>No mínimo, 8 caracteres</li>
                            </ul>
                        </template>
                    </Password>
                </Field>

                <Field  col="col-6"  id="password_confirmation" campo="Confirmação da Senha" v-model:valor="password_confirmation" type="password">
                    <Password class="block w-full" toogleMask promptLabel="Digite a senha de confirmação" weakLabel="Senha fraca!" mediumLabel="Senha média!" strongLabel="Senha Forte!" v-model="password_confirmation">
                        <template #header>
                            <h6>Digite uma senha forte</h6>
                        </template>
                        <template #footer>
                            <Divider />
                            <p class="mt-2">Sugestões</p>
                            <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                                <li>Pelo menos, 1 letra minuscula</li>
                                <li>Pelo menos, 1 letra maiuscula</li>
                                <li>Pelo menos, 1 número</li>
                                <li>No mínimo, 8 caracteres</li>
                            </ul>
                        </template>
                    </Password>
                </Field>
            </div>
    
            <div class="flex gap-3">
                <Button label="Salvar"   :loading="loading"    @click="salvar" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </Page>
    </div>
    
</template>
    
<script>
import Page from "@/components/Page.vue";
import Field from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";
import Button from "primevue/button";
import Message from "primevue/message";
import Password from "primevue/password";

import service from "@/services/Service";

export default {
    components: { Page , Field, Textarea, Button, Message, Password },
    
    data() {
        return {
            token: null,
            email: null,
            password: null,
            password_confirmation: null,
            loading: false,

            error: {},
        };
    },

    methods: {
        salvar(){
            this.loading = true;

            let request = service.minhaConta.resetPassword(
                this.token,
                this.email,
                this.password,
                this.password_confirmation,
            );
            
            request.then( r =>{
                this.loading = false;
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`A senha foi alterada com sucesso!`, life: 3000}) , 10 );
                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.error  = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.error = err.data ?? {};
            });
        },

        voltar(){
            this.$router.push( "/" );
        },

        getQueryParams( url ) {
            const paramArr = url.slice(url.indexOf('?') + 1).split('&');
            const params = {};
            
            paramArr.map( param => {
                const [key, val] = param.split('=');
                params[key] = decodeURIComponent(val);
            });

            return params;
        }
    },

    mounted(){
        const params = this.getQueryParams( window.location.toString() );
        this.token = params.token;
        this.email = params.email;
    }
}
</script>