<template>
    <div class="bg-surface-0 dark:bg-surface-900 py-2">
        <div class="mb-4 table-header">
            <div class="font-medium text-surface-900 dark:text-surface-0 cursor-pointer" @click="setUpdateVisible( !visible )">
                <span :class="[ 'pi' , visible ? 'pi-chevron-down' : 'pi-chevron-right' , 'p-accordion-toggle-icon' ]"></span>
                <span class="ml-2">{{ title }}</span>
            </div>

            <div>
                <slot name="buttons"></slot>
            </div>
        </div>
        <div class="text-surface-500 dark:text-surface-300 mb-8" v-if="subtitle">{{ subtitle }}</div>
        
        <div v-show="visible">
            <slot>
                <ul class="list-none p-0 m-0 border-surface">
                    <Row v-for="(row,index) in rows" 
                        :key="index" 
                        :title="row.title" 
                        :value="row.value"></Row>
                </ul>
            </slot>
        </div>
    </div>
</template>

<script>
import Row from './Row';

// surface-50

export default {
    components: { Row } ,

    props: {
        'title'    : { type: String , default: null } ,
        'subtitle' : { type: String , default: null } ,
        'rows'     : { default: [] } ,
        'visible'  : { type: Boolean , default: true } ,
    },

    methods: {
        setUpdateVisible( value ){
            this.$emit( "update:visible" , value );
        }
    }
}
</script>

<style scoped>
.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>