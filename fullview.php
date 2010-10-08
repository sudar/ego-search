<?php
/**
 * Fullview for Ego Search YAP App
 *
 * @author: Sudar Muthu (http://sudarmuthu.com)
 */
require('../yos-social-php/lib/Yahoo.inc');
require('config.php');

YahooLogger::setDebug(true); // set it to false in prod

$yql_query = "select * from social.updates.search where query='%s'"; //YQL query to retrieve updates
?>

<style>
.y10main {
    margin: 10px;
}

.y10heading {
    display:block;
    line-height:10px;
}
.y10updates {
    list-style-type:none;
    margin:10px 0 10px -30px;
}

.y10update {
    border-bottom:1px solid #EFEFEF;
    margin:5px 0;
}

.y10photo {
    border:1px solid #EFEFEF;
    float:left;
    height:30px;
    margin:0 10px 5px 0;
    width:30px;
}

.y10updatedetail {
    float:left;
    width:90%;
}

img.yml-profile-pic {
    border:0;
}

.y10updatetext {
    color:#666666;
    font-size:12px;
    font-weight:normal;
    line-height:16px;
    margin:-2px 0 8px;
    padding-right:60px;
    text-align:justify;
}

.y10updatestamp {
    color:#999999;
    display:block;
    float:right;
    font-size:10px;
    margin-top:-10px;
}

.y10updates :last-child {
    border:medium none;
}

.y10clear {
    clear:both;
}
</style>
<div class="y10main"">
    <yml:form name="myform" params="fullview.php"  method="POST">
       Feed your ego:
       <input type="text" name="query" value ="<?php echo $_POST['query'];?>"/>
       <input type="submit" value="Go" />
    </yml:form>


<?php
if (isset ($_POST['query'])) {
    $app = new YahooApplication(CONSUMER_KEY, CONSUMER_SECRET);

    $query = $_POST['query'];
    $queryResponse = $app->query(sprintf($yql_query, $query));
    $updates = $queryResponse->query->results->update;
?>

<h3 class="y10heading">Results</h3>

<ul class="y10updates">
<?php
    foreach ($updates as $update) {
?>
        <li class="y10update">
            <div class="y10photo">
                <span class="yml-profile-pic">
                    <yml:profile-pic uid="<?php echo $update->profile_guid; ?>" width="24" linked="true" />
                </span>
            </div>
            <div class="y10updatedetail">
                <h5 class="y10updatetext">
                    <?php echo $update->title; ?>
                </h5>
                <span class="y10updatestamp">
                    <a href ="<?php echo $update->link;?>">
                        <?php echo computeTimeDifference($update->pubDate); ?> ago
                    </a>
                </span>
            </div>
            <div class="y10clear"></div>
        </li>
<?php
    }
?>
</ul>
</div>
<?php
}

/**
 * Calcualtes time differernce in human readable form
 *
 * @param <timestamp> $time
 * @return string formatted time
 */
function computeTimeDifference($time) {
    $seconds = time() - $time;
    if ($seconds < 60) {
        return $seconds == 1 ? $seconds . " second" : $seconds . " seconds";
    } else if ($seconds < 60*60) {
        $mins = round($seconds/60.0);
        return $mins == 1 ? $mins . " minute" : $mins . " minutes";
    } else if ($seconds < 24*60*60) {
        $hours = round($seconds/(60.0*60.0));
        return $hours == 1 ? $hours . " hour" : $hours . " hours";
    } else {
        $days = round($seconds/(24.0*60.0*60.0));
        return $days == 1 ? $days . " day" : $days . " days";
    }
}

?>