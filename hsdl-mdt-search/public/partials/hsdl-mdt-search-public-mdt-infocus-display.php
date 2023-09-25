<?php
include('hsdl-mdt-search-public-common-variables.php');
$facet_title = 'In Focus';
$facets = $attributes['facets'];
$decoded_facets = (array)json_decode($facets, null);
$facets_array['lists'] = $decoded_facets;
$col_focus = 'In Focus';
include('hsdl-mdt-search-public-search-facet-category-common-display.php');


