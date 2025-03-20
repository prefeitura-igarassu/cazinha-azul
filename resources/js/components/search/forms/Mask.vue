<template>
    <Field :col="col" :id="id" :title="title" :error="error" :description="description" 
           :labelClass="labelClass" :required="required" :show="show" @update:show="value => $emit( 'update:show' , value )"
    >
        <InputMask 
            :id="id" 
            :type="type" 
            :modelValue="modelValue" 
            :mask="mask"
            :autoClear="false"
            :class="[ 'block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
            @blur="$emit('blur')" 
            @focus="$emit('focus')"
            @update:modelValue="updatedModelValue"
        />
    </Field>
</template>

<script>
import Field from "./Field.vue";
import InputMask from 'primevue/inputmask';

export default {
    components: { Field, InputMask },

    props: {
        'mask'  : { type: String , required: true },
        
        'col'   : { type: String , default: 'col' },
        'id'    : { type: String , required: true },
        'title' : { type: String , required: true },
        'modelValue' : {},
        'inputClass' : {},
        'labelClass' : {},
        'description': {},
        'error'    : {},
        'required' : { type: Boolean , default: false },
        'show'     : { type: Boolean , default: false },
    },

    methods: {
        updatedModelValue( value ){
            this.$emit( "update:modelValue" , value );
            this.$emit( "changed" , value );
        },
    }
}
</script>