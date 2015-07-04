 <?php

//GOES TO ID 1 AUTOMATICALLY IF THERE'S NO ID IN THE URL


include("settings.php"); //INCLUDES THE IMPORTANT MySQL SETTINGS

$q = mysql_query("SELECT * FROM $content WHERE id='$id'"); //GETS THE CONTENT ID
$r = mysql_fetch_assoc($q);
$con = $r["content"]; //CONTENT OF THE ID
$id = $r["id"]; //NEW ID VARIABLE, USED TO CHECK IF IT'S IN THE DATABASE

?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
function rate(rating){ //'rating' VARIABLE FROM THE FORM in view.php
var data = 'rating='+rating+'&id=<?php echo $id; ?>';

$.ajax({
type: 'POST',
url: 'rate.php', //POSTS FORM TO THIS FILE
data: data,
success: function(e){
$("#ratings").html(e); //REPLACES THE TEXT OF view.php
}
});
}
</script>
<style>
/*GIVES THE BUTTONS AND CANCEL LINK THE POINTER ON MOUSEOVER*/
#like, #dislike, #cancel {
cursor: pointer;
}
/*GIVES THE CANCEL BUTTON AN UNDERLINE ON MOUSEOVER*/
#cancel:hover {
text-decoration: underline;
}
</style>
<?php

//IF $id EXISTS, THEN COUNT LIKES & DISLIKES
if($id){
    //COUNTS THE TOTAL NUMBER OF LIKES &amp; DISLIKES
    $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND rating='like'");
    $likes = mysql_num_rows($q);
    $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND rating='dislike'");
    $dislikes = mysql_num_rows($q);
    
    //LIKE & DISLIKE IMAGES
    $l = 'http://wcetdesigns.com/images/buttons/l_color.png';
    $d = 'http://wcetdesigns.com/images/buttons/d_color.png';
    
    //CHECKS IF USER HAS ALREADY RATED CONTENT
    $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND ip='$ip'");
    $r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM
    
    //IF SO, THE RATING WILL HAVE A SHADOW
    if($r["rating"]=="like"){
        $l = 'http://wcetdesigns.com/images/buttons/l_color_shadow.png';
    }
    if($r["rating"]=="dislike"){
        $d = 'http://wcetdesigns.com/images/buttons/d_color_shadow.png';
    }
    
    //FORM & THE NUMBER OF LIKES & DISLIKES
    $m = '<img id="like" onClick="rate($(this).attr(\'id\'))" src="'.$l.'"> '.$likes.' &nbsp;&nbsp; <img id="dislike" onClick="rate($(this).attr(\'id\'))" src="'.$d.'"> '.$dislikes;
    
    //EVERYTHING HERE DISPLAYED IN HTML
    echo $con.'<br><br><br><div id="ratings">'.$m.'</div>';
}
else
{
echo "Invalid ID";
}

?> 