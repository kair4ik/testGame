<?php

/* @var $this yii\web\View */

use yii\web\View;

/* @var $game \app\models\Task */

$this->title = 'My Yii Application';
?>

<div class="site-index">
    <div class="jumbotron" >
        <h3><?php
           echo  isset($game['id']) ? $game['id'] : 'Подождите немного админ еще не добавил задания';
            ?></h3>
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
                if ($game['words'] != null) {
                    foreach ($game['words'] as $item) {
                        echo "<span class = 'word' id='word' data-word='$item'>$item</span>   ";
                    }
                }

                ?>
            </div>
            <div id="gameResultWindow">
                <p id="response"></p>
                <p id="statistic"></p>
                <button id='close'>Закрыть</button>
                <button id='next'>Следующее задание</button>
            </div>
            <?php
        }
            ?>
    </div>

</div>


<?php

$gameId = $game['id'];
$script = <<<JS

    initMoveWords();
    
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
                response = JSON.parse(data);
                $('#gameResultWindow').show();
                $('#response').html(response.text);
                
                if (response.status == 'loss') {
                    $("#next").hide();
                    $("#close").show();
                } else if (response.status == 'win') {
                    $("#close").hide();
                    $("#next").show();
                }
                console.log(data);
                
                $.ajax({
                    type: 'post',
                    url:'index.php?r=site/get-stat',
                    beforeSend: function (msg) {
                    },
                    success: function(data) {
                        var stat = JSON.parse(data);
                        $("#statistic").html(data);
                        console.log(stat);
                    }
	            });
                
                
                
                
            }
	    });
        
        
    });
    
     
     $('#gameResultWindow #close').click(function() {
       $(this).parent().hide();
     });
    
     
     $('#gameResultWindow #next').click(function() {
       location.reload();
     });
     
    
JS;
$this->registerJs($script, View::POS_END);
?>