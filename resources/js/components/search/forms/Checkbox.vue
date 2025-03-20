<template>
    <Field :col="col" :id="id" :title="title" :error="error" :description="description" 
           :labelClass="labelClass" :required="required" :show="show" @update:show="value => $emit( 'update:show' , value )"
    >
        <div class="field-checkbox" v-for="(option,index) in options" :key="index">
            <Checkbox :id="id+index" :value="option.value" :modelValue="modelValue" @update:modelValue="updatedModelValue" />
            <label :for="id+index" class="w-full">{{ option.label }}</label>
        </div>
    </Field>
</template>

<script>
import Field from "./Field.vue";
import Checkbox from 'primevue/checkbox';

export default {
    components: { Field, Checkbox },

    props: {
        'options' : { default: [] } ,
        
        'col'   : { type: String , default: 'col'  },
        'id'    : { type: String , required: true  },
        'title' : { type: String , default: this.getDefaultTitle()  },
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