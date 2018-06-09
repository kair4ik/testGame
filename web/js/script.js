function initMoveWords(){
    $("div #word").click(function() {

        parentId = $(this).parent().attr('id');
        if (parentId == "suggestion") {
            $("div #random-word").append(this);
            $("div #random-word").append("   ");

        } else if (parentId == "random-word"){
            $("div #suggestion").append(this);
            $("div #suggestion").append("   ");
        }
    });
}

function getSuggestionFromDivById(id) {
    newSuggestion = '';
    words = $('#'+id).html();
    wordArray = words.split("   ");
    newWordArray = [];
    $.each( wordArray, function(key, value ) {
        value = $.trim(value);
        if (value != "" && value != "\\n"){
            newWordArray.push(value);
            newSuggestion += $(value).data("word") + " ";
        }
    });
    return newSuggestion;
}