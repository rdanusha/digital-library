<?php
include('hsdl-mdt-search-public-common-variables.php');
$facet_title = 'Collections';
$facets = $attributes['facets'];
$decoded_facets = (array)json_decode($facets, null);
$facets_array['collections'] = $decoded_facets;
$col_focus = 'Collections';
include('hsdl-mdt-search-public-search-facet-category-common-display.php');


