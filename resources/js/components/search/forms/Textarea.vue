<template>
    <Field :col="col" :id="id" :title="title" :error="error" :description="description" 
           :labelClass="labelClass" :required="required" :show="show" @update:show="value => $emit( 'update:show' , value )"
    >
        <Textarea 
            :id="id" 
            v-model="value" 
            :class="['block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
            :autoResize="autoResize" 
            :rows="rows" 
        />
    </Field>
</template>

<script>
import Field from "./Field.vue";
import Textarea from 'primevue/textarea';

export default {
    components: { Field, Textarea },

    props: {
        'row': {},
        'autoResize': { default: true },

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
    } ,

    data(){
        return {
            value: null,
        };
    },

    watch: {
        value( value ){
            this.$emit( "update:modelValue" , { '**': value }  );
            this.$emit( "changed" , value );
        },

        modelValue( value ){
            this.value = this.value ? this.value['**'] : null;
        },

        show( value ){
            this.$emit( 'update:show' , value );
        },
    },

    mounted(){
        this.value = this.modelValue ? this.modelValue['**'] : null;
    }
}
</script>