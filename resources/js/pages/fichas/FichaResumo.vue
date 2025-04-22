<template>
    <div class="flex flex-column" v-if="ficha">
        <div class="flex text-4xl font-bold align-items-center">
            <Avatar :image="`/fichas/${ficha.id}.png`" shape="circle" class="mr-2" size="xlarge" />
            {{ ficha.nome }}
        </div>

        <div class="flex flex-wrap gap-4">
            <div class="flex flex-column flex-wrap my-4">
                <property title="Unidade"        :value="ficha.unidade?.nome"></property>
                <property title="Cadastrado em"  :value="ficha.cadastrado_em" type="datetime"></property>
                <property title="CPF Nº"         :value="ficha.cpf"           type="cpf_cnpj"></property>
                <property title="Nascido em"     :value="ficha.nascido_em"    type="date"    ></property>
                <property title="SUS Nº"         :value="ficha.sus"                          ></property>
                <property title="NIS Nº"         :value="ficha.nis"                          ></property>
                <property title="Posto de Saúde" :value="ficha.posto_saude"                  ></property>
            </div>

            <div class="flex flex-column flex-wrap my-4">
                <property title="Mãe"            :value="ficha.mae_nome"                     ></property>
                <property title="Pai"            :value="ficha.pai_nome"                     ></property>
                <property title="Responsável"    :value="ficha.responsavel"                  ></property>
                <property title="Escola"         :value="ficha.escola"                       ></property>
                <property title="Endereço"       :value="ficha.endereco"                     ></property>
                <property title="Telefone"       :value="ficha.telefone"                     ></property>
                <property title="E-mail"         :value="ficha.email"                        ></property>
            </div>
        </div>

        <property title="CID's"                 :value="cids"              type="array"     ></property>
        <property col="" title="Observação"     :value="ficha.observacao"                   ></property>
    </div>
</template>
    
<script>
import service  from "@/services/Service";
import Property from "./RowProperty.vue";
import Avatar   from "primevue/avatar";

export default {
    components: { Property, Avatar },
    props: [ "ficha_id" ] ,

    data() {
        return {
            ficha: null,
            cids: [],
        };
    },

    watch: {
        ficha_id( value ){
            this.init();
        },
    },

    methods: {
        init(){
            service.fichas.get( this.ficha_id ).then( ficha => {
                this.ficha = ficha;
                this.cids  = ficha.cid;

                service.fichas.getGeneric( `/cid/codigo/${ficha.cid}` ).then( cids => {
                    this.cids = cids ?? ficha.cid;
                });
            });
        },
    },

    mounted(){
        if( this.ficha_id ){
            this.init();
        }
    }
}
</script>