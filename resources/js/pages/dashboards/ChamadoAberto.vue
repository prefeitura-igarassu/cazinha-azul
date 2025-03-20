<template>
    <DataView :value="chamados" :loading="loading" layout="list" :class="valueClass">
        <template #header>
            {{ coluna.title }}
        </template>

        <template #empty>
            <p class="p-5 text-center">Nenhum chamado foi encontrado!</p>
        </template>

        <template #list="slotProps">
            <div class="grid grid-nogutter p-2">
                <div class="col-12 py-2" v-for="chamado in slotProps.items" :key="chamado.id" @click="$emit( 'visualizar' , chamado )">

                    <div :class="getClass( chamado )" >
                        <div class="flex-1 flex flex-column gap-2">
                            <span class="text-xl font-bold">{{ chamado.titulo }}</span>
                            <div>
                                <div class="flex align-items-center gap-2">
                                    <i class="pi pi-tag text-sm"></i>
                                    <span>{{ chamado.assunto }}</span>
                                </div>
                            </div>

                            <div class="flex justify-content-between gap-4">
                                <div class="flex align-items-center gap-2">
                                    <i class="pi pi-building text-sm"></i>
                                    <span>{{ chamado.empresa?.nome ?? 'Nenhum' }}</span>
                                </div>

                                <div class="flex align-items-center gap-2">
                                    <i class="pi pi-user text-sm"></i>
                                    <span>{{ chamado.requerente }}</span>
                                </div>

                                <div class="flex align-items-center gap-2">
                                    <i class="pi pi-clock text-sm"></i>
                                    <span>{{ now( chamado.requerido_em ) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </DataView>
</template>

<script>
import DataView from 'primevue/dataview';
import Button   from 'primevue/button';
import dayjs from 'dayjs';

export default {
    components: { DataView, Button },
    props: [ 'coluna' , 'loading' , 'chamados' , 'atualizar' ],

    data() {
        return {

        };
    },

    computed: {
        valueClass(){
            return `${this.coluna.property}-${this.coluna.value}`;
        },
    },

    methods: {
        visualizar( event ){
            this.$router.push( `/chamados/${event.data.id}` );
        },

        now( data ){
            return dayjs( data ).fromNow();
        },

        visualizar( chamado ){
            alert( `Visualizar o Chamado Nº ${chamado.id}` );
        },

        getClass( chamado ){
            let t = "flex flex-wrap p-2 align-items-center gap-3 cursor-pointer";

            if( chamado.status == 1 ) t += " border-andamento hover:bg-yellow-500";
            else                      t += " border-round border-400 hover:border-1 hover:surface-hover";

            return t;
        }
    },
}
</script>

<style>

.border-andamento {
    border-left-width: 1px;
    border-left-style: solid;
    border-left-color: #FBC02D;
}

.prioridade-0 .p-dataview-header {
    background: #0ea5e9;
    color: white;
}

.prioridade-1 .p-dataview-header {
    background: #FBC02D;
    color: white;
}   

.prioridade-2 .p-dataview-header {
    background: #ef4444;
    color: white;
}

.status-0 .p-dataview-header {
    background: #f1f5f9;
    color: rgb(71, 85, 105);
}

.status-1 .p-dataview-header {
    background: #FBC02D;
    color: white;
}

</style>