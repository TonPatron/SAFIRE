$(document).ready(function() {
    var path = "{{ path('recherche') }}";
    $("#kup").keyup(function (e) { 
        console.log($("#kup").val());
        
        var data =  $("#kup").val();
        $.ajax({
            url: path,
            data: data,
            type: "GET"

        }).then(function(data) {
            console.log(data);
        });
    });

    /* $('.js-user-autocomplete').each(function() {
        var autocompleteUrl = $(this).data('autocomplete-url');

        
        $(this).autocomplete({hint: false}, [
            {
                source: function(query, cb) {
                    $.ajax({
                        url: autocompleteUrl+'?query='+query
                    }).then(function(data) {
                        cb(data.anime);
                    });


                },

                displayKey: 'titre',
                debounce: 500 // only request every 1/2 second

            }
        ])


    }); */
});
