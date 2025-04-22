// https://stackoverflow.com/questions/10420352/converting-file-size-in-bytes-to-human-readable-string
function humanFileSize( bytes , si = false , dp = 1 ) {
    const thresh = si ? 1000 : 1024;

    if (Math.abs(bytes) < thresh) {
    return bytes + ' B';
    }

    const units = si 
        ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] 
        : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
    let u = -1;
    const r = 10**dp;

    do {
    bytes /= thresh;
    ++u;
    } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


    return bytes.toFixed(dp) + ' ' + units[u];
}

export { humanFileSize };

export default [
    { field: "id"           , title: "Arquivo Nº" , visible: true , sortable: true },
    { field: "anexado_em"   , title: "Anexado em" , visible: true , sortable: true , type: 'datetime' },
    { field: "usuario.nome" , title: "Usuário"    , visible: true , sortable: true },
    { field: 'titulo'       , title: "Título"     , visible: true , sortable: true },
    { field: 'descricao'    , title: "Descrição"  , visible: true , sortable: true },

    { field: 'nome'         , title: "Nome"       , visible: false , sortable: true },
    { field: 'tipo'         , title: "Tipo"       , visible: false , sortable: true },
    { field: 'tamanho'      , title: "Tamanho"    , visible: false , sortable: true , format: ( data , value ) => humanFileSize( value ) },
];