<?php
/* @var $article Article */

use app\modules\page\models\Article;

?>

<style>
    #rating {
        unicode-bidi: bidi-override;
        direction: rtl;
        text-align: center;
    }

    #rating > span {
        display: inline-block;
        position: relative;
        width: 1.1em;
    }


    #rating > span:hover {
        cursor: pointer;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  var articleRating = <?=$article->rating?>;

  function vote (rating) {
    setVote(rating)
    $.get('/vote.html?article_id=<?=$article->id?>&rating=' + rating <?php if ($article->status === Article::STATUS_LINK) {
        echo '+ \'&link='.$article->link.'\'';
    } ?>)
      .done(function () {
        articleRating = rating
        document.getElementById('rating-result').innerHTML = 'The vote has been accepted'
      })
      .fail(function (data) {
        setVote(articleRating)
        document.getElementById('rating-result').innerHTML = data.responseText
      })
  }

  function setVote (rating) {
    if (rating >= 1) {
      document.getElementById('star_1').style.color = 'gold'
    } else {
      document.getElementById('star_1').style.color = 'gray'
    }
    if (rating >= 2) {
      document.getElementById('star_2').style.color = 'gold'
    } else {
      document.getElementById('star_2').style.color = 'gray'
    }
    if (rating >= 3) {
      document.getElementById('star_3').style.color = 'gold'
    } else {
      document.getElementById('star_3').style.color = 'gray'
    }
    if (rating >= 4) {
      document.getElementById('star_4').style.color = 'gold'
    } else {
      document.getElementById('star_4').style.color = 'gray'
    }
    if (rating >= 5) {
      document.getElementById('star_5').style.color = 'gold'
    } else {
      document.getElementById('star_5').style.color = 'gray'
    }
  }
</script>

<div id="rating">
    <span id="star_5" onclick="vote(5)" onmouseenter="setVote(5)" onmouseleave="setVote(articleRating)">☆</span>
    <span id="star_4" onclick="vote(4)" onmouseenter="setVote(4)" onmouseleave="setVote(articleRating)">☆</span>
    <span id="star_3" onclick="vote(3)" onmouseenter="setVote(3)" onmouseleave="setVote(articleRating)">☆</span>
    <span id="star_2" onclick="vote(2)" onmouseenter="setVote(2)" onmouseleave="setVote(articleRating)">☆</span>
    <span id="star_1" onclick="vote(1)" onmouseenter="setVote(1)" onmouseleave="setVote(articleRating)">☆</span>
</div>
<div style="text-align: center">
    <p id="rating-result"></p>
</div>

<script>
  setVote(articleRating)
</script>