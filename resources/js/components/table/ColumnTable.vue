<template>

<DataTable :value="columns" @row-reorder="onRowReorder" :selection="selected" dataKey="title" responsiveLayout="scroll"
    @update:selection="(value) => $emit( 'update:selected' , value )">
    <Column :rowReorder="true" headerStyle="width: 3em" />
    <Column selectionMode="multiple" headerStyle="width: 3em"></Column>
    <Column field="title" header="Coluna"></Column>
</DataTable>

</template>

<script>
import DataTable from "primevue/datatable";
import Column from "primevue/column";

export default {
    components: { DataTable , Column },
    props: [ 'columns' , 'selected' ],

    methods: {
        onRowReorder( event ){
            this.$emit( "update:columns" , event.value );
        }
    }
}
</script>