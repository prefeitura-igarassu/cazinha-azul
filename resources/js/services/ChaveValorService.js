import service from "./Service.js";
import DefaultService from './DefaultService';

export default class ChaveValorService extends DefaultService 
{
    
    constructor()
    {
        super( "/chaves" );
    }
    
    getByName( chave ){
        return this.search({ "chave": chave })
        .then( result => result.length > 0 ? result[0] : null );
    }

    getMyByName( chave ){
        return this.search({
            "chave": chave,
            "usuario_id": service.usuarioLogado?.id,
        })
        .then( result => result.length > 0 ? result[0] : null );
    }
    
}