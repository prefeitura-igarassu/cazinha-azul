<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <InputMask 
                :id="id" 
                :type="type" 
                :modelValue="valor" 
                :mask="mask"
                :autoClear="false"
                :class="[ 'block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                @blur="$emit('blur')" 
                @focus="$emit('focus')"
                @update:modelValue="updatedModelValue"
            />
        </slot>

        <small v-if="descricao" class="block">{{ descricao }}</small>
        <small v-if="error"     class="p-error block">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import InputMask from "primevue/inputmask";

export default {
    components: { InputMask },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'type'  : { type: String , default: 'text' } ,
        'id'    : { type: String , required: true } ,
        'mask'  : { type: String , required: true } , 
        'campo' : { type: String , required: true } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'inputClass': {} ,
        'labelClass': {} ,
        'descricao' : {} ,
        'required'  : { type: Boolean , default: false  } ,
    } ,

    methods: {
        handleBlur(){
            this.$emit('blur');
        },

        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        },
    }
}
</script>