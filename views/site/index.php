<?php

/* @var $this yii\web\View */

use yii\web\View;

/* @var $game \app\models\Task */

$this->title = 'My Yii Application';
?>

<style>
    .word {
        padding: 5px;
        border: 1px solid green;
        cursor: pointer;
        border-radius: 5px;
    }

    .suggestion{
        width: 100%;
        height: 50px;
        border: 1px solid green;
        border-radius: 5px;
        padding: 10px;
    }

    .random-words{

        width: 100%;
        height: 50px;
        border: 1px dotted gray;
        border-radius: 5px;
        padding: 10px;
    }

</style>
<div class="site-index">
    <div class="jumbotron">
        <h2>Игра "Как бы написал автор?"</h2>
        <br>
        <br>
        <div class="suggestion" id="suggestion">

        </div>
        <br>
        <button class="btn btn-success" id="send">Проверить</button>
        <br>
        <br>

        <div class="random-words" id="random-word">
            <?php
            foreach ($game as $item) {
                echo "<span class = 'word' id='word' data-word='$item'>$item</span>   ";
            }
            ?>
        </div>
    </div>

</div>


<?php
$script = <<<JS

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
    
    initMoveWords();
    
    $("#send").click(function() {
        sugg = getSuggestionFromDivById('suggestion');
        console.log(sugg);
    });

    
JS;
$this->registerJs($script, View::POS_END);
?>