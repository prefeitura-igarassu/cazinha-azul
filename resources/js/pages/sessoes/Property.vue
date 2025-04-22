<template>

    <div :class="col">
        <b class="block">{{ title }}</b>
        <div>{{ valueStr }}</div>
    </div>
    
</template>
    
<script>
export default {
    props: {
        col   : { default: 'col' }, 
        title : { required: true }, 
        value : { required: true },
        options : {},
    },

    data() {
        return { };
    },

    computed: {
        valueStr(){
            if( this.options ){
                for ( let i = 0 ; i < this.options.length ; i++ ) {
                    if( this.options[ i ].value == this.value )
                        return this.options[i].label;
                }
            }

            return this.value;
        },
    },
}
</script>