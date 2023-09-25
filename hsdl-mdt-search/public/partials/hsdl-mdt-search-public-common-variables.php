<?php
$col_focus = get_query_var('col-focus') ?: '';

$facet_mapping = array('subjects' => 'Subjects', 'creator' => 'Author', 'series' => 'Series',
    'lists' => 'In Focus', 'format' => 'Format', 'language' => 'Language',
    'publisher' => 'Publisher', 'tabsection' => 'Resource Type', 'collections' => 'Collections');

if ($col_focus) {
    $filter_title = $col_focus;
    $facet_title = $col_focus;
} else {
    $filter_title = '';
}

//Hide In Focus On Collection filter results
if ($filter_title == 'Collections') {
    $facet_mapping['tabsection'] = 'Collections';
    unset($facets_array->lists);
}
//Hide Collection on In Focus filter results

if ($filter_title == 'In Focus') {
    unset($facets_array->tabsection);
}

$params = array(
    'all'=>(isset($search_term))?$search_term:'',
    'any'=>(isset($any))?$any:'',
    'exact'=>(isset($exact))?$exact:'',
    'without'=>(isset($without))?$without:'',
    'from'=>(isset($from))?$from:'',
    'to'=>(isset($to))?$to:'',
    'creator'=>(isset($creator))?$creator:'',
    'publisher'=>(isset($publisher))?$publisher:'',
    'tabsection'=>(isset($tabsection))?$tabsection:'',
    'searchterm'=>(isset($search_term))?$search_term:'',
    'format'=>(isset($format))?$format:'',
    'language'=>(isset($language))?$language:'',
    'type'=>(isset($type))?$type:'',
    'action'=>'add',
    'advanced'=>'true',
    );
$alert_query_string = http_build_query($params);

