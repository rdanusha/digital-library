<?php get_header(); ?>
<section class="wp-block-e25m-section bs-section bs-section-1 bs-section--search-navbar critical-release">
    <?php echo do_shortcode('[mdt_search_bar]'); ?>
</section>
<?php include('hsdl-mdt-search-public-mdt-toolbar-common-display.php'); ?>
<?php if (is_auth_user() && is_mdt_user()) : ?>
    <section
            class="bs-section--search-navbar-title critical-release <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0) ? 'toggle-hide' : ''; ?>">
        <div class="container">
            <div class="mdt-nav-title">
                <h2>Critical Releases</h2>
            </div>
        </div>
    </section>
<?php endif; ?>
<section
        class="bs-section--critical-releases-main-section <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0) ? '' : 'results-toggle-hide'; ?> <?php if (!is_auth_user()) :
            ?>results-signed-in<?php endif; ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mdt">
                <a class="btn-help" href="<?php echo home_url('/help-searching'); ?>">Help</a>
                <div class="mdt-toggle">
                    <div class="row">
                        <div class="col-md-12 mdt-toggle__wrapper">
                            <?php if (is_auth_user() && is_mdt_user()) : ?>
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
            <div class="col-md-12">
                <h2>Critical Releases in Homeland
                    Security: <?php echo (isset($release_details->identifier)) ? $release_details->identifier : ''; ?></h2>
                <p>Every two weeks, the HSDL identifies a brief, targeted collection of recently released documents
                    of particular interest or potential importance. We post the collection on the site and email it
                    to subscribers. <a href="">Click here to subscribe</a>. (You must have an <a
                            href="/request-an-individual-account/">individual account</a> in order to
                    subscribe.)</p>
                <h3><?php echo $records_count; ?> featured resources
                    <span>Updated <?php echo (isset($release_details->created)) ? date('M d, Y', strtotime($release_details->created)) : ''; ?></span>
                </h3>
                <div class="release-cards">
                    <div id="accordion">
                        <?php if (!empty($documents)) : ?>
                            <?php $i = 0; ?>
                            <?php foreach ($documents as $document) : ?>
                                <?php
                                $resource_type = '';
                                if (isset($document->ResourceType) && !empty($document->ResourceType)) {
                                    $resource_type = implode(", ", $document->ResourceType);
                                }
                                $resource = 'document';
                                if ($resource_type == "Text") {
                                    $resource = 'pdf';
                                } elseif ($resource_type == "Video") {
                                    $resource = 'video';
                                }
                                $download_url = $this_class->get_document_download_url($document->DocID);
                                ?>
                                <div class="release-card">
                                    <h4 class="release-card__title"><?php echo (isset($document->Title_text)) ? $document->Title_text : ''; ?></h4>
                                    <div class="release-card__files">
                                        <span class="release-card__file-actions release-card__file-actions--summary according-toggle"
                                              data-id="id_<?php echo $i; ?>">Show Summary</span>
                                        <a href="<?php echo $download_url; ?>" target="_blank"><span
                                                    class="release-card__file-actions release-card__file-actions--resource">Open resource (<?php echo $resource; ?>)</span></a>
                                        <div id="id_<?php echo $i; ?>" class="collapse">
                                            <p class="release-card__file-actions--summary-desc">
                                                <?php echo (isset($document->Description_text)) ? $document->Description_text : ''; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="release-card__details">
                                        <?php
                                        $publisher_array = [];
                                        if (isset($document->Publisher_text) && count($document->Publisher_text) > 0) {
                                            foreach ($document->Publisher_text as $value) {
                                                $publisher_array[] = $value;
                                            }
                                        }
                                        ?>
                                        <?php if (count($publisher_array)) : ?>
                                            <span class="release-card__detail release-card__detail--description">
                                            <?php echo implode(", ", $publisher_array); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php
                                        $author_array = [];
                                        if (isset($document->Creator_nostem) && count($document->Creator_nostem) > 0) {
                                            foreach ($document->Creator_nostem as $value) {
                                                $author_array[] = $value;
                                            }
                                        }
                                        ?>
                                        <?php if (count($author_array)) : ?>
                                            <span class="release-card__detail release-card__detail--author">
                                                <?php echo implode(", ", $author_array); ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="release-card__detail release-card__detail--date"><?php echo date('d/m/Y', strtotime($document->PublishDate)); ?></span>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="close-the-date-conteiner">
                    <?php if (!empty($close_publish_tag_data)) : ?>
                        <p class="text-normal">
                            <?php
                            $id = (isset($close_publish_tag_data->id)) ? $close_publish_tag_data->id : '';
                            $type = (isset($close_publish_tag_data->type)) ? $close_publish_tag_data->type : '';
                            $make_link = (isset($close_publish_tag_data->makeLink)) ? $close_publish_tag_data->makeLink : '';
                            ?>
                            <?php if ($make_link) : ?>
                            <a href="/critical-releases/?cr=1&id=<?php echo $id; ?>">
                                <?php endif; ?>
                                <span>
                                <?php echo (isset($close_publish_tag_data->identifier)) ? $close_publish_tag_data->identifier : ''; ?>
                               </span>
                                <?php if ($make_link) : ?>
                            </a>
                        <?php endif; ?>
                            <a href="/critical-releases/?cr=1&id=<?php echo $id; ?>&action=<?php echo $type; ?>">(<?php echo ucfirst($type); ?>
                                the list)</a>
                        </p>
                        <a href=""><p class="text-bold">Run the mail job</p></a>
                    <?php endif; ?>
                </div>
                <div class="previous-releases">
                    <a href="<?php echo home_url('/previous-critical-releases/?pcr=1') ?>"><p>Previous Releases</p></a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
