<?php
get_header();
?>
    <section class="bs-section--alerts-and-subcriptions-top-nav-section">
        <div class="container secondary-nav">
            <div class="row">
                <div class="col-12">
                    <a class="secondary-nav__go-back-btn button"
                       href="<?php echo home_url('/search-result/?search=1'); ?>">Go Back to Search
                        Results</a>
                    <button class="secondary-nav__weekly-report-btn">Search Results | Alerts & Subscriptions
                        to <?php echo (isset($auth_user) && !empty($auth_user)) ? $auth_user->email : ''; ?></button>
                    <a href="" class="alert-added-title">ALERTS & SUBSCRIPTIONS
                        TO <?php echo (isset($auth_user) && !empty($auth_user)) ? $auth_user->email : ''; ?></a>
                    <a href="/alerts-and-subscriptions/" class="alert-faq">FAQ about Alerts & Subscriptions</a>
                </div>
            </div>
        </div>
    </section>
    <section class="bs-section--alerts-and-subcriptions-title-section">
        <div class="container">
            <h1>New Content Alerts</h1>
        </div>
    </section>
    <section class="bs-section--alerts-and-subcriptions-alerts-section">
        <div class="container">
            <?php if ($action == 'add'): ?>
                <div class="add-alert-container">
                    <div class="row">
                        <div class="col-md-9">
                            <h3>Add this Alert?</h3>
                            <input class="alert-input" disabled checked value="yes" type="checkbox" name="alert" id="">
                            <label for="alert"> <a href="<?php echo $confirm_url; ?>">Yes, add Alert</a></label>
                        </div>
                    </div>
                    <div class="alert-desc-container">
                        <div class="alert-desc">
                            <?php echo $display_text; ?>
                        </div>
                        <div class="cancel-btn-div">
                            <form action="<?php echo home_url('/alerts-subscriptions'); ?>">
                                <input type="hidden" name="d" value="1">
                                <button>Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="alerts-list-container">
                <?php if ($action == 'success'): ?>
                    <div class="alert-added">
                        <h3>Alert added.</h3>
                    </div>
                <?php endif; ?>
                <?php if (!empty($alerts)): ?>
                    <?php foreach ($alerts as $alert): ?>
                        <?php
                        $display_text = '';
                        $params = array('advanced' => 'true');
                        if ($alert->all) {
                            $display_text .= $this_class->generate_alert_describe_text('all', $alert->all);
                            $params['all'] = $alert->all;
                        } else {
                            $display_text .= $this_class->generate_alert_describe_text('empty', 'N/A');
                            $params['all'] = '';
                        }
                        if ($alert->any) {
                            $display_text .= $this_class->generate_alert_describe_text('any', $alert->any);
                            $params['any'] = $alert->any;
                        }
                        if ($alert->exact) {
                            $display_text .= $this_class->generate_alert_describe_text('exact', $alert->exact);
                            $params['exact'] = $alert->exact;
                        }
                        if ($alert->without) {
                            $display_text .= $this_class->generate_alert_describe_text('without', $alert->without);
                            $params['without'] = $alert->without;
                        }
                        if ($alert->searchfield) {
                            $display_text .= $this_class->generate_alert_describe_text('searchfield', $alert->searchfield);
                            $params['type'] = $alert->searchfield;
                        }
                        if ($alert->publisher) {
                            $display_text .= $this_class->generate_alert_describe_text('publisher', $alert->publisher);
                            $params['publisher'] = $alert->publisher;
                        }
                        if ($alert->tabsection) {
                            $display_text .= $this_class->generate_alert_describe_text('tabsection', $alert->tabsection);
                            $params['tabsection'] = $alert->tabsection;
                        }
                        if ($alert->creator) {
                            $display_text .= $this_class->generate_alert_describe_text('creator', $alert->creator);
                            $params['creator'] = $alert->creator;
                        }
                        if ($alert->format) {
                            $display_text .= $this_class->generate_alert_describe_text('format', $alert->format);
                            $params['format'] = $alert->format;
                        }
                        if ($alert->language) {
                            $display_text .= $this_class->generate_alert_describe_text('language', $alert->language);
                            $params['language'] = $alert->language;
                        }
                        if ($alert->begindate) {
                            $display_text .= $this_class->generate_alert_describe_text('from', $alert->begindate);
                            $params['from'] = $alert->begindate;
                        }
                        if ($alert->enddate) {
                            $display_text .= $this_class->generate_alert_describe_text('to', $alert->enddate);
                            $params['to'] = $alert->enddate;
                        }

                        $search_query_string = http_build_query($params);
                        $search_url = home_url('/search-result?search=1&');
                        $search_url = $search_url . $search_query_string;
                        ?>
                        <div class="single-alert">
                            <div class="row">
                                <div class="col-md-9">
                                    <h5><?php echo $display_text; ?></h5>
                                </div>
                                <div class="col-md-3">
                                    <ul>
                                        <li><a href="<?php echo $search_url; ?>">See Current Results</a></li>
                                        <li>
                                            <a href="/alerts-subscriptions/?d=1&id=<?php echo $alert->id; ?>&action=delete">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (empty($alerts)): ?>
                <div class="alert-bottom-row">
                    <h5>You have not established any alerts</h5>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <!--<section class="bs-section--alerts-and-subcriptions-subcription-section">
        <div class="container">
            <h2>Subscriptions</h2>
            <div class="subscription-container">
                <div class="single-subscription">
                    <div class="row">
                        <div class="col-md-9">
                            <h3>Critical Releases in Homeland Security <span>(Sent every other Wednesday)</span></h3>
                        </div>
                        <div class="col-md-3">
                            <ul>
                                <li><a href="">See Previous</a></li>
                                <li><a href="">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="subscriptions-bottom-row">
                    <h5><span>Do you want to add a subscription for:</span> HSDL Quarterly Newsletter [ See Recent ]
                    </h5>
                </div>
            </div>
        </div>
    </section>-->
<?php get_footer(); ?>