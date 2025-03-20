import DefaultService from './DefaultService';

export default class UsuarioLogService extends DefaultService 
{
    
    constructor()
    {
        super( "/logs" );
    }
    
}