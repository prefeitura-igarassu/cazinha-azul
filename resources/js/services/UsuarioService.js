import DefaultService from './DefaultService';
import service from "./Service";

export default class UsuarioService extends DefaultService 
{
    
    constructor()
    {
        super( "/usuarios" );
        this.usuario = null;
    }

    alterarSenha( usuarioId , nova , novaConfirmacao ){
        return this.then( fetch( `/usuarios/${usuarioId}/alterar_senha` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
            body: this.getFormData({
                password: nova,
                password_confirmation: novaConfirmacao,
            })
        } ) );
    }

    gerarNovaSenha( usuarioId ){
        return this.then( fetch( `/usuarios/${usuarioId}/gerar_senha` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
        } ) );
    }

    getImagem( usuarioId ){
        return this.then( fetch( `/usuarios/${usuarioId}/imagem` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            credentials: "same-origin" ,
        } ) );
    }

    upload( usuarioId , imagem ){
        return this.then( fetch( `/usuarios/${usuarioId}/imagem` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'POST' ,
            credentials: "same-origin" ,
            body: this.getFormData({ imagem } )
        } ) );
    }

    getOptions(){
        return this.then( fetch( `/usuarios/options` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            credentials: "same-origin" ,
        } ) );
    }

    getAvaliadoresOptions(){
        let p = {
            avaliador: 1,
            cartorio_id: null,
        };

        return this.search( p ).then( r => {
            let avaliadores = [];

            r.forEach( usuario => {
                avaliadores.push({
                    label: usuario.nome,
                    value: usuario.id
                });
            });

            return avaliadores;
        });
    }

    generateToken( usuarioId ){
        return this.then( fetch( `/usuarios/${usuarioId}/token` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            },
            method: 'POST',
            credentials: "same-origin",
        } ) );
    }
    
}