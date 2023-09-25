<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/public/partials
 */
?>
<?php
$type = get_query_var('type') ?: 'all';
$start = get_query_var('start') ?: '0';
$rows = get_query_var('rows') ?: '30';
$sort = get_query_var('sort') ?: 'date';
$col_focus = get_query_var('col-focus') ?: '';
$search_term = get_query_var('searchterm') ?: '';

$advanced = get_query_var('advanced') ?: 'false';
$all = get_query_var('all') ?: '';
$any = get_query_var('any') ?: '';
$exact = get_query_var('exact') ?: '';
$without = get_query_var('without') ?: '';
$from = get_query_var('from') ?: '';
$to = get_query_var('to') ?: '';
$tabsection = get_query_var('tabsection') ?: '';
$search_in = get_query_var('search-in') ?: '';

//staff
$release_flag = get_query_var('releaseFlag') ?: '';
$doc_id = get_query_var('docID') ?: '';
$url = get_query_var('url') ?: '';
$source = get_query_var('source') ?: '';
$notes = get_query_var('notes') ?: '';
$country = get_query_var('country') ?: '';
$state = get_query_var('state') ?: '';
$modified_by = get_query_var('modifiedBy') ?: '';
$modified_from = get_query_var('modifiedFrom') ?: '';
$modified_to = get_query_var('modifiedTo') ?: '';
$created_by = get_query_var('createdBy') ?: '';
$created_from = get_query_var('createdFrom') ?: '';
$created_to = get_query_var('createdTo') ?: '';
$fast_subject = get_query_var('fastSubject') ?: '';

$page_name = get_query_var('page-name') ?: '';
$restricted = get_query_var('restricted') ?: '';

$is_restricted_collections = 'false';
if(($page_name=="restricted" && $restricted=='true') && is_hsdl_restricted_participant() || is_ip_user()){
    $is_restricted_collections = 'true';
}

if ($advanced == 'true' && !empty($all)) {
    $search_term = $all;
}

$search_contains = array('all' => 'Full Text Contains',
    'author' => 'Author contains',
    'title' => 'Title contains',
    'summary' => 'Title or summary',
    'publisher' => 'Publisher contains',
    'series' => 'Series contains',
    'list' => 'Lists contains'
);

$staff_search_contains = array('0' => 'Any Release label',
    '1' => 'Show to End User',
    '2' => 'Needs Review',
    '3' => 'Broken Link',
    '4' => 'Do Not Collect',
    '5' => 'Duplicate',
);

$facet_values = array('subjects', 'creator', 'series', 'lists', 'format', 'language', 'publisher', 'tabsection');

?>
<div class="top-row-cont">
    <div class="container">
        <div class="top-row">
            <form action="<?php echo home_url('/search-result'); ?>" autocomplete="off" method="get"
                  id="mdt-search-form">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <select name="type" id="type">
                            <?php foreach ($search_contains as $key => $value) : ?>
                                <option value="<?php echo $key; ?>" <?php echo ($type == $key) ? 'selected' : ''; ?>>
                                    <?php echo $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8 col-sm-12 search-bar">
                        <input type="text" name="searchterm" id="searchterm"
                               value="<?php echo ($search_term) ? $search_term : ''; ?>"
                               placeholder="Search for titles, authors, journals, digital collections etc…">
                        <button id="simple-search" class="simple-search">
                            <div class="search-btn"></div>
                        </button>
                        <button type="reset"
                                data-url="<?php echo home_url('/search-result/?type=all&searchterm=&advanced=false&search=1&start=0&rows=30'); ?>"
                                class="reset-btn" id="reset-btn">Reset
                        </button>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <button class="adv-search-btn" id="adv-search-btn">Advanced Search</button>
                    </div>
                </div>
                <input type="hidden" name="advanced" id="advanced" value="false">
                <?php include('hsdl-mdt-search-public-common-form-fields.php'); ?>
            </form>
            <input type="hidden" name="ajx_start" id="ajx_start" value="0">
            <input type="hidden" name="is_advanced" id="is_advanced" value="<?php echo $advanced; ?>">
        </div>
    </div>
</div>
<div class="bottom-row-cont" id="bottom-row-cont">
    <div class="container">
        <div class="bottom-row">
            <form action="<?php echo home_url('/search-result'); ?>" autocomplete="off" method="get"
                  id="mdt-advanced-search-form">
                <?php include('hsdl-mdt-search-public-common-form-fields.php'); ?>

                <div class="row">
                    <div class="col-md-12">
                        <h4>Search Filters</h4><a class="btn-help btn-with-text"
                                                  href="<?php echo home_url('/help-searching'); ?>">Help</a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="">Contains</label>
                            <select name="type" id="type">
                                <?php foreach ($search_contains as $key => $value) : ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($type == $key) ? 'selected' : ''; ?>>
                                        <?php echo $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="">All of the Words</label>
                            <input type="text" id="all" name="all" value="<?php echo ($all) ? $all : ''; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="">Any of the Words</label>
                            <input type="text" id="any" name="any" value="<?php echo ($any) ? $any : ''; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="">Exact Phrase</label>
                            <input type="text" id="exact" name="exact" value="<?php echo ($exact) ? $exact : ''; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="">Without the words</label>
                            <input type="text" id="without" name="without"
                                   value="<?php echo ($without) ? $without : ''; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="">Publish Date (YYYY-MM-DD)</label>
                            <div class="row" id="date-range-picker-container">
                                <div class="col-md-6 date-input-wrapper">
                                    <input type="text" value="<?php echo ($from) ? $from : ''; ?>" name="from" id="from"
                                           data-date-orientation="bottom right"
                                           data-date-z-index-offset=999
                                           data-date-autoclose="true"
                                           data-date-clear-btn="true"
                                           data-provide="datepicker"
                                           data-date-container="#date-range-picker-container"
                                           data-date-format="yyyy-mm-dd" onkeydown="return false"
                                           class="datepicker date-input" autocomplete="off" placeholder="From">
                                </div>
                                <div class="col-md-6 date-input-wrapper">
                                    <input type="text" value="<?php echo ($to) ? $to : ''; ?>" name="to" id="to"
                                           data-date-orientation="bottom right"
                                           data-date-autoclose="true"
                                           data-date-clear-btn="true"
                                           data-provide="datepicker"
                                           data-date-container="#date-range-picker-container"
                                           data-date-format="yyyy-mm-dd" onkeydown="return false"
                                           class="datepicker date-input" autocomplete="off" placeholder="To">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="">Search In</label>
                            <select class="search-in" name="search-in" id="search-in">
                                <?php if (is_array($search_in_values) && !empty($search_in_values)) : ?>
                                    <?php foreach ($search_in_values as $search_in_value) : ?>
                                        <option <?php echo ($search_in == $search_in_value->key) ? 'selected' : ''; ?>
                                                value="<?php echo $search_in_value->key ?>"><?php echo $search_in_value->value ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 input-button">
                        <input type="hidden" name="advanced" id="advanced" value="true">
                        <?php if (!is_mdt_user()) : ?>
                            <input type="submit" id="advanced-search" value="submit">
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (is_mdt_user()) : ?>
                    <!-- starff start -->
                    <div class="row">
                        <div class="col-md-12">
                            <h3>MDT Search Options Enabled &#40; Staff Only &#41;</h3>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="releaseFlag">Contains</label>
                                <select class="releaseFlag" name="releaseFlag" id="releaseFlag">
                                    <?php if (!empty($staff_search_contains)) : ?>
                                        <?php foreach ($staff_search_contains as $key => $value) : ?>
                                            <option <?php echo ($key == $release_flag) ? 'selected' : ''; ?>
                                                    value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="docID">DOC ID</label>
                                <input type="text" id="docID" name="docID" value="<?php echo $doc_id; ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="url">URL, ALT URL</label>
                                <input type="text" id="url" name="url" value="<?php echo $url; ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="source">SOURCE, REPORT NUMBER</label>
                                <input type="text" id="source" name="source" value="<?php echo $source; ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="notes">NOTES, RIGHTS, RETRIEVED FROM</label>
                                <input type="text" id="notes" name="notes" value="<?php echo $notes; ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a class="btn-help btn-help-notext" href=""></a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="">FAST SUBJECT</label>
                                <select name="fastSubject" id="fastSubject">
                                    <option value="">
                                        Any Subject
                                    </option>
                                    <?php
                                    if (!empty($fast_subjects_list) ) : ?>
                                        <?php foreach ($fast_subjects_list as $subject) : ?>
                                            <option <?php echo ($fast_subject == $subject) ? 'selected' : ''; ?>
                                                    value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row staff-search-bottom-row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="country">CONTAINS</label>
                                <select name="country" id="country">
                                    <option value="">Any Country</option>
                                    <?php
                                    if (!empty($staff_settings) && isset($staff_settings->countries)) : ?>
                                        <?php foreach ($staff_settings->countries as $s_country) : ?>
                                            <option <?php echo ($country == $s_country) ? 'selected' : ''; ?>
                                                    value="<?php echo $s_country; ?>"><?php echo $s_country; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="state">STATE</label>
                                <select name="state" id="state">
                                    <option value="">Any State</option>
                                    <?php
                                    if (!empty($staff_settings) && isset($staff_settings->states)) : ?>
                                        <?php foreach ($staff_settings->states as $s_state) : ?>
                                            <option <?php echo ($state == $s_state) ? 'selected' : ''; ?>
                                                    value="<?php echo $s_state; ?>"><?php echo $s_state; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="">MODIFIED BY</label>
                                <select name="modifiedBy" id="modifiedBy">
                                    <option value="">Anyone</option>
                                    <?php
                                    if (!empty($staff_settings) && isset($staff_settings->staff)) : ?>
                                        <?php foreach ($staff_settings->staff as $staff) : ?>
                                            <option <?php echo ($modified_by == $staff->email) ? 'selected' : ''; ?>
                                                    value="<?php echo $staff->email; ?>"><?php echo $staff->name; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="">MODIFIED DATE</label>
                                <div class="row" id="date-range-picker-container">
                                    <div class="col-md-6 date-input-wrapper">
                                        <input type="text" value="<?php echo $modified_from; ?>" name="modifiedFrom"
                                               id="modifiedFrom"
                                               data-date-orientation="bottom right"
                                               data-date-z-index-offset=999
                                               data-date-autoclose="true"
                                               data-date-clear-btn="true"
                                               data-provide="datepicker"
                                               data-date-container="#date-range-picker-container"
                                               data-date-format="yyyy-mm-dd" onkeydown="return false"
                                               class="datepicker date-input" autocomplete="off" placeholder="From">
                                    </div>
                                    <div class="col-md-6 date-input-wrapper">
                                        <input type="text" value="<?php echo $modified_to; ?>" name="modifiedTo"
                                               id="modifiedTo"
                                               data-date-orientation="bottom right"
                                               data-date-autoclose="true"
                                               data-date-clear-btn="true"
                                               data-provide="datepicker"
                                               data-date-container="#date-range-picker-container"
                                               data-date-format="yyyy-mm-dd" onkeydown="return false"
                                               class="datepicker date-input" autocomplete="off" placeholder="To">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="createdBy">CREATED BY</label>
                                <select name="createdBy" id="createdBy">
                                    <option value="">Anyone</option>
                                    <?php
                                    if (!empty($staff_settings) && isset($staff_settings->staff)) : ?>
                                        <?php foreach ($staff_settings->staff as $staff) : ?>
                                            <option
                                                <?php echo ($created_by == $staff->email) ? 'selected' : ''; ?>
                                                    value="<?php echo $staff->email; ?>"><?php echo $staff->name; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="">CREATED DATE</label>
                                <div class="row" id="date-range-picker-container">
                                    <div class="col-md-6 date-input-wrapper">
                                        <input type="text" value="<?php echo $created_from; ?>" name="createdFrom"
                                               id="createdFrom"
                                               data-date-orientation="bottom right"
                                               data-date-z-index-offset=999
                                               data-date-autoclose="true"
                                               data-date-clear-btn="true"
                                               data-provide="datepicker"
                                               data-date-container="#date-range-picker-container"
                                               data-date-format="yyyy-mm-dd" onkeydown="return false"
                                               class="datepicker date-input" autocomplete="off" placeholder="From">
                                    </div>
                                    <div class="col-md-6 date-input-wrapper">
                                        <input type="text" value="<?php echo $created_to; ?>" name="createdTo"
                                               id="createdTo"
                                               data-date-orientation="bottom right"
                                               data-date-autoclose="true"
                                               data-date-clear-btn="true"
                                               data-provide="datepicker"
                                               data-date-container="#date-range-picker-container"
                                               data-date-format="yyyy-mm-dd" onkeydown="return false"
                                               class="datepicker date-input" autocomplete="off" placeholder="To">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12  input-button">
                            <input type="submit" id="advanced-search" value="SEARCH">
                        </div>
                    </div>
                <?php endif; ?>
                <!-- staff end -->
            </form>
        </div>
    </div>
</div>
<div class="" id="bottom-row-cont">
    <!-- "staff-row-cont-show" class should togle when staff login -->
    <div class="container">
        <div class="bottom-row">
            <form action="" autocomplete="off" method="get" id="mdt-advanced-search-form">
                <input type="hidden" name="" id="" value="true">
            </form>
        </div>
    </div>
</div>

