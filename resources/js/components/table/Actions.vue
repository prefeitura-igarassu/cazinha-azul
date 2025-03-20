<template>
<div class="flex flex-wrap gap-1">
    <template v-for="(action,index) of actions" :key="index">
        <FileUpload v-if="action.upload" 
            mode="basic"
            :maxFileSize="action.maxFileSize" 
            :fileLimit="action.fileLimit"
            :accept="action.accept"
            :multiple="action.multiple" 
            :chooseLabel="action.label" 
            :uploadIcon="action.icon" 
            :class="action.class" 
            :customUpload="true"
            :auto="true"
            @uploader="actionUpload( action , $event )" 
        />

        <template v-else-if="action.actions && !action.click">
            <Button v-show="isActionShow( action )" type="button" :label="action.label" :icon="action.icon" :class="action.class" @click="action.toggle" aria-haspopup="true" :aria-controls="'overlay_menu_' + action.ref" />
            <Menu :ref="getRef( action )" :id="'overlay_menu_' + action.ref" :model="action.actions" :popup="true" />
        </template>

        <SplitButton v-else-if="action.actions"
            v-show="isActionShow( action )"
            :label="action.label" 
            :icon="action.icon" 
            :class="action.class" 
            @click="actionClicked( action )" 
            :model="action.actions"
        />

        <Button v-else
            v-show="isActionShow( action )"
            :label="action.label" 
            :icon="action.icon" 
            :class="action.class" 
            @click="actionClicked( action )" 
        />
    </template>
</div>
</template>

<script>
import service from "@/services/Service";
import FileUpload from "primevue/fileupload";
import SplitButton from "primevue/splitbutton";
import Button from "primevue/button";
import Menu from "primevue/menu";

export default {
    components: { FileUpload, SplitButton, Button, Menu },
    props: [ 'actions' , 'selected' , 'parameters' , 'result' ],

    data() {
        return {

        };
    },

    methods: {
        isActionShow( acao ){
            if ( acao.isAdmin ) 
                if( !service.usuarioLogado?.isAdmin ) return false;

            if ( acao.groups ){
                if( !acao.groups.includes( parseInt( service.usuarioLogado?.grupo_id ) ) ) return false;
            }

            if( acao.showOnlyOne  ) return this.selected.length == 1;
            else if( acao.showOnlyMany ) return this.selected.length > 0;
            else return true;
        },

        actionClicked( action ){
            action.click( this.selected , this );
        },

        actionUpload( action , event ){
            action.click( event.files , this );
        },
        
        getRef( action ){
            if( action.ref ) return action.ref;

            //generate random String
            action.ref = (Math.random() + 1).toString(36).substring( 7 );

            action.toggle = ( evnt ) => this.$refs[ action.ref ][ 0 ].toggle( evnt );
            
            action.actions.forEach( elm => {
                elm.command = ( evnt ) => elm.click( this.selected , this );
            });

            return action.ref;
        },

        pesquisar(){
            this.$emit( "pesquisar" );
        },
    }
}
</script>