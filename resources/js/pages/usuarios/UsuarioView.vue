<template>

<Page :title="usuario.nome">
    <template #menu>
        <Actions :actions="actions" :selected="[ this.usuario ]" @pesquisar="voltar" />
    </template>

    <div class="grid">
        <div class="col-2">
            <div class="mt-4">
                <b class="block">Função:</b>
                {{ usuario.funcao }}
            </div>

            <div class="mt-4">
                <b class="block">E-mail:</b>
                {{ usuario.email }}
            </div>
            
            <div class="mt-4">
                <b class="block">Telefone:</b>
                {{ usuario.telefone }}
            </div>
            
            <div class="mt-4">
                <b class="block">Endereço:</b>
                {{ usuario.endereco }}
            </div>

            <div class="mt-4">
                <b class="block">Último Acesso:</b>
                {{ dateFormat( usuario.ultimo_acesso_em ) }}
            </div>
        </div>

        <div class="col-10">
            <Transition>
                <Message v-if="token" severity="success" @close="token = null">
                    Token criado com sucesso: <b>{{ token }}</b>
                </Message>
            </Transition>

            <TabView :lazy="true">
                <TabPanel header="Permissões">
                    <Permissoes :usuario_id="this.id" @setPermissoes="setPermissoes"></Permissoes>
                    
                    <div class="flex gap-3">
                        <Button label="Salvar"   :loading="loading"    @click="salvar" />
                        <Button label="Cancelar" class="p-button-text" @click="voltar" />
                    </div>
                </TabPanel>
                
                <TabPanel header="Registros">
                    <LogTable :usuario_id="this.id" />
                </TabPanel>
            </TabView>
        </div>
    </div>
</Page>

</template>

<script>
import Page from "@/components/Page.vue";
import Permissoes from "@/pages/usuarios_permissoes/Permissoes.vue";
import LogTable from "@/pages/usuarios_logs/LogTable.vue";
import service from "@/services/Service";
import Actions from "@/components/table/Actions.vue";
import acoes from "./acoes";

import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import { Transition } from "vue";
import Message from "primevue/message";
import Button from "primevue/button";

import dayjs from "dayjs";

export default {
    components: { Page, Permissoes, LogTable, Actions, TabView, TabPanel, Transition, Message, Button },
    props: [ "id" ] ,

    data() {
        return {
            actions: acoes( this ).splice( 2 ),
            usuario: {},
            loading: false,
            permissoes: {},
            index: 0,
            token: null,
        };
    },

    methods: {
        salvar(){
            this.loading = true;

            let request = this.salvarPermissoes( this.usuario );
            
            request.then( r => {
                this.loading = false;
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`Permissões foram salvas com sucesso!`, life: 3000}) , 10 );

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
                permissao.usuario_id = r.id;

                return permissao.id 
                    ? service.permissoes.update( permissao )
                    : service.permissoes.insert( permissao );
            }) );
        },

        generateToken( usuarioId ){
            service.usuarios.generateToken( usuarioId ).then( token => {
                this.token = token;
                this.$toast.add({severity:'info' , summary:'Salvou', detail:`Token gerado com sucesso!`, life: 3000});
            }).catch( r => {
                this.$toast.add({severity:'error', summary:'Ops!'  , detail:`Ocorreu um erro e o token não foi gerado!`, life: 3000})
            });
        },

        voltar(){
            this.$router.push( "/usuarios" );
        },

        setPermissoes( permissoes ){
            this.permissoes = permissoes;
        },

        dateFormat( d ){
            return d ? dayjs( d ).format( "DD/MM/YYYY HH:mm:ss" ) : '';
        }
    },

    mounted(){
        if( !service.usuarioLogado.isAdmin ){
            this.$router.push(`/usuarios/${this.id}/editar`);
        }
        else if( this.id ){
            service.usuarios.get( this.id )
                .then( r => {
                    this.usuario = r;
                    service.usuarios.getImagem( this.id ).then( r => this.usuario.imagem = r );
                })
                .catch( r => console.log( r ) );
        }
    }
}
</script>