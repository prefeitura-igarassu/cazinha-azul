<template>

<Page :title="title">
    <template #menu>
        <Actions :actions="actions" :selected="selected" @pesquisar="goBack" />
    </template>

    <div class="grid grid-cols-12 gap-0">
        <div class="col-2">

            <template v-for="(col,index) in columns" :key="index">
                <Property col="mt-6" :column="col" :object="object"></Property>
            </template>
        </div>

        <div class="col-10">
            <slot></slot>
        </div>
    </div>
</Page>

</template>

<script>
import Page    from "@/components/Page.vue";
import Actions from "@/components/table/Actions.vue";
import Property from "./Property.vue";

export default {
    components: { Page, Actions, Property },
    props: {
        "title"  : { type: String   , default: "" }, 
        "actions": { type: Array    , default: [] }, 
        "columns": { type: Array    , default: [] },
        "object" : { type: Object   , default: {} },
        "goBack" : { type: Function , default: () => history.back() }, 
    },

    data() {
        return {
            
        };
    },

    computed: {
        selected(){
            return [ this.object ];
        },
    },

    methods: {
        
    },

    mounted(){
        
    }
}
</script>