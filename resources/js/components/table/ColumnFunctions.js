import columnType from "./ColumnType.js";
import service from "@/services/Service";

function getType( col ){
    return col.type && columnType[ col.type ]
        ? columnType[ col.type ]
        : {};
};

function getClass( col , data ){
    let classz = (this.getType( col ).class ?? '') + ' ';

    if( getLink( col , data ) ) 
        classz += "cursor-pointer underline text-primary ";

    if( col.getClass ) return classz + col.getClass( data );
    return classz;
}

function getValue( col , data ){
    if( col.field ){
        let d = data;
        
        col.field.split( "." ).forEach( name => {
            d = d == null ? null : d[ name ];
        });

        return d;
    }
    
    return null;
}

function format( col, data , value ){
    if( col.type == 'options' && !Array.isArray( col.options ) ){
        col.options.then( r => col.options = r );
        return ;
    }

    let typeFormat = (this.getType( col ).format ?? null);
    
    if( col.format ) return col.format( data , value );
    else if( typeFormat ) return typeFormat( value , data , col );
    else return value;
}

function getFooterValue( col , data ){
    if( col.footer ){
        let typeFormat = (this.getType( col ).format ?? null);
        let value = col.footer( data );

        return typeFormat == null 
            ? value
            : typeFormat( value );
    }
    
    return null;
}

function getFooterClass( col ){
    return this.getType( col ).class ?? '';
}

function getColumnsSelected( columns , selected ){
    let result = [];

    columns.forEach( column => {
        if( selected.includes( column.field ) || selected.includes( column.title ) ){
            result.push( column );
        }
    });

    return result;
}

function getLink( column , data ){
    if( !column.link ) return null;

    let link = typeof column.link === 'string'
        ? column.link
        : column.link( data , getValue( column , data ) );

    return link;
}

function click( column , data , table ){
    if( column.click ) column.click( data , table );

    let link = getLink( column , data );
    if( link ) service.view.$router.push( link );
}

export { getType, getClass, getValue, format, getFooterClass, getFooterValue , getColumnsSelected , click };