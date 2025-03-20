//from: https://stackoverflow.com/questions/14964035/how-to-export-javascript-array-info-to-csv-on-client-side
function exportToCSV( rows ) {
    var processRow = function (row) {
        var finalVal = '';
        for (var j = 0; j < row.length; j++) {
            var innerValue = row[j] === null ? '' : row[j].toString();
            if (row[j] instanceof Date) {
                innerValue = row[j].toLocaleString();
            };
            var result = innerValue.replace(/"/g, '""');
            if (result.search(/("|,|\n)/g) >= 0)
                result = '"' + result + '"';
            if (j > 0)
                finalVal += ',';
            finalVal += result;
        }
        return finalVal + '\n';
    };

    var csvFile = '';
    for (var i = 0; i < rows.length; i++) {
        csvFile += processRow(rows[i]);
    }

    var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, "export.csv");
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", "export.csv");
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}

//from: https://pt.stackoverflow.com/questions/171353/exportar-para-excel-via-javascript-colocar-nome-no-arquivo
function exportToExcel( rows ){
    let BOM = "\uFEFF";
    let table = "<table>";

    table += rows.map( tr => {
        let r = tr.map( td => {
            //td = td ? td.replace(/ /g, '%20') : '';
            return "<td>" + td + "</td>";
        });

        return "<tr>" + r.join( '' )  + "</tr>"
    } ).join( '' );
    
    table += "</table>";

    //donwload( 'export.xls' , 'data:application/vnd.ms-excel' , table );
    tableToExcel( table , "Export" );
}

var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,'
      , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>{table}</body></html>'
      , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
      , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
    return function(table, name) {
      var ctx = {worksheet: name || 'Worksheet', table: table };
      window.location.href = uri + base64(format(template, ctx));
    };
})();

function convert(  ){

}

function toRows( service , params , columns ){
    let rows = [];

    service.search( params ).then( result => {
        result.data.forEach( element => {
            rows.push( toColumn( element , columns ) );
        });
    });

    if( params.current_page < params.total ){
        params.page = params.current_page++;

        rows = rows.concat( rows , params , columns );
    }

    return rows;
}

function toColumn( datas , columns ){
    return datas.map( data => {
        return columns.map( col => {
            if( col.html ) return col.html( data );
            else if( getType( col ).html ) return getType( col ).html( data );
            else return format( col , data , getValue( col , data ) );
        });
    });
}

function donwload( filename , type , data ){
    let a = document.createElement('a');
    a.href = type + ', ' + data;
    a.download = filename;
    a.click();
    //e.preventDefault();
}

export { exportToCSV, exportToExcel, donwload, toColumn, toRows };