<template>
    
    <div class="field">
        <div class="flex justify-content-between mb-2">
            <label>Permissões</label>

            <div class="flex gap-5">
                <Button icon="pi pi-angle-down"  @click="expandAll()"   class="p-button-text p-buttom-sm m-0 p-0" />
                <Button icon="pi pi-angle-right" @click="collapseAll()" class="p-button-text p-buttom-sm m-0 p-0" />

                <Button icon="pi pi-thumbs-up"   @click="mudar( null , true  )" class="p-button-text p-buttom-sm m-0 p-0" />
                <Button icon="pi pi-thumbs-down" @click="mudar( null , false )" class="p-button-text p-buttom-sm m-0 p-0 p-button-danger" />
            </div>
        </div>

        <Tree :value="nodes" :expandedKeys="expandedKeys" :loading="loading" :filter="true" filterMode="lenient">
            <template #default="slotProps">
                <div class="w-full">
                    <div class="flex justify-content-between flex-wrap gap-5">
                        <div class="flex">
                            <b>{{slotProps.node.label}}</b>
                        </div>

                        <div class="flex gap-5">
                            <Button icon="pi pi-thumbs-up"   label="Permitir" @click="mudar( slotProps.node , true  )" class="p-button-text p-buttom-sm m-0 p-0" />
                            <Button icon="pi pi-thumbs-down" label="Negar"    @click="mudar( slotProps.node , false )" class="p-button-text p-buttom-sm m-0 p-0 p-button-danger" />
                        </div>
                    </div>
                </div>
            </template>

            <template #url="slotProps">
                <Checkbox :inputId="slotProps.node.key" 
                    v-model="permissoes[ slotProps.node.module ][ 'acoes' ][ slotProps.node.action ]" 
                    :binary="true" 
                    @change="$emit( 'setPermissoes' , this.permissoes )"
                />

                <label :for="slotProps.node.key" class="pl-3 cursor-pointer">{{ slotProps.node.label }}</label>
            </template>
        </Tree>
    </div>

</template>

<script>
import service from "@/services/Service";

import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import Tree from "primevue/tree";

export default {
    components: { Button, Checkbox, Tree },
    props: [ 'usuario_id' , 'grupo_id' ],

    data() {
        return {
            loading: false,
            actions: [],
            nodes  : [],
            expandedKeys: {},
            permissoes: {},
        };
    },

    methods: {
        init(){
            this.loading = true;
            
            service.permissoes.getActions().then( r => {
                this.actions = r;

                this.initPermissoes().then( () => {
                    this.createNodes();
                    this.loading = false;
                });
                
            });
        },

        initPermissoes(){
            if( !this.usuario_id && !this.grupo_id ){
                return Promise.all( [] );
            }

            let params = this.usuario_id 
                ? { usuario_id : this.usuario_id }
                : { grupo_id   : this.grupo_id   }
            ;

            return service.permissoes.search( params ).then( r => {
                let permissoes = {};
                
                r.forEach( permissao => {
                    permissoes[ permissao.modulo ] = permissao;
                });

                this.permissoes = permissoes;
            });
        },

        createNodes(){
            this.nodes = Object.keys( this.actions ).map( ( moduleName , index ) => {
                if( !this.permissoes[ moduleName ] ){
                    this.permissoes[ moduleName ] = {
                        id: null,
                        grupo_id: this.grupo_id,
                        usuario_id: this.usuario_id,
                        modulo: moduleName,
                        expirar_em: null,
                        acoes: {},
                    };
                }
                
                let children = Object.keys( this.actions[ moduleName ] ).map( ( actionName ) => {
                    if( !this.permissoes[ moduleName ][ 'acoes' ][ actionName ] ) 
                        this.permissoes[ moduleName ][ 'acoes' ][ actionName ] = false;

                    return {
                        key   : `${moduleName}-${actionName}`,
                        label : this.actions[ moduleName ][ actionName ],
                        module: moduleName,
                        action: actionName,
                        type  : 'url',
                    };
                });

                return {
                    key: index, 
                    label: moduleName, 
                    children: children
                };
            });
        },

        mudar( module , value ){
            if( module == null ){
                this.nodes.forEach( node => this.setPermissoes( node , value ) );
                this.$emit( 'setPermissoes' , this.permissoes );
            }
            else{
                this.setPermissoes( module , value );
                this.$emit( 'setPermissoes' , this.permissoes );
            }
        },

        setPermissoes( module , value ){
            module.children.forEach( node => {
                this.permissoes[ node.module ][ 'acoes' ][ node.action ] = value;
            });

            this.expandNode( module );
        },

        expandAll() {
            for (let node of this.nodes) {
                this.expandNode(node);
            }

            this.expandedKeys = {...this.expandedKeys};
        },

        collapseAll() {
            this.expandedKeys = {};
        },

        expandNode( node ) {
            if (node.children && node.children.length) {
                this.expandedKeys[node.key] = true;

                for (let child of node.children) {
                    this.expandNode(child);
                }
            }
        }
    },

    mounted(){
        this.init();
    }
}
</script>

<style>

.p-treenode-label {
    width: 100%;
}

</style>