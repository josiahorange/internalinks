<?php


if(isset($_POST['submit'])) 
{ 

}

?>


<div id="oilWrapper">


    <form id="orangeoilform"  action="<?php echo $_SERVER['PHP_SELF'];?>?page=orangeinternallinks" method="post">

        <h2>Start a new Connection</h1>
        <p class="oilinfo">ALL LINKS ARE NO FOLLOW AND NEW TAB UNLESS CHANGED</p>
        <div class="connection_wrapper">  

           
    
                
                    <div>
                        <label for="">Key Word</label>
                        <input id="oil_keyword" name="oil_keyword" type="text">
                    </div>
                    <div>
                        <label for="">Link</label>
                        <input id="oil_link" name="oil_link" type="text">
                    </div>    
                    <div>
                        <label for="">Turn Off No Follow?</label>
                        <input id="oil_follow" name="oil_follow" type="checkbox">
                    </div>
                    <div>
                    <label for="">Turn Off New Tab?</label>
                        <input id="oil_newtab" name="oil_newtab" type="checkbox">
                    </div>

                    <p class="status" id="status"></p>



    


        </div> 

        <input onclick="oillinksubmit();" class="hvr-grow" id="oilbutton" type="button" name="submit" value="Add Connection">

    </form> 


    <?php 
        $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
        if (file_exists($root.'/wp-load.php')) {
        // WP 2.6
        require_once($root.'/wp-load.php');
        } else {
        // Before 2.6
        require_once($root.'/wp-config.php');
        }
        
        global $wpdb;

        $tablename = $wpdb->prefix . "internallinks";

        $results = $wpdb->get_results( "SELECT * FROM $tablename ORDER BY key_word ASC"); 

        echo '
               <div id="current_connection_wrapper">
               <h2>Current Link Connections</h2>
                <p class="oilinfo">Click To Edit Values</p>
                <table>
                    <tr id="current_top_row">
                        <th></th>
                        <th>KeyWord</th>
                        <th>Link</th>
                        <th class="checkbox_column">Follow?</th>
                        <th class="checkbox_column">Same Tab?</th>
                        <th>Update Link</th>
                        <th>DELETE Link</th>
                        <th>Duplicate Link</th>
                        <th>Count</th>
                    </tr>

                        ';

         

        $totalcount = 0;
        $postcount = 0;
        $linklocations = array(array());
        if(!empty($results)){
            foreach($results as $row){  

                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1
                );

                $postcount = 0;
                $count = 0;

                $post_query = new WP_Query($args);
                if($post_query->have_posts() ) {
                    while($post_query->have_posts() ) {
                        $post_query->the_post();
                        $postcount++;
                        if(preg_match('/\b' . $row->key_word . '\b/', get_the_content())){
                       
                            $linklocations[$count][0] = get_the_title();
                            $linklocations[$count][1] = get_the_permalink();

                            $totalcount++;
                            $count++;

                            
                        }
                    }

                }
                
                //check for broken links
                $headers = @get_headers($row->link);
                if($headers && strpos( $headers[0], '200')) {
                    $urlstatus = '<span class="dashicons dashicons-admin-links"></span>';
                }
                else if(!$headers | strpos( $headers[0], '404')){
                    $urlstatus = '<span class="dashicons dashicons-editor-unlink"></span>';
                }
                else{
                    $urlstatus = '<span class="dashicons dashicons-editor-help"></span>';
                }


                if ($row->newtab == "1" ){
                    
                    $newtab = "checked=\"checked\"";
                }
                else{
                    $newtab = "";
                }
                if ($row->follow == "1" ){

                    $follow = "checked=\"checked\"";
                }
                else{
                    $follow = "";
                }
                $homeurlstatus = '';
                $url = get_home_url();
                if(strpos($row->link, $url) !== false){
                    $homeurlstatus = 'ownurl'; 
                }
                echo '<tr class="linkforms" id="' . $row->id . 'oillink">
                        <td>' .  $urlstatus . '</td>
                        <td><input class="oil_textnoback" id="' . $row->id . 'oil_keyword" type="text" value="' . $row->key_word . '"></td>
                        <td><input class="oil_textnoback oil_linkinput ' . $homeurlstatus . '" id="' . $row->id . 'oil_link" type="text" value="' . $row->link . '"></td>
                        <td class="checkbox_column"><input class="oil_checkbox" id="' . $row->id . 'oil_follow" type="checkbox" ' . $follow . '></td>
                        <td class="checkbox_column"><input class="oil_checkbox" id="' . $row->id . 'oil_newtab" type="checkbox" ' . $newtab . '></td>
                        <td><input class="oil_update hvr-grow" value="Update" type="button" onclick="oilupdatelink(' . $row->id . ')"></td>
                        <td><input class="oil_delete hvr-grow" value="Delete" type="button" onclick="oilcheckdelete(' . $row->id . ')"></td>
                        <td><input class="oil_duplicate hvr-grow" value="Duplicate" type="button" onclick="oilduplicatelink(' . $row->id . ')"></td>
                        <td>' . $count . '</td>
                        <td><a class="LLopen" onclick="openLL(' . $row->id . ')"><span class="dashicons dashicons-arrow-down-alt2"></span></a></td>
                    </tr>
                    <p class="status" id="' . $row->id . 'status"></p>

                    <div id="' . $row->id . 'linklocations" class="link_locations">
                        <h2>Link Locations</h2>
                        ';
                        for ($x = 0; $x <= $count - 1; $x++) { 
                            $y = $x + 1;
                            echo '<p><a target="_blank" rel="nofollow noreferrer noopener" href=" ' . $linklocations[$x][1] .  ' ">'
                                        . $y . ': ' . $linklocations[$x][0] . 
                                 '</a></p>';
                        }

                    echo '<a class="LLclose" onclick="closeLL(' . $row->id . ')"><span class="dashicons dashicons-no"></span></a>
                    </div>';


            }
        }

        echo '</table>
                <p class="oilinfo" >Searched <b>' . $postcount . '</b> Posts   |   Total of <b>' . $totalcount . '</b> Links Generated</p>
                </div>';
    ?>  


<div id="page_analysis_wrapper">

    <h2>Content Analysis</h1>
    
    <?php 

    echo '
        <p><b style="color:red">Less than 2</b> | <b style="color:orange">Less than 5</b> | <b style="color:green">More than 10 </b></p>

        <table>
        <tr>
            <th>Title</th>
            <th>Manual Text</th>
            <th>Manual Image</th>
            <th>Plugin</th>
            <th>Total Links</th>
            <th>Word Count</th>


        </tr>
    
    ';
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1
    );

    $localtotallinkcount = 0;
    $totallinkcount = 0;

    $manuallinkcount = 0;
    $manualtextlinkcount = 0;

    $localmanuallinkcount = 0;
    $localtextlinkcount = 0;

    $totalplugincount = 0;

    $postwordcount = 0;
    $totalpostwordcount = 0;

    $post_query = new WP_Query($args);
    if($post_query->have_posts() ) {
        while($post_query->have_posts() ) {
            $post_query->the_post();


            $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            if (file_exists($root.'/wp-load.php')) {
            // WP 2.6
            require_once($root.'/wp-load.php');
            } else {
            // Before 2.6
            require_once($root.'/wp-config.php');
            }
            
            global $wpdb;
    
            $tablename = $wpdb->prefix . "internallinks";
    
            $results = $wpdb->get_results( "SELECT * FROM $tablename ORDER BY key_word ASC"); 
            $plugincount = 0;
            if(!empty($results)){
                foreach($results as $row){  
                    if(preg_match('/\b' . $row->key_word . '\b/', get_the_content())){
                        $totalplugincount++;
                        $plugincount++;
                        $postwordcount = str_word_count(apply_filters('the_content', get_the_content()));
                        $totalpostwordcount = $totalpostwordcount + $postwordcount;

                    }

                }
            }

            $localmanuallinkcount = preg_match_all('#(<\s*?a\b[^>]*>)(.*?)</a\b[^>]*>#s', get_the_content());
            $localtextlinkcount = preg_match_all('#(<\s*?a\b[^>]*>(?!<\s*?img\b[^>]*>))(.*?)</a\b[^>]*>#s', get_the_content());
            $localimagelinkcount = $localmanuallinkcount - $localtextlinkcount;
            $localtotallinkcount = $localmanuallinkcount + $plugincount;
            $totallinkcount = $totallinkcount + $localtotallinkcount;
            

            if($plugincount <= 2){
                echo '<tr class="redrow">';
            }
            else if ($plugincount <=5){
                echo '<tr class="orangerow">';
            }
            else if($plugincount >= 10){
                echo '<tr class="greenrow">';

            }
            else{
                echo '<tr>';
            }
            echo '
                <td><a target="_blank" href="' . get_the_permalink() . '">' . get_the_title() .'</a></td>
                <td>' . $localtextlinkcount .'</td>
                <td>' . $localimagelinkcount .'</td>
                <td class="highcell">' . $plugincount .'</td>
                <td>' . $localtotallinkcount .'</td>
                <td>' . $postwordcount .'</td>


            
            ';
            $manuallinkcount = $manuallinkcount + preg_match_all('#(<\s*?a\b[^>]*>)(.*?)</a\b[^>]*>#s', get_the_content());
            $manualtextlinkcount = $manualtextlinkcount + preg_match_all('#(<\s*?a\b[^>]*>(?!<\s*?img\b[^>]*>))(.*?)</a\b[^>]*>#s', get_the_content());



        }

    }
    $imagelinkcount = $manuallinkcount - $manualtextlinkcount;

    echo '
        <tr class="highcell" style="">
            <td>Total Analysis</td>
            <td>' . $manualtextlinkcount .'</td>
            <td>' . $imagelinkcount .'</td>
            <td>' . $totalplugincount .'</td>
            <td>' . $totallinkcount .'</td>
            <td>' . $totalpostwordcount .'</td>
        </tr>
    
    </table>
    ';


?>


</div>


</div>  









<script>    


function openLL(id){
    var box = document.getElementById(id + "linklocations");
    box.style.display = "block";

}
function closeLL(id){
    var box = document.getElementById(id + "linklocations");
    box.style.display = "none";

}
//Database Submittion Javascript

function oillinksubmit()
{
    jQuery(function($) {

        var oil_link = "";
        var oil_keyword = "";
        var oil_follow = "";
        var oil_newtab = "";

        oil_link = document.getElementById("oil_link").value;
        oil_keyword = document.getElementById("oil_keyword").value;
        oil_follow = $("#oil_follow").is(":checked") ? "True" : "False";;
        oil_newtab = $("#oil_newtab").is(":checked") ? "True" : "False";;

        if(oil_link && oil_keyword){
            $.ajax
            ({
                type:'post',
                url:'<?php echo plugins_url( '../formprocess/dbsubmit.php', __FILE__)  ?>',
                data:
                {
                    oillink:oil_link,
                    oilkeyword:oil_keyword,
                    oilfollow:oil_follow,
                    oilnewtab:oil_newtab,

                },
                success: async function (response)
                {
                    location.reload(); 
                    document.getElementById("oil_keyword").value="";
                    document.getElementById("oil_link").value="";
                    document.getElementById("status").innerHTML="Internal Links Constructed";
                    await delay(3000);           
                    document.getElementById("status").innerHTML="";
                },
                error: function(jqxhr, status, exception) {
                 alert('Exception:', exception);
                }
            });
        }
        return false;

});

}


async function oilupdatelink(id)
{
    jQuery(function($) {

        var oil_link = "";
        var oil_keyword = "";
        var oil_follow = "";
        var oil_newtab = "";

        oil_link = document.getElementById(id + "oil_link").value;
        oil_keyword = document.getElementById(id + "oil_keyword").value;
        oil_follow = $("#" + id + "oil_follow").is(":checked") ? "True" : "False";
        oil_newtab = $("#" + id + "oil_newtab").is(":checked") ? "True" : "False";

        if(oil_link && oil_keyword){
            $.ajax
            ({
                type:'post',
                url:'<?php echo plugins_url( '../formprocess/dbupdate.php', __FILE__)  ?>',
                data:
                {
                    oillink:oil_link,
                    oilkeyword:oil_keyword,
                    oilfollow:oil_follow,
                    oilnewtab:oil_newtab,
                    oilid:id,

                },
                success: async function (response)
                {
                    document.getElementById(id + "status").innerHTML="Internal Link Network Updated";
                    await delay(3000);           
                    document.getElementById(id + "status").innerHTML="";


                },
                error: function(jqxhr, status, exception) {
                 alert('Exception:', exception);
                }
            });
        }

  return false;

});

}

function oilcheckdelete(id){
    if(confirm("Are you sure you want to delete this internal link?")){
        oildeletelink(id);
    }
}

const delay = ms => new Promise(res => setTimeout(res, ms));

async function oildeletelink(id)
{
    var linkform = document.getElementById(id + "oillink");
    linkform.style.backgroundColor = "red";
    linkform.style.opacity = "0.1";
    document.getElementById(id + "status").style.color="red";
    document.getElementById(id + "status").innerHTML="LINK DELETED";
    await delay(1000);           
    document.getElementById(id + "status").style.display="none";
    linkform.style.display = "none";
    jQuery(function($) {   
        if(true){
            $.ajax
            ({
                type:'post',
                url:'<?php echo plugins_url( '../formprocess/dbdelete.php', __FILE__)  ?>',
                data:
                {
                    oilid:id,

                },
                success: function (response)
                {
                    document.getElementById(id + "status").innerHTML="LINKS DELETED";
                },
                error: function(jqxhr, status, exception) {
                 alert('Exception:', exception);
                }
            });
        }

  return false;

});

}


async function oilduplicatelink(id)
{
    jQuery(function($) {

        var oil_link = "";
        var oil_keyword = "";
        var oil_follow = "";
        var oil_newtab = "";

        oil_link = document.getElementById(id + "oil_link").value;
        oil_keyword = document.getElementById(id + "oil_keyword").value;
        oil_follow = $("#" + id + "oil_follow").is(":checked") ? "True" : "False";
        oil_newtab = $("#" + id + "oil_newtab").is(":checked") ? "True" : "False";

        if(oil_link && oil_keyword){
            $.ajax
            ({
                type:'post',
                url:'<?php echo plugins_url( '../formprocess/dbduplicate.php', __FILE__)  ?>',
                data:
                {
                    oillink:oil_link,
                    oilkeyword:oil_keyword,
                    oilfollow:oil_follow,
                    oilnewtab:oil_newtab,
                    oilid:id,

                },
                success: async function (response)
                {
                    location.reload(); 
                    document.getElementById(id + "status").value="Link Duplicated";
                    await delay(3000);           
                    document.getElementById(id + "status").innerHTML="";


                },
                error: function(jqxhr, status, exception) {
                 alert('Exception:', exception);
                }
            });
        }

  return false;

});

}
</script>



