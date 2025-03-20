<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <div class="flex gap-0">
                <div class="flex w-48"><Rating :id="id" :modelValue="valor" @update:modelValue="updatedModelValue" /></div>
                <div class="flex w-48 pr-12">{{ txt }}</div>
            </div>
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import Rating from "primevue/rating";

export default {
    components: { Rating },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'type'  : { type: String , default: 'text' } ,
        'id'    : { type: String , required: true  } , 
        'campo' : { type: String , required: true } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required' : { type: Boolean , default: false  } ,
    } ,

    computed: {
        txt(){
            switch( this.valor ){
                case 1: return "Péssima";
                case 2: return "Ruim";
                case 3: return "Na Média";
                case 4: return "Boa";
                case 5: return "Excelente";
                default: return "Não sei avaliar";
            } 
        } ,
    } ,

    methods: {
        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        },
    },
}
</script>