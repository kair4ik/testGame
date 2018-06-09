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

    #gameResultWindow{
        display: none;
        width: 300px;
        height: 300px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -150px 0 0 -150px;
        background: white;
        border: 1px solid black;
        border-radius: 20px ;
        padding: 10px;
    }

</style>
<div class="site-index">
    <div class="jumbotron" >
        <h2>Игра "Как бы написал автор?"</h2>
        <?php
        if (!Yii::$app->user->isGuest) {
        ?>
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
            foreach ($game['words'] as $item) {
                echo "<span class = 'word' id='word' data-word='$item'>$item</span>   ";
            }
            ?>
        </div>

        <?php
            $bookName = "Исскусво мыслить масштабно";
//            $games = \app\models\Statistic::find()
//                ->joinWith('task', true)
//                ->where(['book_name' => $bookName])
//                ->andWhere(['user_id' => Yii::$app->user->id])
//                ->count();
            $countGames = \app\models\Statistic::getAmountGamesByBook($bookName);
            echo "<pre>";
//            echo \app\models\Statistic::getAmountWinForUser();
            var_dump(\app\models\Statistic::getAverage('loss'));
            echo "</pre>";

        }
        ?>

        <div id="gameResultWindow">
            <p id="response"></p>
            <p id="statistic"></p>
            <button>Закрыть</button>
        </div>

    </div>

</div>


<?php

$gameId = $game['id'];
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

    function getGameResult() {
        
    }
    
    $("#send").click(function() {
        
        suggestion = getSuggestionFromDivById('suggestion');
        console.log(suggestion);
        gameId = $gameId;
        
        $.ajax({
            type: 'post',
            url:'index.php?r=site/get-result&gameId='+gameId+'&suggestion='+suggestion,
            beforeSend: function (msg) {
                // alert("Картинка скоро будет загружена");
            },
            success: function(data) {
                $('#gameResultWindow').show();
                $('#response').text(data);
                console.log(data);
            }
	});
        
        
    });
    
     $('#gameResultWindow button').click(function() {
       $(this).parent().hide();
     });

    
JS;
$this->registerJs($script, View::POS_END);
?>