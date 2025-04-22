<template>
    <div class="bg-white rounded-lg p-4 mb-4">
        <b class="flex justify-content-between align-items-center flex-wrap">
            <div>
                Serviços Solicitados
                <i v-if="loading" class="pi pi-spin pi-spinner ml-2"></i>
            </div>

            <div class="flex gap-2">
                <Button icon="pi pi-plus"    @click="adicionar" label="Solicitar" size="small" outlined></Button>
                <Button icon="pi pi-refresh" @click="pesquisar" label="Atualizar" size="small"></Button>
            </div>
        </b>
        
        <Button 
            v-if="solicitacoes.length == 0" 
            class="p-5 my-3 w-full" 
            label="Nenhum serviço foi solicitado! Clique aqui para solicitar!"
            @click="dialog = true"
            outlined
        ></Button>

        <div v-else class="flex flex-wrap gap-5 mt-3 mb-5">
            <div v-for="solicitacao in solicitacoes" :key="solicitacao.id" class="p-2 border-2 rounded-lg border-white hover:border-gray-50">
                <div class="flex">
                    <Avatar :icon="iconFormat( solicitacao )" class="mr-2 cursor-pointer" size="xlarge" @click="editar( solicitacao )" />

                    <div>
                        <div><b>{{ solicitacao.servico.nome }}</b></div>

                        <template v-if="solicitacao.terapeuta">
                            <small class="block">{{ solicitacao.terapeuta.nome }}</small>
                            <small class="block">{{ diaFormat( solicitacao.dia ) }} às {{ solicitacao.horario }}h</small>
                        </template>

                        <small class="block">{{ statusFormat( solicitacao ) }}</small>
                        <small v-if="solicitacao.status == 0" class="block">Solicitado em {{ dateFormat( solicitacao.solicitado_em ) }}</small>
                    </div>

                    <div class="flex flex-column ml-3" v-if="ficha">
                            <Button icon="pi pi-pencil" size="small" text @click="editar( solicitacao )"></Button>
                            <Button icon="pi pi-trash"  size="small" text @click="excluir( solicitacao )"></Button>
                        </div>
                </div>
            </div>
        </div>

        <FichaServicoDialog v-model:visible="dialog" :solicitacao="solicitacao" @salvou="salvou" @cancelar="cancelar"></FichaServicoDialog>
    </div>
</template>

<script>

import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import service from "@/services/Service";
import dayjs   from "dayjs";
import FichaServicoDialog from "./FichaServicoDialog.vue";
import dias from './dias';

export default {
    components: { Avatar, FichaServicoDialog, Button },
    props: [ "ficha" ],

    data() {
        return {
            solicitacoes: [],
            solicitacao : {},

            dialog: false,
            loading: false,
        };
    },

    watch: {
        ficha(){
            this.pesquisar();
        },
    },

    methods: {
        pesquisar(){
            this.solicitacoes = [];
            if( !this.ficha ) return ;

            const params = {
                ficha_id: this.ficha.id,
                with: [ 'terapeuta' , 'servico' ]
            };

            this.loading = true;
            service.fichas_servicos.search( params ).then( solicitacoes => {
                this.solicitacoes = solicitacoes;
                this.loading = false;

                console.log( "fichas_servicos" , this.solicitacoes );
            });
        },

        labelFormat( solicitacao ){
            return solicitacao.status == 0 ? `${solicitacao.posicao}º` : null;
        },

        iconFormat( solicitacao ){
            switch( solicitacao.status ){
                case 0: return "pi pi-clock";
                case 1: return "pi pi-calendar-clock";
                case 2: return "pi pi-check";
            }
        },

        statusFormat( solicitacao ){
            switch( solicitacao.status ){
                case 0: return `Aguardando na ${solicitacao.posicao}º posição`;
                case 1: return "";
                case 2: return "Alta";
                default: return "desconhecido";
            }
        },

        diaFormat( dia ){
            return dias[ dia ].label;
        },

        dateFormat( dia ){
            return dayjs( dia ).format( "DD/MM/YYYY [às] HH:mm" );
        },

        adicionar(){
            this.dialog = true;

            this.solicitacao = {
                unidade_id   : this.ficha.unidade_id,
                ficha_id     : this.ficha.id,
                usuario_id   : service.usuarioLogado.id,
                status       : 0,
                solicitado_em: dayjs().format( "YYYY-MM-DD HH:mm:ss" ),
                servico_id   : null,
            };
        },

        editar( solicitacao ){
            this.dialog = true;
            this.solicitacao = solicitacao;
        },

        salvou( solicitacao ){
            this.pesquisar();
        },

        cancelar(){
            this.dialog = false;
            this.solicitacao = null;
        },

        excluir( solicitacao ){
            this.$confirm.require({
                message: 'Você deseja excluir a solicitação selecionada?',
                header: 'Excluir',
                icon: 'pi pi-exclamation-triangle',
                acceptClass: 'p-button-danger',
                accept: () => {
                    service.fichas_servicos.destroy( solicitacao.id ).then( () => {
                        this.pesquisar();
                        this.$toast.add({severity:'info', summary:'Excluída', detail:`A solicitação foi excluída com sucesso!`, life: 3000})
                    });
                },
                reject: () => {
                    this.$toast.add({severity:'error', summary:'Cancelado', detail:'Você cancelou a exclusão!', life: 3000});
                }
            });
        },
    },

    mounted(){
        this.pesquisar();
    }
}
</script>