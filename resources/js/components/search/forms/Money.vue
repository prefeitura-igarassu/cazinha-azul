<template>
    <Field :col="col" :id="id" :title="title" :error="error" :description="description" 
           :labelClass="labelClass" :required="required" :show="show" @update:show="value => $emit( 'update:show' , value )"
    >
        <div class="p-inputgroup" style="margin-top: 4px;">
            <InputNumber 
                :id="id + '-min'" 
                v-model="min" 
                mode="currency" 
                currency="BRL" 
                locale="pt-BR" 
                class="block mt-1 w-full"
                style="margin-top: 0 !important;"
                :inputClass="input_class_str"
                :max="9999999999"
            />

            <span class="p-inputgroup-addon">
                à
            </span>

            <InputNumber 
                :id="id + '-max'" 
                v-model="max" 
                mode="currency" 
                currency="BRL" 
                locale="pt-BR" 
                class="block mt-1 w-full"
                style="margin-top: 0 !important;"
                :inputClass="input_class_str"
                :max="9999999999"
            />
        </div>
    </Field>
</template>

<script>
import Field from "./Field.vue";
import InputNumber from 'primevue/inputnumber';

export default {
    components: { Field, InputNumber },

    props: {
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

    data(){
        return {
            min: null,
            max: null
        };
    },

    watch: {
        modelValue( value ){
            this.min = value ? value['ge'] : null;
            this.max = value ? value['le'] : null;
        },

        show( value ){
            this.$emit( 'update:show' , value );
        },

        min( value ){
            this.$emit( "update:modelValue" , { ge : this.min , le: this.max } );
            this.$emit( "changed" , { ge : this.min , le: this.max } );
        },

        max( value ){
            this.$emit( "update:modelValue" , { ge : this.min , le: this.max } );
            this.$emit( "changed" , { ge : this.min , le: this.max } );
        },
    },

    computed: {
        input_class_str(){
            return [ 'w-full', 'text-right', (this.error ? 'p-invalid' : '') , this.inputClass ].join( ' ' );
        }
    },

    created(){
        if( !this.modelValue ){
            this.$emit( "update:modelValue" , { ge: null , lt: null } );
        }
    },

    mounted(){
        this.min = this.modelValue ? this.modelValue['ge'] : null;
        this.max = this.modelValue ? this.modelValue['le'] : null;
    }
}
</script>