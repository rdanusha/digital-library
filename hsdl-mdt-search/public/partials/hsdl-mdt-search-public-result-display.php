<?php get_header(); ?>
<?php include('hsdl-mdt-search-public-common-variables.php'); ?>
<section class="wp-block-e25m-section bs-section bs-section-1 bs-section--search-navbar">
    <?php echo do_shortcode('[mdt_search_bar]'); ?>
</section>
<?php include('hsdl-mdt-search-public-mdt-toolbar-common-display.php'); ?>
<?php if (is_auth_user() && is_mdt_user()) : ?>
    <section
            class="bs-section--search-navbar-title <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0) ? 'toggle-hide' : ''; ?>">
        <div class="container">
            <div class="mdt-nav-title">
                <h2>Search The HSDL</h2>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if (!is_auth_user()) : ?>
    <section class="bs-section--search-results-sign-in-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <span class="icon"></span>
                    <p>Only 2/3! You are seeing results from the Public Collection, not the complete Full Collection. <a
                                href="<?php echo home_url('/sign-in'); ?>">Sign in</a> to search everything (<a
                                href="<?php echo home_url('/request-an-individual-account'); ?>">see eligibility</a>).
                    </p>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ((is_hsdl_restricted_participant() || is_ip_user()) && $is_restricted_collections == 'true') : ?>
    <section
            class="bs-section--search-results-sign-in-section restricted-collection <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0 || empty($_SESSION['mdt'])) ? '' : 'results-toggle-hide'; ?>
            <?php if (!is_auth_user()) : ?> results-signed-in <?php endif; ?>">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p>
                        <span class="icon"></span>Warning: Documents in the Restricted Collection are CONTROLLED UNCLASSIFIED INFORMATION (CUI) or
                        UNCLASSIFIED//FOR OFFICIAL USE ONLY (U//FOUO). They contain information that may be exempt from
                        public release under the Freedom of Information Act (5 U.S.C. 552). They are to be controlled,
                        stored, handled, transmitted, distributed, and disposed of in accordance with <a target="_blank" href="https://hsdl-cms.s3.amazonaws.com/uploads/2021/06/692111.pdf">DHS policy [pdf]</a>
                        relating to CUI or FOUO information and are not to be released to the public, the media, or
                        other personnel who do not have a valid "need-to-know" without prior approval of an authorized
                        DHS official. No portion of these reports should be furnished to the media, either in written or
                        verbal form. <a href="/about-the-restricted-collection/">More rules for use / help with this section.</a></p>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<section
        class="bs-section--search-results-posts-section search-results-posts <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0 || empty($_SESSION['mdt'])) ? '' : 'results-toggle-hide'; ?> <?php if (!is_auth_user()) :
            ?> results-signed-in <?php endif; ?> <?php echo ((is_hsdl_restricted_participant() || is_ip_user()) && $is_restricted_collections == 'true') ? ' restricted-collection' : ''; ?>
"><!-- if only restricted  - >  must add this class "restricted-collection " -->
    <div class="container">
        <div class="row top-row">
            <div class="col-md-12 left">
                <?php if (!empty($search_term) || !empty($any) || !empty($exact) || !empty($without) ||
                    !empty($from) || !empty($to)) : ?>
                    <?php $query_args = array('searchterm', 'all', 'any', 'exact', 'without', 'from', 'to'); ?>
                    <div class="search-criteria" data-url="<?php echo home_url(remove_query_arg($query_args)); ?>">
                        <span class="close"></span>
                        Searching for terms:
                        <?php if (isset($any) && !empty($any)) : ?>
                            ANY (<?php echo $any; ?>)
                        <?php endif; ?>
                        <?php if (isset($search_term) && !empty($search_term)) : ?>
                            ALL (<?php echo $search_term; ?>)
                        <?php endif; ?>
                        <?php if (isset($exact) && !empty($exact)) : ?>
                            EXACT: "<?php echo $exact; ?>"
                        <?php endif; ?>
                        <?php if (isset($without) && !empty($without)) : ?>
                            WITHOUT: "<?php echo $without; ?>"
                        <?php endif; ?>
                        in: <?php echo $filter_bar_text; ?>
                        <?php if ((isset($from) && !empty($from)) && (isset($to) && !empty($to))) : ?>
                            published from: <?php echo $from; ?> to <?php echo $to; ?>
                        <?php elseif (isset($from) && !empty($from)) : ?>
                            published from: <?php echo $from; ?> to *
                        <?php elseif (isset($to) && !empty($to)) : ?>
                            published from: * to <?php echo $to; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($facets_array)) : ?>
                    <?php $counter = 0; ?>
                    <?php foreach ($facets_array as $facet => $facet_items) : ?>
                        <?php
                        $selected_value = get_query_var($facet) ?: '';
                        ?>
                        <?php if (count($facet_items) > 0) : ?>
                            <?php foreach ($facet_items as $item) : ?>
                                <?php if ($selected_value == $item->type) : ?>
                                    <div class="search-criteria"
                                         data-url="<?php echo home_url(remove_query_arg($facet)); ?>"><span
                                                class="close"></span> <?php echo $facet_mapping[$facet] . ' is ' . $item->type; ?>
                                    </div>
                                <?php endif ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-md-12 right">
                <a class="btn-help" href="<?php echo home_url('/help-searching'); ?>">Help</a>
                <div class="mdt-toggle">
                    <div class="row">
                        <div class="col-md-12 mdt-toggle__wrapper">
                            <?php if (is_auth_user() && is_mdt_user()) : ?>
                                <span class="mdt-toggle__link"><a href="">MDT</a></span>
                                <div class="mdt-toggle__btn toggle-btn">
                                    <label class="switch">
                                        <?php
                                        if (isset($_SESSION['mdt']) && $_SESSION['mdt'] !== '') {
                                            $mdt_switch = $_SESSION['mdt'];
                                        }
                                        ?>
                                        <input id="mdt-toggle-checkbox"
                                               type="checkbox" <?php echo (!empty($mdt_switch)) ? 'checked' : ''; ?>
                                               value="<?php echo (!empty($mdt_switch)) ? '1' : '0'; ?>">
                                        <span class="slider round"></span>
                                    </label>
                                    <label class="mdt-toggle__btn-text">MDT</label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mid-row">
            <?php include('hsdl-mdt-search-public-search-facet-common-display.php'); ?>
            <div class="col-12 col-md-8 col-xl-9 right">
                <div class="row right-inner-row">
                    <div class="col-12 col-md-4 inner-left">
                        <h3><?php echo $num_found; ?> Results Found</h3>
                    </div>
                    <div class="col-12 col-md-8 inner-right">
                        <div class="row m-0 right-top">
                            <select class="select-field" id="result-per-page">
                                <option value="30" <?php echo ($rows == 30) ? 'selected' : ''; ?>>Show 30 Results Per
                                    Page
                                </option>
                                <option value="60" <?php echo ($rows == 60) ? 'selected' : ''; ?>>Show 60 Results Per
                                    Page
                                </option>
                                <option value="90" <?php echo ($rows == 90) ? 'selected' : ''; ?>>Show 90 Results Per
                                    Page
                                </option>
                                <option value="120" <?php echo ($rows == 120) ? 'selected' : ''; ?>>Show 120 Results Per
                                    Page
                                </option>
                            </select>
                            <?php if (is_auth_user() && $is_restricted_collections != 'true') : ?>
                                <a class="btn-mail"
                                   href="/alerts-subscriptions/?d=1&<?php echo $alert_query_string; ?>">Set an Alert to
                                    Get Future Results</a>
                                <div class="more-options">
                                    <ul>
                                        <li><a class="download-result"
                                               data-url="<?php echo $result_download_url . '&numfound=' . $num_found; ?>"
                                               href="<?php echo $result_download_url . '&numfound=' . $num_found; ?>">Download
                                                Search</a></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
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
    (function ($, window, document) {
        'use strict';
        $('#loading_indicator').hide();
        // Load more click function
        $(document).on('click', '#load_more', function (e) {
            e.preventDefault();
            $('#load_more').attr('disabled', true);
            let start = parseInt($('#ajx_start').val());
            let rows = parseInt($('#rows').val());
            let sort = parseInt($('#sort').val());
            let search_term = $('#searchterm').val();
            let type = $('#type').val();
            let nonce = '<?php echo wp_create_nonce('load_more_data') ?>';
            start = start + rows;

            //get facet values
            let subjects = $('#subjects').val();
            let creator = $('#creator').val();
            let series = $('#series').val();
            let lists = $('#lists').val();
            let format = $('#format').val();
            let language = $('#language').val();
            let publisher = $('#publisher').val();
            let tabsection = $('#tabsection').val();


            //advanced filter values
            let advanced = $('#is_advanced').val();
            let all = $('#all').val();
            let any = $('#any').val();
            let exact = $('#exact').val();
            let without = $('#without').val();
            let from = $('#from').val();
            let to = $('#to').val();

            //staff
            let releaseFlag = $('#releaseFlag').val();
            let docID = $('#doc_id').val();
            let url = $('#url').val();
            let source = $('#source').val();
            let notes = $('#notes').val();
            let country = $('#country').val();
            let state = $('#state').val();
            let modifiedBy = $('#modifiedBy').val();
            let modifiedFrom = $('#modifiedFrom').val();
            let modifiedTo = $('#modifiedTo').val();
            let createdBy = $('#createdBy').val();
            let createdFrom = $('#createdFrom').val();
            let createdTo = $('#createdTo').val();
            let fastSubject = $('#fastSubject').val();
            let pageName = $('#page-name').val();
            let restricted = $('#restricted').val();


            // This does the ajax request
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>', // or example_ajax_obj.ajaxurl if using on frontend
                type: 'post',
                data: {
                    'action': 'display_results_ajx',
                    'ajax': true,
                    'start': start,
                    'rows': rows,
                    'sort': sort,
                    'search_term': search_term,
                    'type': type,
                    'nonce': nonce,
                    'subjects': subjects,
                    'creator': creator,
                    'series': series,
                    'lists': lists,
                    'format': format,
                    'language': language,
                    'publisher': publisher,
                    'tabsection': tabsection,
                    'advanced': advanced,
                    'all': all,
                    'any': any,
                    'exact': exact,
                    'without': without,
                    'from': from,
                    'to': to,
                    'releaseFlag': releaseFlag,
                    'docID': docID,
                    'url': url,
                    'source': source,
                    'notes': notes,
                    'country': country,
                    'state': state,
                    'modifiedBy': modifiedBy,
                    'modifiedFrom': modifiedFrom,
                    'modifiedTo': modifiedTo,
                    'createdBy': createdBy,
                    'createdFrom': createdFrom,
                    'createdTo': createdTo,
                    'fastSubject': fastSubject,
                    'pageName': pageName,
                    'restricted': restricted,
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

        const submitForm = () => {
            let is_advanced = $('#is_advanced').val();
            if (is_advanced == 'true') {
                $('#mdt-advanced-search-form').submit();
            } else {
                $('#mdt-search-form').submit();
            }
        }

        //Facet show more click function
        $(".show-more").on('click', function (e) {
            e.preventDefault();
            let target = $(this).data('type');
            $(`.${target}:hidden`).slice(0, 6).slideDown();
            if ($(`.${target}:hidden`).length == 0) {
                $(this).fadeOut('slow');
            }
            $('html,body').animate({
                scrollTop: $(this).offset().top - 350
            }, 1500);
        });

        //Facet checkbox on click action
        $(document).on('change', '.facet-filter-checkbox', function (e) {
            e.preventDefault();
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            let $box = $(this);
            let type = $(this).data('type');
            if ($box.is(":checked")) {
                $(`#mdt-advanced-search-form #${type}`).val($(this).val());
                $(`#mdt-search-form #${type}`).val($(this).val());
                $(`#mdt-advanced-search-form #${type}`).prop("disabled", false);
                $(`#mdt-search-form #${type}`).prop("disabled", false);
            } else {
                $(`#mdt-advanced-search-form #${type}`).val('');
                $(`#mdt-search-form #${type}`).val('');
            }
            $("#search-in option:first").attr('selected', 'selected');
            submitForm();
        });

        //Result per page on click action
        $('#result-per-page').on('change', function (e) {
            e.preventDefault();
            $('#mdt-advanced-search-form #rows').val($(this).val());
            $('#mdt-search-form #rows').val($(this).val());
            submitForm();
        });

        //Search in field on change action
        $('#search-in').on('change', function (e) {
            e.preventDefault();
            $('#mdt-advanced-search-form #tabsection').val($(this).val());
            $('#mdt-search-form #tabsection').val($(this).val());
            $('#mdt-advanced-search-form #tabsection').prop("disabled", false);
            $('#mdt-search-form #tabsection').prop("disabled", false);
        });

        //Sort field on change action
        $('#sort-field').on('change', function (e) {
            e.preventDefault();
            $('#mdt-advanced-search-form #sort').val($(this).val());
            $('#mdt-search-form #sort').val($(this).val());
            submitForm();
        });

        //Search filter indicator labels close action
        $(".search-criteria span.close").on('click', function (e) {
            e.preventDefault();
            $(this).parent().hide();
            let url = $(this).parent().data('url');
            location.href = url;
        });

        //reset button on click action
        $('#reset-btn').on('click', function (e) {
            e.preventDefault();
            let url = $(this).data('url');
            location.href = url;
        });

        $('.download-result').on('click', function (e) {
            let url = $(this).data('url');
            window.location.href = url;
        });

        $(document).on('change', '.facet-filter-category-checkbox', function (e) {
            e.preventDefault();
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            let $box = $(this);
            let url = '';
            if ($box.is(":checked")) {
                url = $(this).data('url');
            } else {
                url = $(this).data('unchecked-url');
            }
            location.href = url;
        });
    })(jQuery, window, document); // or even jQuery.noConflict()
</script>

<?php get_footer(); ?>


