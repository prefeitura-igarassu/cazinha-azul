<template>
    <div :class="[ 'field' , col ]">
        <label :class="[ 'w-full' , labelClass ]" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot> <!-- :class="[ 'mt-1' , 'w-full' , inputClass ]" -->
            <div class="field-checkbox" v-for="(option,index) in options" :key="index">
                <Checkbox :id="id+index" :value="option.value" :modelValue="valor" @update:modelValue="updatedModelValue" />
                <label :for="id+index" class="w-full">{{ option.label }}</label>
            </div>
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import Checkbox from 'primevue/checkbox';

export default {
    components: { Checkbox },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'id'    : { type: String , required: true  } , 
        'campo' : { type: String , required: true } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'options' : {} ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required' : { type: Boolean , default: false  } ,
    } ,

    methods: {
        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        },
    },
}
</script>