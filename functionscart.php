function woo_custom_search(){
    ?>
    <input type="text" id="search" onkeyup="myfunc()" placeholder="search string">
    <div id="dispprod"></div>
    <script>
        function myfunc() {
            var str=document.getElementById('search').value;
            //alert(str);
            if(str.length>=3) {
                //alert(str);
                var data = {
                    action: 'search_fetch',
                    s: str
                };
                var ajaxurl = "http://localhost/wordpresswoocustomsearch/wp-admin/admin-ajax.php";
                jQuery.post(ajaxurl, data, function (response) {
                    //alert(response);
                    console.log(response);
                    var d=JSON.parse(response);
                    //console.log(d);
                    document.cookie="res="+response;
                    //document.cookie="res="+response;
                    <?php //print_r(json_decode($_COOKIE['res'])); ?>
                    //console.log(d.length);
                    var html='<table border="1">';
                    for(var i=0;i<d.length;i++)
                    {
                        console.log(d[i]);
                        html+="<tr>";
                        html+="<td>"+d[i].post_title+"</td>";
                        html+="<td>"+d[i].post_content+"</td>";
                        html+="<td><a href='http://localhost/wordpresswoocustomsearch/cart/?add-to-cart="+d[i].ID +"'>Add to cart</a> </td>";
                        html+="</tr>";
                    }
                    html+="</table>";
                    document.getElementById('dispprod').innerHTML= html
                });
            }
        }
    </script>
    <?php

}
add_shortcode('woosearch','woo_custom_search');

function search_fetch(){
    $s=$_POST['s'];
    global $wpdb;
    $result=$wpdb->get_results("select * from $wpdb->posts WHERE post_type='product' AND (post_content LIKE '%$s%' OR post_title LIKE '%$s%' OR post_excerpt LIKE '%$s%')");
    echo json_encode($result);
    die();
}
add_action('wp_ajax_search_fetch', 'search_fetch');
add_action('wp_ajax_nopriv_search_fetch', 'search_fetch');