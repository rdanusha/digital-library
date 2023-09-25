<?php
get_header(); ?>

    <section class="bs-section--search-results-detail-top-nav-section previous-releases">
        <div class="container secondary-nav">
            <div class="row">
                <div class="col-12">
                    <a class="secondary-nav__go-back-btn button"
                       href="<?php echo home_url("/critical-release?cr=1"); ?>">
                        Go Back to Weekly Release
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php if (!is_auth_user()) : ?>
    <section class="search-results-sign-in bs-section--search-results-sign-in-section previous-releases">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <span class="icon"></span>
                    <p>Only 2/3! You are seeing results from the Public Collection, not the complete Full Collection. <a
                                href="<?php echo home_url('/sign-in'); ?>">Sign in</a> to search everything (see
                        eligibility).</p>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

    <section
            class="bs-section--previous-release-title-section <?php if (!is_auth_user()) : ?>signed-out<?php endif; ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-12 header-col">
                    <h1>Critical Releases in Homeland Security : Previous Releases </h1>
                    <!--                    <a href="">Subscribe</a>-->
                </div>
            </div>
        </div>
    </section>

    <section
            class="bs-section--search-results-posts-section  search-results-posts bs-section--previous-release-posts-section">
        <div class="container">
            <div class="row mid-row">
                <div class="col-12 col-md-4 col-xl-3 left">
                    <h3>Refine Results</h3>
                    <form id="cr-form">
                        <input type="hidden" name="pcr" value="1">
                        <input type="hidden" name="start" id="start" value="<?php echo $start; ?>">
                        <input type="hidden" name="rows" id="rows" value="<?php echo $rows; ?>">
                        <label>Sort by</label>
                        <select class="select-field filter-cr" name="sort" id="sort">
                            <option value="DESC" <?php echo ($get_sort == 'DESC') ? 'selected' : ''; ?>>Newest to
                                oldest
                            </option>
                            <option value="ASC" <?php echo ($get_sort == 'ASC') ? 'selected' : ''; ?>>Oldest to newest
                            </option>
                        </select>
                        <hr>
                        <label>Select Year</label>
                        <select class="select-field filter-cr" name="year" id="year">
                            <?php if (!empty($years)) : ?>
                                <?php foreach ($years as $year) : ?>
                                    <option <?php echo ($get_year == $year) ? 'selected' : ''; ?>
                                            value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label>Select Month</label>
                        <select class="select-field filter-cr" name="month" id="month">
                            <?php if (!empty($months)) : ?>
                                <?php foreach ($months as $month) : ?>
                                    <option <?php echo ($get_month == $month) ? 'selected' : ''; ?>
                                            value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="result-container" id="result-container">
                            <?php if (!empty($filter_checkbox_data)): ?>
                                <?php foreach ($filter_checkbox_data as $checkbox_data) : ?>
                                    <div class="row data-row">
                                        <div class="checkbox-div">
                                            <input class="filter-checkbox" name="crid" type="checkbox"
                                                <?php echo ($crid == $checkbox_data->id) ? 'checked' : ''; ?>
                                                   id="<?php echo $checkbox_data->id; ?>"
                                                   value="<?php echo $checkbox_data->id; ?>"/>
                                            <label for="<?php echo $checkbox_data->id; ?>"><?php echo $checkbox_data->identifier ?></label>
                                        </div>
                                        <div class="counter-div">
                                            <h6></h6>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </form>
                    <input type="hidden" name="ajx_start" id="ajx_start" value="0">
                </div><!-- left -->
                <div class="col-12 col-md-8 col-xl-9 right">
                    <div class="row right-inner-row">
                        <div class="col-12 col-md-4 inner-left">
                            <h3><?php echo $num_found; ?> Results Found</h3>
                        </div>
                        <div class="col-12 col-md-8 inner-right">
                            <div class="row m-0 right-top">
                                <select class="select-field" id="result-per-page">
                                    <option value="30" <?php echo ($rows == 30) ? 'selected' : ''; ?>>Show 30 Results
                                        Per
                                        Page
                                    </option>
                                    <option value="60" <?php echo ($rows == 60) ? 'selected' : ''; ?>>Show 60 Results
                                        Per
                                        Page
                                    </option>
                                    <option value="90" <?php echo ($rows == 90) ? 'selected' : ''; ?>>Show 90 Results
                                        Per
                                        Page
                                    </option>
                                    <option value="120" <?php echo ($rows == 120) ? 'selected' : ''; ?>>Show 120 Results
                                        Per
                                        Page
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="search-content">
                        <?php include('hsdl-mdt-search-public-search-result-common-display.php'); ?>
                    </div>
                    <div class="row loading-indicator" id="loading_indicator">
                        <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . '/image/loading.gif' ?>">
                    </div>

                    <div class="row load-more">
                        <?php if ($is_load_more) : ?>
                            <button id="load_more">Load More Results</button>
                        <?php endif ?>
                    </div>

                </div><!-- right -->
            </div><!-- mid-row -->
        </div>
    </section>
    <script>
        (($, window, document) => {
            'use strict';
            $('#loading_indicator').hide();
            $(() => {
                const submitForm = () => {
                    $('#cr-form').submit();
                }
                //result per page change action
                $('#result-per-page').on('change', (e) => {
                    e.preventDefault();
                    $('#cr-form #rows').val(e.target.value);
                    submitForm();
                });
                //filters on change action
                $('.filter-cr').on('change', (e) => {
                    e.preventDefault();
                    submitForm();
                });
                //check box on change action
                $(document).on('change', '.filter-checkbox', (e) => {
                    e.preventDefault();
                    let $_this = e.target;
                    $('.filter-checkbox').not($_this).prop('checked', false);
                    submitForm();
                });
                //load more click action
                $(document).on('click', '#load_more', (e) => {
                    e.preventDefault();
                    $('#load_more').attr('disabled', true);
                    let start = parseInt($('#ajx_start').val());
                    let rows = parseInt($('#rows').val());
                    let sort = $('#sort').val();
                    let year = $('#searchterm').val();
                    let month = $('#type').val();
                    let nonce = hsdl_mdt_ajax.nonce;
                    start = start + rows;
                    // This does the ajax request
                    $.ajax({
                        url: hsdl_mdt_ajax.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
                        type: 'post',
                        data: {
                            'action': 'get_previous_releases_result_ajx',
                            'ajax': true,
                            'start': start,
                            'rows': rows,
                            'sort': sort,
                            'nonce': nonce,
                            'year': year,
                            'month': month,
                        },
                        dataType: "json",
                        beforeSend: function () {
                            $('#loading_indicator').show();
                        },
                        success: function (response) {
                            $('#load_more').attr('disabled', false);
                            $('#loading_indicator').hide();
                            if (response.result_html) {
                                $('.search-content').append(response.result_html);
                            }
                            let start = parseInt(response.record_start);
                            $('#ajx_start').val(start);
                            if (!response.is_load_more) {
                                $('#load_more').hide();
                            }
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                });
            });
        })(jQuery, window, document); // or even jQuery.noConflict()
    </script>
<?php get_footer(); ?>