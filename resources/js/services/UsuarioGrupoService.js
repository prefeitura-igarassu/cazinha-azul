import DefaultService from './DefaultService';

export default class UsuarioGrupoService extends DefaultService 
{
    
    constructor()
    {
        super( "/grupos" );
    }
    
}