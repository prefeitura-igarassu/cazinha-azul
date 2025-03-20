<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <Textarea 
                :id="id" 
                :modelValue="valor" 
                :class="['block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                :autoResize="true" 
                :rows="rows" 
                @update:modelValue="updatedModelValue"
            />
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import Textarea from "primevue/textarea";

export default {
    components: { Textarea },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'rows'  : { type: String , default: '6'    } ,
        'id'    : { type: String , required: true  } , 
        'campo' : { type: String , required: true  } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required' : { type: Boolean , default: false  } ,
    } ,
    
    methods: {
        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        }
    }
}
</script>