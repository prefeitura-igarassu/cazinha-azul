<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <Editor 
                :id="id" 
                :modelValue="valor" 
                :class="['block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                :autoResize="true" 
                :rows="rows" 
                @update:modelValue="updatedModelValue"
                :editorStyle="editorStyle"
            />
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import Editor from "primevue/editor";

export default {
    components: { Editor },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'rows'  : { type: String , default: '6'    } ,
        'id'    : { type: String , required: true  } , 
        'campo' : { type: String , required: true  } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'editorStyle' : { default: '' } ,
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