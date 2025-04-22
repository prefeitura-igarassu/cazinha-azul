<template>
    <Message severity="error" v-if="hasError" class="p-4 my-4">
        <div class="block">O(s) seguinte(s) erro(s) precisa(m) ser corrigido: </div>

        <ul class="list-disc ml-5 mt-2">
            <template v-for="(values, name) in errors" :key="name">
                <li v-for="value in values" :key="value">{{ value }}</li>
            </template>
        </ul>
    </Message>
</template>

<script>
import Message from 'primevue/message';

export default {
    components: { Message },
    props: [ "errors" ],

    data() {
        return {
            
        }
    },

    computed: {
        hasError(){
            return this.errors 
                ? Object.keys( this.errors ).length > 0
                : false;
        },
    }
}
</script>