<?php
////http://localhost:8983/solr/magento/spell?q=shitr%20ultrashar&spellcheck=true&spellcheck.collate=true&spellcheck.build=true&indent=on&wt=json
header('Access-Control-Allow-Origin: *');
include_once ("config.php");

$term=urlencode($_REQUEST['term']);
//$start=$_REQUEST['start'];
$start=0;
//$limit=$_REQUEST['limit'];
$limit=1000;

$category=[]; //////// the limit of suggestions
$combined_result=[]; /// to get result from all levels
$array_index=0; // global array counter

//function solr_curl($handler,$min_price,$max_price,$total){
//    $mainLink=Solr_url.'spell?q'.$total.'&_getMiPr='.$min_price.'&_getMxPr='.$max_price.'&_prdKyid='.$ids;
$full_category_search=Solr_url."select?hl.fl=name,brand,level1_category,level2_category&hl.simple.pre=<strong>&hl.simple.post=</strong>&hl=on&indent=on&q=level1_category:".$term."*&stats=true&wt=json&fq=is_in_stock:1";
$category_search_result=json_decode(solr_curl($full_category_search),true);

$full_brand_search=Solr_url."select?hl.fl=name,brand,level1_category,level2_category&hl.simple.pre=<strong>&hl.simple.post=</strong>&hl=on&indent=on&q=brand_c:".$term."*&stats=true&wt=json&fq=is_in_stock:1";
$brand_search_result=json_decode(solr_curl($full_brand_search),true);


if($category_search_result['response']['numFound'] > 0){

    category_search($term,$limit,$start);
}
else if($brand_search_result['response']['numFound'] > 0){
    brand_search($term,$limit,$start);
}
else{
    name_search($term,$limit,$start);
}

/*category search*/
function category_search($term,$limit,$start){
    global $combined_result;
    global $category;
    global $array_index;
    //$main_result=json_decode(solr_curl("select",$term,$start,$limit),true);
    $fullLink=Solr_url."select?hl.fl=name,brand,level1_category,level2_category&hl.simple.pre=<strong>&hl.simple.post=</strong>&hl=on&indent=on&q=level1_category:".$term."*&rows=".$limit."&start=".$start."&stats=true&wt=json&fq=is_in_stock:1";
    $main_result=json_decode(solr_curl($fullLink),true);
    //$category=[];



    if($main_result['response']['numFound'] > 0)
    {
        //$array_index=0;

        foreach ($main_result['response']['docs'] as $doc){

            if (empty($category)){

                $category[]=$doc['level2_category'];
                $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                $highlight_field_type=key($main_result['highlighting'][$doc['id']]);

                //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);



                /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                $combined_result[$array_index]['id']=$doc['id'];
                $combined_result[$array_index]['name']=$doc['level1_category'];
                $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                $combined_result[$array_index]['type']=$highlight_field_type;
                $array_index++;
            }
            else if (!in_array($doc['level2_category'], $category)) {
                $category[]=$doc['level2_category'];
                $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                $highlight_field_type=key($main_result['highlighting'][$doc['id']]);
                //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);



                /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                $combined_result[$array_index]['id']=$doc['id'];
                $combined_result[$array_index]['name']=$doc['level1_category'];
                $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                $combined_result[$array_index]['type']=$highlight_field_type;
                $array_index++;
            }


            if(sizeof($category) >= 10)break;

        }

        if(sizeof($category) < 10){
            brand_search($term,$limit,$start);
        }
//     var_dump(sizeof($category));
        //echo json_encode($combined_result);
    }
    else return 0;// no level1_category found
}
/*category search*/
function brand_search($term,$limit,$start){
    global $combined_result;
    global $category;
    global $array_index;
//$main_result=json_decode(solr_curl("select",$term,$start,$limit),true);
    $fullLink=Solr_url."select?hl.fl=name,brand,level1_category,level2_category&hl.simple.pre=<strong>&hl.simple.post=</strong>&hl=on&indent=on&q=brand_c:".$term."*&rows=".$limit."&start=".$start."&stats=true&wt=json&fq=is_in_stock:1";
    $main_result=json_decode(solr_curl($fullLink),true);






    if($main_result['response']['numFound'] > 0)
    {
        //$array_index=0;


        foreach ($main_result['response']['docs'] as $doc){

            if (empty($category)){

                $category[]=$doc['level1_category'];
                $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                $highlight_field_type=key($main_result['highlighting'][$doc['id']]);

                //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);

                /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                $combined_result[$array_index]['id']=$doc['id'];
                $combined_result[$array_index]['name']=$doc['brand'];
                $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                $combined_result[$array_index]['type']=$highlight_field_type;
                $array_index++;
            }
            else if (!in_array($doc['level1_category'], $category)) {
                $category[]=$doc['level1_category'];
                $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                $highlight_field_type=key($main_result['highlighting'][$doc['id']]);
                //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);

                /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                $combined_result[$array_index]['id']=$doc['id'];
                $combined_result[$array_index]['name']=$doc['brand'];
                $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                $combined_result[$array_index]['type']=$highlight_field_type;
                $array_index++;
            }

            if(sizeof($category) >= 10)break;
        }

        if(sizeof($category) < 10){
            name_search($term,$limit,$start);
        }
        //echo json_encode($combined_result);
    }
    else return 0;// no brand found
}
/*category search*/
function name_search($term,$limit,$start){
    global $combined_result;
    global $category;
    global $array_index;
//$main_result=json_decode(solr_curl("select",$term,$start,$limit),true);
    $fullLink=Solr_url."select?hl.fl=name,brand,level1_category,level2_category&hl.simple.pre=<strong>&hl.simple.post=</strong>&hl=on&indent=on&q=".$term."*&rows=".$limit."&start=".$start."&stats=true&wt=json&fq=is_in_stock:1";
    $main_result=json_decode(solr_curl($fullLink),true);

    if($main_result['response']['numFound'] > 0)
    {
        //$array_index=0;
        foreach ($main_result['response']['docs'] as $doc){

            if (empty($category)){
                $category[]=$doc['level1_category'];
                $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                $highlight_field_type=key($main_result['highlighting'][$doc['id']]);

                //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);

                /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                $combined_result[$array_index]['id']=$doc['id'];

                $combined_result[$array_index]['name']=$doc['name'];

                $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                $combined_result[$array_index]['type']=$highlight_field_type;
                $array_index++;
            }
            else if (!in_array($doc['level1_category'], $category)) {
                $category[]=$doc['level1_category'];
                $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                $highlight_field_type=key($main_result['highlighting'][$doc['id']]);
                //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);



                /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                $combined_result[$array_index]['id']=$doc['id'];
                $combined_result[$array_index]['name']=$doc['name'];
                $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                $combined_result[$array_index]['type']=$highlight_field_type;
                $array_index++;
            }
            if(sizeof($category) < 10){
                $category[]=$doc['level1_category'];
                $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                $highlight_field_type=key($main_result['highlighting'][$doc['id']]);
                //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);



                /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                $combined_result[$array_index]['id']=$doc['id'];
                $combined_result[$array_index]['name']=$doc['name'];
                $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                $combined_result[$array_index]['type']=$highlight_field_type;
                $array_index++;

            }
            if(sizeof($category) >= 10)break;


        }
//     var_dump(sizeof($category));
        //echo json_encode($combined_result);
    }
    else {
        //////////////////////// suggestions

        $fullLink=Solr_url."spell?hl.fl=name,brand,level1_category,level2_category&hl.simple.pre=<strong>&hl.simple.post=</strong>&hl=on&indent=on&q=".$term."&rows=".$limit."&start=".$start."&stats=true&wt=json&fq=is_in_stock:1";
        $main_result=json_decode(solr_curl($fullLink),true);

        //$main_result=json_decode(solr_curl("spell",$term,$start,$limit),true);
        $suggestions=current($main_result['spellcheck']['suggestions'])['suggestion'];
        //echo'<pre>',print_r($suggestions),'</pre>';/////// all suggestions


        if(! empty($suggestions)){
            for($i = 0;$i <= 2;$i++ ){ // check for first 3 suggestions

                //var_dump($suggestions[$i]['word']);
                /////////////////////////////////////////////////////////// check for suggestion results
                $name_query=urlencode( $suggestions[$i]['word'] );
                $fullLink=Solr_url."select?hl.fl=name,brand,level1_category,level2_category&hl.simple.pre=<strong>&hl.simple.post=</strong>&hl=on&indent=on&q=name:".$name_query."*&rows=".$limit."&start=".$start."&stats=true&wt=json&fq=is_in_stock:1";
                $main_result=json_decode(solr_curl($fullLink),true);




                if($main_result['response']['numFound'] > 0)
                {
                    $array_index=0;

                    foreach ($main_result['response']['docs'] as $doc){

                        if (empty($category)){
                            $category[]=$doc['level1_category'];
                            $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                            $highlight_field_type=key($main_result['highlighting'][$doc['id']]);
                            //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);


                            /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                            $combined_result[$array_index]['id']=$doc['id'];
                            $combined_result[$array_index]['name']=$doc['name'];
                            $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                            $combined_result[$array_index]['category_id']=$doc['level2_category_id'];

                            $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                            $combined_result[$array_index]['type']=$highlight_field_type;

                            $array_index++;
                        }
                        else if (!in_array($doc['level1_category'], $category)) {
                            $category[]=$doc['level1_category'];
                            $highlight_field_value=current($main_result['highlighting'][$doc['id']])[0];
                            $highlight_field_type=key($main_result['highlighting'][$doc['id']]);
                            //preg_match('~<strong>([^{]*)<\/strong>~i', $highlight_field_value, $highlight);



                            /*$combined_result[$array_index]['label']=$highlight_field_value." in <font style='color: blue'>".$doc['level1_category']."</font>";*/
                            $combined_result[$array_index]['id']=$doc['id'];
                            $combined_result[$array_index]['name']=$doc['name'];
                            $combined_result[$array_index]['category']=$doc['level2_category']." ".$doc['level1_category']; ;
                            $combined_result[$array_index]['category_id']=$doc['level2_category_id'];
                            $combined_result[$array_index]['highlight']=strip_tags("$highlight_field_value");
                            $combined_result[$array_index]['type']=$highlight_field_type;
                            $array_index++;
                        }

                    }

                }

                ///////////////////////////////////////  end of suggestion result
            }

            //echo json_encode($combined_result);
        }
        else  echo 0; //  nothing found


    }
}

//echo'<pre>',print_r($combined_result),'</pre>';
echo json_encode($combined_result);


function solr_curl($fullLink){
    //echo "<br>".$fullLink."</br>"; die();
    $ch = curl_init($fullLink);

    $options = array
    (
        CURLOPT_RETURNTRANSFER => true,         // return web page
        CURLOPT_HEADER         => false,        // don't return headers
        CURLOPT_FOLLOWLOCATION => false,         // follow redirects
        CURLOPT_AUTOREFERER    => true,         // set referer on redirect
        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
        CURLOPT_SSL_VERIFYPEER => false,        //
        CURLOPT_VERBOSE        => 1,
    );

    curl_setopt_array($ch,$options);
    $result = curl_exec($ch);

    return $result;
}

