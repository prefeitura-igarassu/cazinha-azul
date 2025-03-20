<template>

<div>
    <DataTable 
        :value="data" 
        :resizableColumns="true" 
        :rowHover="true"
        :loading="loading"
        v-model:selection="selected"
        stripedRows 
        size="small"
        :rowClass="rowClass"

        @row-dblclick="$emit( 'visualizar' , $event , data , selected )"
        @sort="filterEvent"
        @page="filterEvent"
        
        rowGroupMode="subheader" 
        :groupRowsBy="agrupar"
        
        :lazy="true" 
        :paginator="true" 
        :totalRecords="result.total"
        :rows="parameters.perPage"
        :paginatorTemplate="paginator" 
        :rowsPerPageOptions="[1,10,20,50,100,200,500]"
        currentPageReportTemplate="Mostrando {first} até {last} de {totalRecords}"
    >
        <template v-if="!print" #header>
            <slot name="header">
                <div class="flex justify-between my-3">
                    <div class="flex flex-row gap-1">
                        <slot name="header-left">
                            <Actions :actions="actions" 
                                :selected="selected" 
                                :result="result" 
                                :parameters="parameters" 
                                @pesquisar="pesquisar()" 
                            />
                        </slot>
                    </div>

                    <div class="flex flex-row gap-1">
                        <slot name="header-right">
                            <div v-if="hasGlobalFilter">
                                <InputGroup>
                                    <InputGroupAddon>
                                        <i class="pi pi-search m-2" />
                                    </InputGroupAddon>

                                    <InputText v-model.trim="globalFilter" placeholder="Pesquisar" />
                                </InputGroup>
                            </div>
                            
                            <Button icon="pi pi-refresh" alt="atualizar" class="p-button-text" @click="pesquisar" />
                            <Button icon="pi pi-table"   alt="colunas"   class="p-button-text" @click="columnVisible = true" />
                        </slot>
                    </div>
                </div>
            </slot>
        </template>

        <template #empty>
            <slot name="empty">
                <div class="m-12 text-3xl font-bold flex items-center justify-center">{{ empty }}</div>
            </slot>
        </template>

        <Column v-if="selectionMode" :selectionMode="selectionMode" headerStyle="width: 3rem"></Column>

        <template v-for="(col,index) of colunasSelecionadasOrdenada" :key="index">
            <Column :field="col.field" 
                    :header="col.title" 
                    :sortable="col.sortable" 
                    :footer="getFooterValue( col , data )" 
                    :footerClass="getFooterClass( col )"
            >
                <template #body="slotProps">
                    <div v-if="col.html"                 v-html="col.html( slotProps.data , this )"                                 :class="getClass( col , slotProps.data )" @click="click( col , slotProps.data , this )"></div>
                    <div v-else-if="getType( col ).html" v-html="getType( col ).html( slotProps.data )"                             :class="getClass( col , slotProps.data )" @click="click( col , slotProps.data , this )"></div>
                    <div v-else                          v-html="format( col , slotProps.data , getValue( col , slotProps.data ) )" :class="getClass( col , slotProps.data )" @click="click( col , slotProps.data , this )"></div>
                </template>
            </Column>
        </template>
        <slot></slot>

        <template v-if="agrupar" #groupheader="slotProps">
            <span>{{ agruparColumn.title }}: </span>
            <span v-if="agruparColumn.html" v-html="agruparColumn.html( slotProps.data )"></span>
            <span v-else-if="getType( agruparColumn ).html" v-html="getType( agruparColumn ).html( slotProps.data )"></span>
            <span v-else :class="getClass( agruparColumn , slotProps.data )">
                {{ format( agruparColumn , slotProps.data , getValue( agruparColumn , slotProps.data ) ) }}
            </span>
        </template>

    </DataTable>

    <SearchView
        v-model:visible="searchVisible" 
        :columns="columns" 
        :parameters="parameters"
        @pesquisar="setFilters"
    />

    <ColumnView 
        :sidebar="true"
        v-model:visible="columnVisible" 
        :columns="columns"
        v-model:agrupar="agrupar"
        v-model:selected="colunas_selecionadas" 
    />
</div>

</template>

<script>
import SearchView from "../search/SearchView.vue";
import SearchInline from "../search/SearchLine.vue";
import ColumnView from "./ColumnView.vue";
import ParametrosChips from "./ParametrosChips.vue";
import Actions from "./Actions.vue";
import { getType, getClass, getValue, format, getFooterClass, getFooterValue, click } from './ColumnFunctions';
import { columnsSave, columnsMounted, columnDefaultVisible } from './ColumnChaveValor';

import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";

export default {
    components: { ColumnView, SearchView, SearchInline, ParametrosChips, Actions, DataTable, Column, Button, InputText, InputGroup, InputGroupAddon },

    props: {
        tableId: { required: true },
        service: { required: true },
        columns: { required: true },
        empty  : { default: "Nenhum resultado foi encontrado!" },
        rowClass: { type: Function },
        actions: {} ,
        filters: { default: {} }, 
        orderBy: { default: null }, 
        selectionMode: {},
        with  : { default: [] },
        fields: { default: {} },

        print : { default: false },
        params: { default: {} },
        
        hasGlobalFilter: { type: Boolean , default: false },
        hasChips: { type: Boolean , default: true },
        searchInline: { default: null }
    },

    data() {
        return {
            colunas_selecionadas: [],
            selected: [],
            loading: false,
            chaveValor: null,
            agrupar: null,
            globalFilter: null,
            globalFilterTime: null,
            showTable: false,

            result: {
                data: [],
                total: 0,
            },

            parameters: {
                perPage: 20,
                page: 1,
                orderBy: this.orderBy,
                with: this.with,
                ... this.filters,
            },

            columnVisible: false,
            searchVisible: false,
        };
    },

    watch: {
        colunas_selecionadas( newValue , oldValue ){
            if( newValue != oldValue && this.chaveValor ){
                this.columnsSave();
            }
        },

        agrupar( newValue , oldValue ){
            if( newValue != oldValue && this.chaveValor ){
                this.columnsSave();
            }
        },

        globalFilter( value ){
            clearTimeout( this.globalFilterTime );
            
            this.globalFilterTime = setTimeout( () => { 
                this.setFilters( value ? { global: value, page: 1 } : { page: 1 } );
            } , 200 );
        },

        params( value ){
            this.parameters = value;
            this.pesquisar();
        },

        filters( value ){
            this.parameters = {
                perPage: 20,
                page: 1,
                orderBy: this.orderBy,
                with: this.with,
                ... value,
            };

            this.pesquisar();
        }
    },

    computed: {
        colunasSelecionadasOrdenada(){
            return this.columns?.filter( column => {
                return this.colunas_selecionadas.includes( column );
            });
        },

        data(){
            return this.result.data;
        },

        agruparColumn(){
            if( !this.agrupar ) return {};

            return this.columns.find( column => {
                if( this.agrupar == column.field ) return column;
            });
        },

        paginator(){
            return this.print 
                ? "" 
                : "FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown";
        }
    },

    methods: {
        filterEvent( event ){
            this.parameters = {
                ... this.parameters,
                perPage: event.rows ?? 20,
                page: (event.page ?? 0) + 1,
                orderBy: event.sortField 
                    ? event.sortField + "," + (event.sortOrder == 1 ? 'ASC' : 'DESC')
                    : null,
                with: this.with,
            };

            this.pesquisar();
            this.$emit( "filter" , this.parameters );
        },

        setFilters( filters ){
            this.parameters = {
                perPage: this.parameters.perPage ?? 20,
                page   : this.parameters.page    ??  1,
                orderBy: this.parameters.sortField 
                    ? this.parameters.sortField + "," + (this.parameters.sortOrder == 1 ? 'ASC' : 'DESC')
                    : null,
                with: this.with,

                ... filters, 
                ... this.filters,
            };

            this.pesquisar();
            this.$emit( "filter" , this.parameters );
        },

        pesquisar(){
            this.loading = true;

            if( !this.parameters.orderBy ) delete this.parameters.orderBy;

            this.service.search( this.parameters ).then( result =>{
                this.loading  = false;
                this.selected = [];
                this.result   = Array.isArray( result ) ? result : result;
                this.$emit( "resultado" , result );
            });
        },
        
        getType, getClass, getValue, format, getFooterClass, getFooterValue, click,
        columnsSave, columnsMounted, columnDefaultVisible,
    },

    beforeMount(){
        this.colunas_selecionadas = this.columns?.filter( c => c.visible );
    },

    mounted(){
        this.showTable = !this.searchInline;

        setTimeout( () => this.columnsMounted() , 100 );
        setTimeout( () => this.pesquisar()      , 200 );
    }
}
</script>

<!--
<style lang="scss" scoped>

::v-deep(.p-paginator) {
    .p-paginator-current {
        margin-left: auto;
    }
}

</style>
-->