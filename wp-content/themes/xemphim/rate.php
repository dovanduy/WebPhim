 <?php

$id = $_POST["id"];
$rating = $_POST["rating"];
$rating_type = array("like", "dislike");

if(in_array($rating, $rating_type)){
    
    include("settings.php"); //INCLUDES THE IMPORTANT SETTINGS
    
    //CHECKS IF $id EXISTS
    $q = mysql_query("SELECT * FROM $content WHERE id='$id'");
    $r = mysql_fetch_assoc($q);
    $id = $r["id"]; //NEW ID VARIABLE, USED TO CHECK IF IT'S IN THE DATABASE
    
    //COUNTS LIKES & DISLIKES IF $id EXISTS
    if($id)
    {
        //CHECKS IF USER HAS ALREADY RATED CONTENT
        $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND ip='$ip'");
        $r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM
        
        //IF USER HAS ALREADY RATED
        if($r["rating"]){
            if($r["rating"]==$rating){
                mysql_query("DELETE FROM ratings WHERE id='$id' AND ip='$ip'"); //DELETES RATING
            } else {
                mysql_query("UPDATE ratings SET rating='$rating' WHERE id='$id' AND ip='$ip'"); //CHANGES RATING
            }
        } else {
            mysql_query("INSERT INTO ratings VALUES('$rating','$id','$ip')"); //INSERTS INITIAL RATING
        }
        
        //COUNT LIKES & DISLIKES
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
    
        //EVERYTHING HERE DISPLAYED IN HTML AND THE "ratings" ELEMENT FOR AJAX
        echo $m;
    }
    else
    {
    echo "Invalid ID";
    }
}

?> 