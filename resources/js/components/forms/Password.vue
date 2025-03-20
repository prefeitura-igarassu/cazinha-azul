<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <Password 
                :id="id" 
                :type="type" 
                :modelValue="valor" 
                :readonly="readonly"
                :class="['block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                @update:modelValue="updatedModelValue"
            />
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import Password from 'primevue/password';

export default {
    components: { Password },

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

    methods: {
        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        },
    },
}
</script>