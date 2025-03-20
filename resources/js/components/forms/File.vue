<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <FileUpload 
                mode="basic" 
                :name="name" 
                :accept="accept" 
                :multiple="multiple" 
                :customUpload="true" 
                @uploader="setFile" 
                :auto="true" 
                :class="['block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
            />
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import FileUpload from "primevue/fileupload";

export default {
    components: { FileUpload },

    props: {
        'col'   : { type: String , default: 'col'  },
        'id'    : { type: String , required: true  }, 
        'campo' : { type: String , required: true  },
        'required': { type: Boolean , default: false },
        'valor' : {},
        'error' : { default: '' },
        'min'   : {},
        'max'   : {},
        'inputClass' : {},
        'labelClass' : {},

        'accept'  : { type: String },
        'name'    : { type: String },
        'multiple': { type: Boolean, deafult: false },
    },

    watch: {
        valor( newValue, oldValue ){
            this.$emit( "update:valor" , newValue );
        },
    },

    methods: {
        setFile( event ){
            let all = Promise.all( 
                event.files.map( this.fileToDataURL ) 
            );

            all.then( results => {
                this.$emit( "update:valor" , this.multiple ? results : results[0] );
            });
        },

        fileToDataURL( file ){
            var reader = new FileReader();

            return new Promise((resolve, reject ) => {
                reader.onload = event => {
                    resolve( {
                        nome    : file.name,
                        tamanho : file.size,
                        tipo    : file.type,
                        conteudo: event.target.result,
                    });
                };

                reader.readAsDataURL( file );
            });
        },
    }
}
</script>