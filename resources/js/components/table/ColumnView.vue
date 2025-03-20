<template>

<Drawer v-if="sidebar" :visible="visible" position="right" @update:visible="updatedVisible">
    <template #header>
        <h2>Colunas</h2>
    </template>

    <ColumnTable :columns="columns" :selected="selected" @update:selected="( value ) => $emit('update:selected' , value)" />

    <div v-if="agruparOptions.length > 0" class="w-full">
        <h2 class="m-4">Agrupar</h2>
        <Select 
            class="w-full"
            :modelValue="agrupar" 
            :options="agruparOptions" 
            optionValue="field" 
            optionLabel="title" 
            placeholder="Agrupar por" 
            @update:modelValue="value => $emit('update:agrupar' , value )"
        />
    </div>
</Drawer>

<Dialog v-else :visible="visible" header="Colunas" :modal="true" @update:visible="updatedVisible">
	<ColumnTable :columns="columns" :selected="selected" @update:selected="( value ) => $emit('update:selected' , value)" />

    <div v-if="agruparOptions.length > 0" class="w-full">
        <h2 class="m-4">Agrupar</h2>
        <Select 
            class="w-full"
            :modelValue="agrupar" 
            :options="agruparOptions" 
            optionValue="field" 
            optionLabel="title" 
            placeholder="Agrupar por" 
            @update:modelValue="value => $emit('update:agrupar' , value )"
        />
    </div>
</Dialog>

</template>

<script>
import ColumnTable from './ColumnTable.vue';
import Drawer from "primevue/drawer";
import Select from "primevue/select";
import Dialog from "primevue/dialog";
import InputText from 'primevue/inputtext';

export default {
    components: { ColumnTable, Drawer, Select, Dialog, InputText },
    props: [ "sidebar" , "visible" , "columns" , "selected" , "agrupar" ],

    data() {
        return {

        }; 
    },

    watch: {
        columns ( value ){ 
            if( this.visible )
                this.$emit( "update:columns"  , value ); 
        },

        agrupar ( value ){ 
            if( this.visible )
                this.$emit( "update:agrupar"  , value ); 
        },

        selected( value ){ 
            if( this.visible ){
                this.$emit( "update:selected" , value ); 
            }
        },
    },

    computed: {
        agruparOptions(){
            let selected = [{
                field: null , 
                title: "Nenhuma"
            }];

            this.columns.forEach( column => {
                if( column.groupable || column.field ) 
                    selected.push( column );
            });

            return selected;
        }
    },

    methods: {
        updatedVisible( value ){ 
            this.$emit( "update:visible"  , value );
        },
    }
}
</script>