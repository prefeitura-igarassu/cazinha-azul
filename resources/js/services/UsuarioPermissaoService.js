import DefaultService from './DefaultService';

export default class UsuarioPermissaoService extends DefaultService 
{
    
    constructor()
    {
        super( "/permissoes" );
    }

    getActions(){
        return this.getGeneric( "/actions" );
    }
    
}