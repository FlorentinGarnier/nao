$(document).ready(function(){

    $('#form_search').on('click', function() { // When click on an element in the list
        $('#loading').css("display", "block");
    });

    $('#pagination-container').pagination({
        dataSource: datas,
        pageSize: 9,
        className: 'paginationjs-theme paginationjs-small',
        callback: function(data, pagination) {
            var html = simpleTemplating(data);
            $('#data-container').html(html);
        }
    });

    function simpleTemplating(data) {
        var html = '<hr/>';
        html += '<div id="fiches-area">';

        $.each(data, function(index, item){
            html += '<div class="fiche">';
            html += '<p>Ordre : ' + data[index][0] +'</p>';
            html += '<p>Famille : ' + data[index][1] +'</p>';
            html += '<p>Nom vulgaire : ' + data[index][2] +'</p>';
            html += '<p>Nom vulgaire anglais : ' + data[index][3] +'</p>';
            html += '<p>Nom scientifique : ' + data[index][4] +'</p>';
            html += '<p>Habitat : ' + data[index][5] +'</p>';
            if(data[index][6] > 0) {
                html += '<p>' + data[index][6] +' observation(s) faite(s) à cet endroit</p>';
            }
            html += '</div>';
        });

        html += '</div>';
        return html;
    }
});