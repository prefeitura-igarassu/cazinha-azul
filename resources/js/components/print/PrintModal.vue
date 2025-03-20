<template>
    <Dialog :visible="display" 
        :breakpoints="{'960px': '75vw', '640px': '90vw'}" 
        :style="{width: '75vw'}"
        :modal="true" 
        :maximizable="true"
        :header="header"
        @update:visible="$emit('update:visible',false)"
    >
        <div id="PrintMe">
            <slot></slot>
        </div>

        <template #footer>
            <Button label="Cancelar" class="p-button-text" @click="$emit('cancel')" />
            <Button label="Imprimir" icon="pi pi-print" @click="print" autofocus />
        </template>
    </Dialog>
</template>

<script>

import Dialog from "primevue/dialog";
import Button from "primevue/button";

export default {
    components: { Dialog, Button },
    props: [ "header" , "display" ],
    emits: [ 'cancel' ] ,

    data(){
        return {

        };
    } ,

    watch: {
        display( newValue ){
            this.$emit( 'cancel' );
        }
    } ,

    methods : {
        async print(){
            await this.$htmlToPaper( 'PrintMe' );
        }
    }
}

</script>
