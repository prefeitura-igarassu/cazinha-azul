import Basic from './Basic.js';
import service from "./Service";

export default class MinhaContaService extends Basic {
    
    insert( data ) {
        return this.then( fetch( `/register` , {
                headers: {
                    "Accept": "application/json" ,
                    "X-Requested-With": "XMLHttpRequest" ,
                    "X-CSRF-Token": service.csrfToken
                } ,
                method: 'POST' ,
                credentials: "same-origin" ,
                body: this.getFormData( data )
            } ) );
    }

    forgotPassword( email ){
        return this.then( fetch( `/forgot-password` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
            body: this.getFormData({ email })
        } ) );
    }

    resetPassword( token , email , password , password_confirmation ){
        return this.then( fetch( `/reset-password` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
            body: this.getFormData({ 
                token , email , password , password_confirmation
            })
        } ) );
    }

    getCSRF(){
        return this.fetchCSRF( "/get-csrf-token" )
            .then( r => r.text() )
            .then( r => service.csrfToken = r );
    }

    login( email , password ){
        return this.then( fetch( `/login` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
            body: this.getFormData({ email , password })
        } ) ).then( r => {
            service.usuarioLogado = r;
            this.getCSRF();

            return r;
        });
    }

    getAtual(){
        return this.getGeneric( "/usuario-atual" ).then( r => {
            service.usuarioLogado = r;
            return r;
        }).catch( err => console.log( err ) );
    }
    
    logout(){
        return fetch( `/logout` , {
            headers: {
                "Content-Type": "application/json" ,
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
        } ).then( r => {
            service.usuarioLogado = null;
            this.getCSRF();

            return r;
        });
    }

    update( usuario ){
        return this.then( fetch( `/user/profile-information` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
            body: this.getFormData( { _method: 'PUT', ...usuario } )
        } ) );
    }

    alterarSenha( antiga , nova , novaConfirm ){
        return this.then( fetch( `/user/password` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST',
            body: this.getFormData({
                '_method': 'PUT',
                "current_password": antiga,
                "password": nova,
                "password_confirmation": novaConfirm,
            })
        } ) );
    }

    hasPermissao( modulo , acao ){
        return this.then( fetch( `/permissoes/${modulo}/${acao}` ) );
    }
    
}