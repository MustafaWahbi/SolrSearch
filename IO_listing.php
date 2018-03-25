<?php
ini_set("display_errors",1);
header('Access-Control-Allow-Origin: *');
include_once ("config.php");

/* initialize the phrase for search remove special  characters  */
//$_REQUEST['term']=removeCommonWords($_REQUEST['term']);

$filter=null;
$category_filter=null;
$just_category_filter=null;
$isInStock=null;
//////////////
/* add is_in_stock filter*/
/*if(isset($_REQUEST['isInStock'])&&!empty($_REQUEST['isInStock']))
{
    $isInStock="";
}
else  $isInStock="&fq=is_in_stock:1";*/
$isInStock="&fq=is_in_stock:1";
/////////////

/* add brand filter*/
if(isset($_REQUEST['brand'])&&!empty($_REQUEST['brand']))
    if($filter == null ){
        $multi_brand_to_array=explode(',',urldecode($_REQUEST['brand']));

        $new=[];
        foreach ($multi_brand_to_array as $value){
            $new[]='"'.$value.'"';
        }
        $multi_brand_to_array=$new;

        $multi_brand_to_string=implode(' OR ',$multi_brand_to_array);
        $encoded_brand=urlencode($multi_brand_to_string);
        $multi_brand_to_string = str_replace("+", "%20", $encoded_brand);

        $filter.="fq=brand:".$multi_brand_to_string;
        //var_dump($filter); die();
    }

    else {
        $multi_brand_to_array=explode(',',urldecode($_REQUEST['brand']));

        $new=[];
        foreach ($multi_brand_to_array as $value){
            $new[]='"'.$value.'"';
        }
        $multi_brand_to_array=$new;

        $multi_brand_to_string=implode(' OR ',$multi_brand_to_array);
        $encoded_brand=urlencode($multi_brand_to_string);
        $multi_brand_to_string = str_replace("+", "%20", $encoded_brand);
        $filter.="&fq=brand:".urlencode($multi_brand_to_string);
        //var_dump($filter); die();
    }

/* add color filter*/
if(isset($_REQUEST['color'])&&!empty($_REQUEST['color']))
    if($filter == null ){
        $multi_color_to_array=explode(',',urldecode($_REQUEST['color']));
        $multi_color_to_string=implode(' OR ',$multi_color_to_array);
        $filter.="fq=primary_color_value:".urlencode($multi_color_to_string);
    }
    else {
        $multi_color_to_array=explode(',',urldecode($_REQUEST['color']));
        $multi_color_to_string=implode(' OR ',$multi_color_to_array);
        $filter.="&fq=primary_color_value:".urlencode($multi_color_to_string);
    }

/* add size filter*/
if(isset($_REQUEST['size'])&&!empty($_REQUEST['size']))
    if($filter == null ){

        $multi_size_to_array=explode(',',urldecode($_REQUEST['size']));
        $multi_size_to_string=implode(' OR ',$multi_size_to_array);
        $filter.="fq=size_value:".urlencode($multi_size_to_string);
    }
    else {

        $multi_size_to_array=explode(',',urldecode($_REQUEST['size']));
        $multi_size_to_string=implode(' OR ',$multi_size_to_array);
        $filter.="&fq=size_value:".urlencode($multi_size_to_string);

    }
/* add product type filter*/
if(isset($_REQUEST['productType'])&&!empty($_REQUEST['productType']))
    if($filter == null ){
        $multi_type_to_array=explode(',',urldecode($_REQUEST['productType']));
        $multi_type_to_string=implode(' OR ',$multi_type_to_array);
        $filter.="fq=product_type_value:".urlencode($multi_type_to_string);
    }
    else {
        $multi_type_to_array=explode(',',urldecode($_REQUEST['productType']));
        $multi_type_to_string=implode(' OR ',$multi_type_to_array);
        $filter.="&fq=product_type_value:".urlencode($multi_type_to_string);
    }
/* add category filter*/
if(isset($_REQUEST['parent_category'])&&!empty($_REQUEST['parent_category']))
    if($filter == null ){
        $multi_category_to_array=explode(',',urldecode($_REQUEST['parent_category']));
        $multi_category_to_string=implode(' OR ',$multi_category_to_array);
        $filter.="fq=parent_category_id:".urlencode($multi_category_to_string);
        $category_filter="fq=parent_category_id:".urlencode($multi_category_to_string);
        $just_category_filter="fq=parent_category_id:".urlencode($multi_category_to_string);

    }
    else {
        $multi_category_to_array=explode(',',urldecode($_REQUEST['parent_category']));
        $multi_category_to_string=implode(' OR ',$multi_category_to_array);
        $filter.="&fq=parent_category_id:".urlencode($multi_category_to_string);
        $category_filter="&fq=parent_category_id:".urlencode($multi_category_to_string);
        $just_category_filter="&fq=parent_category_id:".urlencode($multi_category_to_string);
    }
if(isset($_REQUEST['level1_category'])&&!empty($_REQUEST['level1_category']))
    if($filter == null ){
        $multi_category_to_array=explode(',',urldecode($_REQUEST['level1_category']));
        $multi_category_to_string=implode(' OR ',$multi_category_to_array);
        $filter.="fq=level1_category_id:".urlencode($multi_category_to_string);
        $category_filter="fq=level1_category_id:".urlencode($multi_category_to_string);
        $just_category_filter="fq=level1_category_id:".urlencode($multi_category_to_string);
    }
    else {
        $multi_category_to_array=explode(',',urldecode($_REQUEST['level1_category']));
        $multi_category_to_string=implode(' OR ',$multi_category_to_array);
        $filter.="&fq=level1_category_id:".urlencode($multi_category_to_string);
        $category_filter="&fq=level1_category_id:".urlencode($multi_category_to_string);
        $just_category_filter="&fq=level1_category_id:".urlencode($multi_category_to_string);
    }

if(isset($_REQUEST['level2_category'])&&!empty($_REQUEST['level2_category']))
    if($filter == null ){
        $multi_category_to_array=explode(',',urldecode($_REQUEST['level2_category']));
        $multi_category_to_string=implode(' OR ',$multi_category_to_array);
        $filter.="fq=level2_category_id:".urlencode($multi_category_to_string);
        $category_filter="fq=level2_category_id:".urlencode($multi_category_to_string);
    }
    else {
        $multi_category_to_array=explode(',',urldecode($_REQUEST['level2_category']));
        $multi_category_to_string=implode(' OR ',$multi_category_to_array);
        $filter.="&fq=level2_category_id:".urlencode($multi_category_to_string);
        $category_filter="&fq=level2_category_id:".urlencode($multi_category_to_string);
    }

/* add price range filter*/
if(isset($_REQUEST['min_price'])&&isset($_REQUEST['max_price']))($filter == null ? $filter.="fq=final_price:[".urlencode($_REQUEST['min_price'])."%20TO%20".urlencode($_REQUEST['max_price'])."]":$filter.="&fq=final_price:[".urlencode($_REQUEST['min_price'])."%20TO%20".urlencode($_REQUEST['max_price'])."]");

//$filter=str_replace ( ' ', '%20', $filter );// remove (+)%20AND%20special characters by urldecode%20AND%20str_replace


if(!isset($_REQUEST['count'])||!isset($_REQUEST['page']))products_search($_REQUEST['term'],24,1,$_REQUEST['order'],$filter);
else if(!isset($_REQUEST['order']))products_search($_REQUEST['count'],$_REQUEST['page'],'',$filter,$category_filter,$just_category_filter);
else products_search($_REQUEST['count'],$_REQUEST['page'],$_REQUEST['order'],$filter,$category_filter,$just_category_filter);

/* put global filters to fill it by function then display it with another */
$full_final_result_brands=[];
$full_colors=[];
$full_sizes=[];
$full_types=[];
$categories=[];
$categories_count=[];
$include_in_menu=[];

///////////////////////////////////////////////////// full product search
function products_search($count=24,$page=1,$order,$filter,$category_filter,$just_category_filter){

    global $full_final_result_brands;
    global $full_colors;
    global $full_sizes;
    global $full_types;
    global $categories;
    global $categories_count;
    global $include_in_menu;
    global $isInStock;

    $start=0;$end=0;
    if($page == 1){$start=0;$end=$count; }
    else {$start=($page-1)*$count;$end=($page*$count); }
    $limit=$end-$start;


   // $name_query=str_replace ( ' ', '%20', $term );

    if($order != ""){
        /* fill the filters*/
        products_search_filters_filler($order,$filter);
        products_search_filters_category($order,$just_category_filter);
        /* pagination */
        $fullLink=Solr_url."select?indent=on&q=*&rows=".$limit."&start=".$start."&stats=true&stats.field=price&sort=final_price%20".$order."&wt=json&fq=status:1&fq=parent_id:0".$isInStock."&".$filter;
    }
    else {
        /* fill the filters*/
       products_search_filters_filler($order,$filter);
        products_search_filters_category($order,$just_category_filter);
        /* pagination */
        $fullLink=Solr_url."select?indent=on&q=*&rows=".$limit."&start=".$start."&stats=true&stats.field=price&wt=json&fq=status:1&fq=parent_id:0".$isInStock."&".$filter;
    }

//var_dump($fullLink); die();

    $main_result=json_decode(solr_curl($fullLink),true);

    $full_final_result=[];

    /*$full_final_result_brands=[];
    $full_colors=[];
    $full_sizes=[];
    $full_types=[];
    $categories=[];*/
    $variations=[];
    $full_final_result['label']="all";
    if($main_result['response']['numFound'] > 0)
    {

    foreach ($categories as $category_meta_key => $category_meta){
        $categoryMeta[]=[
            "entity_id" => $category_meta_key,
            "meta_title" => $category_meta,
            "attribute_id" => 49,
            "meta_keyword" =>"",
            "meta_description" =>""
        ];

        $full_final_result['products']=
            [
                "success"   => 1,
                "categoryMeta" => $categoryMeta

            ];
    }

        $full_final_result['minPrice']=$main_result['stats']['stats_fields']['price']['min'];
        $full_final_result['maxPrice']=$main_result['stats']['stats_fields']['price']['max'];
        $full_final_result['total_products']=$main_result['response']['numFound'];
        $full_final_result['total_products']=$main_result['response']['numFound'];
        $full_final_result['exchange_rate']=
            [
                "AED" => 3.67
            ];


        foreach ($main_result['response']['docs'] as $array_index => $doc)
        {

            if($doc['type_id'] ==  "simple"){

                //$full_final_result['result'][$array_index]['parent_id']           =$doc['parent_id'];
                $full_final_result['result'][$array_index]['product_id']          =$doc['id'];
                $full_final_result['result'][$array_index]['sku']                 =$doc['sku'];
                $full_final_result['result'][$array_index]['category']            =$doc['level2_category_id'];
                $full_final_result['result'][$array_index]['product_name']        =$doc['name'];
                $full_final_result['result'][$array_index]['status']              =$doc['status'];
                $full_final_result['result'][$array_index]['description']         =$doc['description'];
                $full_final_result['result'][$array_index]['product_image']       =$doc['image'];
                $full_final_result['result'][$array_index]['product_otherimages'] =$doc['product_otherimages'];
                $full_final_result['result'][$array_index]['price']               =$doc['price'];
                $full_final_result['result'][$array_index]['qty']                 =$doc['qty'];
                $full_final_result['result'][$array_index]['isInStock']           =$doc['is_in_stock'];
                $full_final_result['result'][$array_index]['brand']               =$doc['brand'];
                $full_final_result['result'][$array_index]['special_price']       =$doc['special_price'];
                $full_final_result['result'][$array_index]['meta_title']          ="";
                $full_final_result['result'][$array_index]['meta_description']    ="";
                $full_final_result['result'][$array_index]['meta_keyword']        ="";

                //$full_final_result_brands[]=$doc['brand'];


            }
            else{

                //$full_final_result['result'][$array_index]['parent_id']           =$doc['parent_id'];
                $full_final_result['result'][$array_index]['product_id']          =$doc['id'];
                $full_final_result['result'][$array_index]['sku']                 =$doc['sku'];
                $full_final_result['result'][$array_index]['category']            =$doc['level2_category_id'];
                $full_final_result['result'][$array_index]['product_name']        =$doc['name'];
                $full_final_result['result'][$array_index]['status']              =$doc['status'];
                $full_final_result['result'][$array_index]['description']         =$doc['description'];

                if(isset($doc['image']))
                    $full_final_result['result'][$array_index]['product_image']       =$doc['image'];
                else $full_final_result['result'][$array_index]['product_image']      ="";

                $full_final_result['result'][$array_index]['product_otherimages'] =$doc['product_otherimages'];
                $full_final_result['result'][$array_index]['price']               =$doc['price'];
                $full_final_result['result'][$array_index]['qty']                 =$doc['qty'];
                $full_final_result['result'][$array_index]['isInStock']           =$doc['is_in_stock'];
                $full_final_result['result'][$array_index]['brand']               =$doc['brand'];
                $full_final_result['result'][$array_index]['special_price']       =$doc['special_price'];

                //$full_final_result_brands[]=$doc['brand'];
                //$full_final_result_types[]=$doc['product_type'];

                $childLink=Solr_url."select?indent=on&q=parent_id:".$doc['id']."&rows=50&wt=json".$isInStock;
                $children=json_decode(solr_curl($childLink),true);

                $variant_group=[];
                $colors=[];
                $sizes=[];
                $full_final_result_children=[];
                $variation_details=[];

                foreach ($children['response']['docs'] as $index => $child)
                {

                    $full_final_result_children[$index]['parent_id']           =$child['parent_id'];
                    $full_final_result_children[$index]['product_id']          =$child['id'];
                    $full_final_result_children[$index]['sku']                 =$child['sku'];
                    $full_final_result_children[$index]['product_name']        =$child['name'];
                    $full_final_result_children[$index]['status']              =$child['status'];
                    $full_final_result_children[$index]['description']         =$child['description'];
                    $full_final_result_children[$index]['product_image']       =$child['image'];
                    $full_final_result_children[$index]['product_otherimages'] =$child['product_otherimages'];
                    $full_final_result_children[$index]['price']               =$child['price'];
                    $full_final_result_children[$index]['qty']                 =$child['qty'];
                    $full_final_result_children[$index]['isInStock']           =$child['is_in_stock'];
                    $full_final_result_children[$index]['brand']               =$child['brand'];
                    $full_final_result_children[$index]['special_price']       =$child['special_price'];

                    $full_final_result_children[$index]['meta_title']           ="";
                    $full_final_result_children[$index]['meta_description']     ="";
                    $full_final_result_children[$index]['meta_keyword']         ="";


                    $full_final_result_children[$index]['size']['id']          =$child['size_id'];
                    $full_final_result_children[$index]['size']['value']       =$child['size_value'];
                    $full_final_result_children[$index]['size']['label']       =$child['size_label'];

                    $full_final_result_children[$index]['color']['id']          =$child['color_id'];
                    $full_final_result_children[$index]['color']['value']       =$child['color_value'];
                    $full_final_result_children[$index]['color']['label']       =$child['color_label'];



                    $full_final_result_children[$index]['primary_color']['id']          =$child['primary_color_id'];
                    $full_final_result_children[$index]['primary_color']['value']       =$child['primary_color_value'];
                    $full_final_result_children[$index]['primary_color']['label']       =$child['primary_color_label'];


                    if ((!in_array($child['color'], $colors)) && ($child['color'] != "0"))
                    {
                        $colors[]=$child['color'];
                    }
                    if ((!in_array($child['size'], $sizes)) && ($child['size'] != "0"))
                    {
                        $sizes[]=$child['size'];
                    }

                    $full_final_result_brands[]=$child['brand'];

                }

                if(sizeOf($sizes) > 1){$variant_group[]="variant_size";}
                if(sizeOf($colors) > 1){$variant_group[]="variant_color";}

                $variation_details['prices']=
                    [
                        "price" => $doc['price'],"special_price"=>$doc['special_price']
                    ];

                $variation_details['variant_group']=implode(",", $variant_group);


                /*child products */
                foreach ($full_final_result_children as $child_copy_index => $copy_child)
                {
                    unset($copy_child['size']);
                    unset($copy_child['color']);
                    unset($copy_child['primary_color']);
                    $full_final_result['result'][$array_index]['child_products'][]=$copy_child;
                }



                /* variation_details*/

                $full_final_result['result'][$array_index]['variation_details']=$variation_details;

                $products_color=[];
                //
                foreach ($full_final_result_children as $key => $last_child)
                {

                    $id_as_key= $last_child['parent_id'];
                    if(isset($products_color[$id_as_key]) && $products_color[$id_as_key] == $last_child['color'])continue;//  if the parent id existed%20OR%20the color existed
                    else{
                        if(!isset($products_color[$id_as_key])){ $color_index=0;//  if the parent id not existed it means the color not existed
                            $products_color[$id_as_key]=$last_child['color']['value'];

                            //////////////////////////
                            $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['color']=$last_child['color'];

                            if(count($sizes)){ // if there is more than one size
                                foreach ($sizes as $size_index => $size)
                                {
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]=$full_final_result_children[$size_index];
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]['color']=$last_child['color'];
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]['product_image']=$last_child['product_image'];
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]['product_otherimages']=$last_child['product_otherimages'];
                                }

                            }
                            else{ // if there is no sizes
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]=$full_final_result_children[0];
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]['color']=$last_child['color'];
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]['product_image']=$last_child['product_image'];
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]['product_otherimages']=$last_child['product_otherimages'];
                            }

                        }
                        else if(isset($products_color[$id_as_key]) && $products_color[$id_as_key] != $last_child['color']['value'])//  if the parent id existed%20OR%20the color not existed
                        {
                            $color_index++;
                            $products_color[$id_as_key]=$last_child['color']['value'];
                            //////////////////////////
                            $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['color']=$last_child['color'];

                            if(count($sizes)){ // if there is more than one size
                                foreach ($sizes as $size_index => $size)
                                {
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]=$full_final_result_children[$size_index];
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]['color']=$last_child['color'];
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]['product_image']=$last_child['product_image'];
                                    $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][$size_index]['product_otherimages']=$last_child['product_otherimages'];
                                }

                            }
                            else{ // if there is no sizes
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]=$full_final_result_children[0];
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]['color']=$last_child['color'];
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]['product_image']=$last_child['product_image'];
                                $full_final_result['result'][$array_index]['variation_details']['variations'][$color_index]['variations'][0]['product_otherimages']=$last_child['product_otherimages'];
                            }

                        }
                    }
                }

                ///////////// sizes & colors
                $full_final_result['result'][$array_index]['variation_details']['sizes']=$sizes;
                $full_final_result['result'][$array_index]['variation_details']['colors']=$colors;
                ///////////// sizes & colors


                ///////////////////////////////////////////////  sort the variations by colors


                //$full_final_result[$array_index]['variation_details']       =$full_final_result_children;

            }

        }

        /////////remove duplicate $full_final_result = array_map("unserialize", array_unique(array_map("serialize", $full_final_result)));

        $full_final_result_colors_remove_duplicate=[];
        $full_final_result_brands_remove_duplicate=[];
        $full_final_result_sizes_remove_duplicate=[];
        $full_final_result_types_remove_duplicate=[];
        $full_final_result_categories_remove_duplicate=[];

        $full_color_counter=0;
        if(count($full_colors) != 0)
            foreach ($full_colors as $full_color_key =>$full_color ){
                $full_final_result_colors_remove_duplicate[$full_color_counter]['option_id']=$full_color_key;
                $full_final_result_colors_remove_duplicate[$full_color_counter]['value']=$full_color;
                $full_color_counter++;
            }
        $full_size_counter=0;
        if(count($full_sizes) != 0)
            foreach ($full_sizes as $full_size_key =>$full_size ){
                $full_final_result_sizes_remove_duplicate[$full_size_counter]['option_id']=$full_size_key;
                $full_final_result_sizes_remove_duplicate[$full_size_counter]['value']=$full_size;
                $full_size_counter++;
            }
        $full_type_counter=0;
        if(count($full_types) != 0)
            foreach ($full_types as $full_type_key =>$full_type ){
                $full_final_result_types_remove_duplicate[$full_type_counter]['option_id']=$full_type_key;
                $full_final_result_types_remove_duplicate[$full_type_counter]['value']=$full_type;
                $full_type_counter++;
            }

        $category_counter=0;
        if(count($categories) != 0)
            foreach ($categories as $category_key =>$category ){
                $full_final_result_categories_remove_duplicate[$category_counter]['id']=$category_key;
                $full_final_result_categories_remove_duplicate[$category_counter]['label']=$category;
                $full_final_result_categories_remove_duplicate[$category_counter]['count']=$categories_count[$category_key];
                $full_final_result_categories_remove_duplicate[$category_counter]['include_in_menu']=$include_in_menu[$category_key];

                $category_counter++;
            }

        $full_final_result_brands_remove_duplicate = array_map("unserialize", array_unique(array_map("serialize", $full_final_result_brands)));

        $full_final_result['products']['filters']=
            [
                "colors"        =>  $full_final_result_colors_remove_duplicate,
                "brands"        =>  $full_final_result_brands_remove_duplicate,
                "sizes"         =>  $full_final_result_sizes_remove_duplicate,
                "types"         =>  $full_final_result_types_remove_duplicate,
                //"categories"    =>  $full_final_result_categories_remove_duplicate,
            ];
        $full_final_result['categories']  =  $full_final_result_categories_remove_duplicate;

        // echo'<pre>',print_r($full_final_result),'</pre>'; die();
        echo json_encode($full_final_result);

    }

}

function products_search_filters_filler($order,$filter){

    global $full_final_result_brands;
    global $full_colors;
    global $full_sizes;
    global $full_types;
    /*global $categories;
    global $categories_count;*/
    global $isInStock;




   // $fullLink=Solr_url."select?indent=on&q=*&rows=".$limit."&start=".$start."&stats=true&stats.field=price&wt=json&fq=parent_id:0".$isInStock."&".$filter;

    if($order != "")$fullLink=Solr_url."select?indent=on&q=*&rows=2000&start=0&stats=true&stats.field=price&sort=final_price%20".$order."&wt=json&fq=parent_id:0".$isInStock."&".$filter;
    else $fullLink=Solr_url."select?indent=on&q=*&rows=2000&start=0&stats=true&stats.field=price&wt=json&fq=parent_id:0".$isInStock."&".$filter;


    $main_result=json_decode(solr_curl($fullLink),true);


    if($main_result['response']['numFound'] > 0)
    {


        foreach ($main_result['response']['docs'] as $array_index => $doc)
        {

            if($doc['type_id'] ==  "simple"){

                if(count($full_colors) == 0 || !in_array($doc['primary_color_label'],$full_colors))$full_colors[$doc['primary_color_value']]=$doc['primary_color_label'];
                /*sizes*/
                if(count($full_sizes) == 0 || !in_array($doc['size_label'],$full_sizes))$full_sizes[$doc['size_value']]=$doc['size_label'];
                /*types*/
                if((count($full_types) == 0 || !in_array($doc['product_type_label'],$full_types))&&($doc['product_type_value'] != 0))$full_types[$doc['product_type_value']]=$doc['product_type_label'];
                /*$categories*/
               /* if((count($categories) == 0 || !in_array($doc['parent_category']." ".$doc['level2_category'],$categories))){
                    $categories[$doc['level2_category_id']]=$doc['parent_category']." ".$doc['level2_category']; // categories
                    $categories_count[$doc['level2_category_id']]=1;// counter of categories

                }
                else if(array_key_exists($doc['level2_category_id'],$categories)){$categories_count[$doc['level2_category_id']]=$categories_count[$doc['level2_category_id']]+1; }
                */
                $full_final_result_brands[]=$doc['brand'];


            }
            else{

                $childLink=Solr_url."select?indent=on&q=parent_id:".$doc['id']."&rows=50&wt=json".$isInStock;
                $children=json_decode(solr_curl($childLink),true);

                /*if((count($categories) == 0 || !array_key_exists($doc['level2_category_id'],$categories))){
                    $categories[$doc['level2_category_id']]=$doc['parent_category']." ".$doc['level2_category']; // categories
                    $categories_count[$doc['level2_category_id']]=1;// counter of categories
                }
                else if(array_key_exists($doc['level2_category_id'],$categories)){$categories_count[$doc['level2_category_id']]=$categories_count[$doc['level2_category_id']]+1; }
                */
                $full_final_result_brands[]=$doc['brand'];

                foreach ($children['response']['docs'] as $index => $child)
                {

                    /*for filters*/
                    /*colors*/
                    if(count($full_colors) == 0 || !in_array($child['primary_color_label'],$full_colors))$full_colors[$child['primary_color_value']]=$child['primary_color_label'];
                    /*sizes*/
                    if(count($full_sizes) == 0 || !in_array($child['size_label'],$full_sizes))$full_sizes[$child['size_value']]=$child['size_label'];
                    /*types*/
                    if((count($full_types) == 0 || !in_array($child['product_type_label'],$full_types))&&($child['product_type_value'] != 0))$full_types[$child['product_type_value']]=$child['product_type_label'];
                    /*$categories*/
                    /*if((count($categories) == 0|| !array_key_exists($doc['level2_category_id'],$categories))){
                        $categories[$child['level2_category_id']]=$child['parent_category']." ".$child['level2_category']; // categories
                        $categories_count[$child['level2_category_id']]=1;// counter of categories
                    }
                    else if(array_key_exists($child['level2_category_id'],$categories))
                    {

                        $categories_count[$child['level2_category_id']] = $categories_count[$child['level2_category_id']]+1;
                    }*/

                    $full_final_result_brands[]=$child['brand'];

                }


            }

        }

        /////////remove duplicate $full_final_result = array_map("unserialize", array_unique(array_map("serialize", $full_final_result)));
        $full_final_result_colors_remove_duplicate=[];
        $full_final_result_brands_remove_duplicate=[];
        $full_final_result_sizes_remove_duplicate=[];
        $full_final_result_types_remove_duplicate=[];
        $full_final_result_categories_remove_duplicate=[];

        $full_color_counter=0;
        if(count($full_colors) != 0)
            foreach ($full_colors as $full_color_key =>$full_color ){
                $full_final_result_colors_remove_duplicate[$full_color_counter]['option_id']=$full_color_key;
                $full_final_result_colors_remove_duplicate[$full_color_counter]['value']=$full_color;
                $full_color_counter++;
            }
        $full_size_counter=0;
        if(count($full_sizes) != 0)
            foreach ($full_sizes as $full_size_key =>$full_size ){
                $full_final_result_sizes_remove_duplicate[$full_size_counter]['option_id']=$full_size_key;
                $full_final_result_sizes_remove_duplicate[$full_size_counter]['value']=$full_size;
                $full_size_counter++;
            }
        $full_type_counter=0;
        if(count($full_types) != 0)
            foreach ($full_types as $full_type_key =>$full_type ){
                $full_final_result_types_remove_duplicate[$full_type_counter]['option_id']=$full_type_key;
                $full_final_result_types_remove_duplicate[$full_type_counter]['value']=$full_type;
                $full_type_counter++;
            }

      /*  $category_counter=0;
        if(count($categories) != 0)
            foreach ($categories as $category_key =>$category ){
                $full_final_result_categories_remove_duplicate[$category_counter]['id']=$category_key;
                $full_final_result_categories_remove_duplicate[$category_counter]['label']=$category;
                $full_final_result_categories_remove_duplicate[$category_counter]['count']=$categories_count[$category_key];
                $category_counter++;
            }*/

        $full_final_result_brands_remove_duplicate = array_map("unserialize", array_unique(array_map("serialize", $full_final_result_brands)));

        $full_final_result['products']['filters']=
            [
                "colors"        =>  $full_final_result_colors_remove_duplicate,
                "brands"        =>  $full_final_result_brands_remove_duplicate,
                "sizes"         =>  $full_final_result_sizes_remove_duplicate,
                "types"         =>  $full_final_result_types_remove_duplicate,
                //"categories"    =>  $full_final_result_categories_remove_duplicate,
            ];
        $full_final_result['categories']  =  $full_final_result_categories_remove_duplicate;


    }

}

function products_search_filters_category($order,$just_category_filter){


    global $categories;
    global $categories_count;
    global $include_in_menu;
    global $isInStock;




   // $fullLink=Solr_url."select?indent=on&q=*&rows=".$limit."&start=".$start."&stats=true&stats.field=price&wt=json&fq=parent_id:0".$isInStock."&".$filter;

    if($order != "")$fullLink=Solr_url."select?indent=on&q=*&rows=2000&start=0&stats=true&stats.field=price&sort=final_price%20".$order."&wt=json&fq=status:1&fq=parent_id:0".$isInStock."&".$just_category_filter;
    else $fullLink=Solr_url."select?indent=on&q=*&rows=2000&start=0&stats=true&stats.field=price&wt=json&fq=status:1&fq=parent_id:0".$isInStock."&".$just_category_filter;


    $main_result=json_decode(solr_curl($fullLink),true);


    if($main_result['response']['numFound'] > 0)
    {


        foreach ($main_result['response']['docs'] as $array_index => $doc)
        {

            if($doc['type_id'] ==  "simple"){

                /*$categories*/
                if((count($categories) == 0 || !in_array($doc['parent_category']." ".$doc['level2_category'],$categories))){
                    $categories[$doc['level2_category_id']]=$doc['parent_category']." ".$doc['level2_category']; // categories
                    $include_in_menu[$doc['level2_category_id']]=$doc['include_in_menu']; // include_in_menu
                    $categories_count[$doc['level2_category_id']]=1;// counter of categories

                }
                else if(array_key_exists($doc['level2_category_id'],$categories)){
                    $categories_count[$doc['level2_category_id']]=$categories_count[$doc['level2_category_id']]+1;
                }



            }
            else{

                $childLink=Solr_url."select?indent=on&q=parent_id:".$doc['id']."&rows=50&wt=json".$isInStock;
                $children=json_decode(solr_curl($childLink),true);

                if((count($categories) == 0 || !array_key_exists($doc['level2_category_id'],$categories))){
                    $categories[$doc['level2_category_id']]=$doc['parent_category']." ".$doc['level2_category']; // categories
                    $include_in_menu[$doc['level2_category_id']]=$doc['include_in_menu']; // include_in_menu
                    $categories_count[$doc['level2_category_id']]=1;// counter of categories
                }
                else if(array_key_exists($doc['level2_category_id'],$categories)){
                    $categories_count[$doc['level2_category_id']]=$categories_count[$doc['level2_category_id']]+1;
                }




            }

        }

        /////////remove duplicate $full_final_result = array_map("unserialize", array_unique(array_map("serialize", $full_final_result)));



        $category_counter=0;
        foreach ($categories as $category_key =>$category ){
            $full_final_result_categories_remove_duplicate[$category_counter]['id']=$category_key;
            $full_final_result_categories_remove_duplicate[$category_counter]['label']=$category;
            $full_final_result_categories_remove_duplicate[$category_counter]['count']=$categories_count[$category_key];
            $full_final_result_categories_remove_duplicate[$category_counter]['include_in_menu']=$include_in_menu[$category_key];
            //$include_in_menu[$doc['level2_category_id']]=$doc['include_in_menu']; // include_in_menu
            $category_counter++;
        }



        $full_final_result['categories']  =  $full_final_result_categories_remove_duplicate;


    }

}

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

function removeCommonWords($input){
    $commonWords = array('and','for','&');
    return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
}