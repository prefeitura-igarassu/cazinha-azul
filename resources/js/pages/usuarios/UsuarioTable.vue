<template>
    <div>
        <div v-if="!filter">
            <h5>Usuários</h5>
        </div>

        <Transition>
            <Message v-if="token" severity="success" @close="token = null">
                Token criado com sucesso: <b>{{ token }}</b>
            </Message>
        </Transition>
        
        <Table 
            tableId="usuarios"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            :hasChips="false"
            :rowClass="getRowClass"
            :hasGlobalFilter="true"
            :filters="filter"
            empty="Nenhum usuário foi encontrado."
            selectionMode="multiple"
            @visualizar="visualizar"
        ></Table>
    </div>
</template>

<script>
import Page from "@/components/Page.vue";
import Table from "@/components/table/Table.vue";
import colunas from "./tabela";
import acoes from "./acoes";
import service from "@/services/Service";

import { Transition } from "vue";
import Message from "primevue/message";

export default {
    components: { Page , Table, Transition, Message },
    props: [ "filter" ],

    data() {
        return {
            columns: colunas,
            actions: acoes( this ),
            service: service.usuarios,
            token: null,
        };
    } ,

    methods: {
        visualizar( event ){
            if( service.usuarioLogado?.isAdmin ) this.$router.push(`/usuarios/${event.data.id}`);
            else                                 this.$router.push(`/usuarios/${event.data.id}/editar`);
        },

        generateToken( usuarioId ){
            service.usuarios.generateToken( usuarioId ).then( token => {
                this.token = token;
                this.$toast.add({severity:'info' , summary:'Salvou', detail:`Token gerado com sucesso!`, life: 3000});
            }).catch( r => {
                this.$toast.add({severity:'error', summary:'Ops!'  , detail:`Ocorreu um erro e o token não foi gerado!`, life: 3000})
            });
        },

        getRowClass( usuario ){
            return !usuario.ativo ? "text-red-200" : "";
        },
    },
}
</script>

<style scoped>

.v-enter-active, .v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from, .v-leave-to {
  opacity: 0;
}

</style>