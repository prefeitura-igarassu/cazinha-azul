<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <InputNumber 
                :id="id" 
                :readonly="readonly"
                :disabled="readonly"
                :modelValue="valor" 
                mode="currency" 
                currency="BRL" 
                locale="pt-BR" 
                class="block mt-1 w-full"
                :inputClass="input_class_str"
                :max="9999999999"
                @update:modelValue="updatedModelValue"
            />
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import InputNumber from 'primevue/inputnumber';

export default {
    components: { InputNumber },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'type'  : { type: String , default: 'text' } ,
        'id'    : { type: String , required: true  } , 
        'campo' : { type: String , required: true  } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required' : { type: Boolean , default: false  } ,
        'readonly' : { type: Boolean , default: false  } ,
    } ,

    computed: {
        input_class_str(){
            return [  'w-full' , 'text-right' , (this.error ? 'p-invalid' : '') , this.inputClass ].join( ' ' );
        }
    },

    methods: {
        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        },
    },
}
</script>