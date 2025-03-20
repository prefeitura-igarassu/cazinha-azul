<template>
    <Drawer :visible="visible" position="right" class="p-sidebar-md" style="width: 60vw" @update:visible="updatedVisible">
        <template #header>
            <h2>{{ title }}</h2>
        </template>
        
        <slot name="buttons"></slot>

        <div class="grid grid-cols-12 gap-0">
            <slot name="left"></slot>

            <div class="col-12 md:col">
                <Rows v-for="(r,index) in rows" :key="index" 
                    :title="r.title"
                    :subtitle="r.subtitle"
                    :visible="r.visible"
                    :rows="r.rows"
                    @update:visible="rowUpdatedVisible( index )"
                ></Rows>
            </div>

            <slot name="right"></slot>
        </div>

        <slot></slot>
    </Drawer>
</template>

<script>
import Drawer from 'primevue/drawer';
import Rows from './Rows.vue';

export default {
    components: { Drawer , Rows },
    props: [ 'visible' , 'title' , 'rows' ],

    methods: {
        rowUpdatedVisible( index ){
            this.rows[ index ].visible = !this.rows[ index ].visible;
            this.$emit( 'update:rows' , this.rows );
        },

        updatedVisible( value ){
            this.$emit( 'update:visible' , value );
        }
    },
}
</script>
