<?php if (isset($documents) && count($documents) > 0) : ?>
    <?php
    $page_name = (isset($page_name))?$page_name:'search';
    ?>
    <?php foreach ($documents as $document) : ?>
        <?php
        $resource_type = '';
        $document_id = $document->DocID;
        $download_url = $this_class->get_document_download_url($document->DocID);
        if (isset($document->ResourceType) && !empty($document->ResourceType)) {
            $resource_type = implode(", ", $document->ResourceType);
        }
        $icon_class = 'document';
        if ($resource_type == "Text") {
            $icon_class = 'pdf';
        } elseif ($resource_type == "Video") {
            $icon_class = 'video';
        }
        $flag_class = '';

        if (is_auth_user() && is_mdt_user()) {
            if (isset($document->Collection) && !empty($document->Collection) &&
                array_key_exists($document->Collection, $release_flags)) {
                $release_flag = $release_flags[$document->Collection];
                switch ($release_flag) {
                    case "Needs Review":
                        $flag_class = 'icon-secondary-edit';
                        break;
                    case "Broken Link":
                        $flag_class = 'icon-secondary-link';
                        break;
                    case "Do Not Collect":
                        $flag_class = 'icon-secondary-close';
                        break;
                    case "Duplicate":
                        $flag_class = 'icon-secondary-duplicate';
                        break;
                    default:
                        $flag_class = '';
                }
            }
        }
        $abstract_link = home_url("/abstract/?details=1&did={$document_id}&page-name={$page_name}&restricted={$restricted}");
        ?>
        <div class="row post-row <?php echo $icon_class; ?>">
            <a href="<?php echo $abstract_link; ?>">
            <div class="icon">
            </div>
            </a>
            <div class="icon-secondary <?php echo $flag_class; ?>">
                <!-- icon-secondary-edit | icon-secondary-close | icon-secondary-link | icon-secondary-duplicate -->
            </div>

            <div class="content">
                <div class="inner-top">
                    <?php
                    if (isset($document->Publisher_text)) {
                        echo implode(", ", $document->Publisher_text);
                    }
                    ?>
                </div>
                <div class="inner-top"><?php echo date('d M, Y', strtotime($document->PublishDate)); ?>
                </div>
                <div class="inner-top">
                    <?php
                    if (isset($document->Series_text)) {
                        echo implode(", ", $document->Series_text);
                    }
                    ?>
                </div>
                <p><a href="<?php echo $abstract_link; ?>"><?php echo $document->Title_text; ?></a></p>
                <div class="expand-content">
                    <p><?php echo $document->Description_text; ?></p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <?php if (isset($document->Creator_nostem)) : ?>
                                <tr>
                                    <td>Creator</td>
                                    <td>
                                        <?php
                                        $author_array = [];
                                        if (isset($document->Creator_nostem) && count($document->Creator_nostem) > 0) {
                                            foreach ($document->Creator_nostem as $value) {
                                                $url = "/search-result/?search=1&start=$start&rows=$rows&sort=date&type=author&exact=$value&advanced=true";
                                                $author_array[] = "<a href='$url'>$value</a>";
                                            }
                                        }
                                        ?>
                                        <?php echo implode(", ", $author_array); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Publisher</td>
                                <td>
                                    <?php
                                    $publisher_array = [];
                                    if (isset($document->Publisher_text) && count($document->Publisher_text) > 0) {
                                        foreach ($document->Publisher_text as $value) {
                                            $url = "/search-result/?search=1&start=$start&rows=$rows&sort=date&type=publisher&exact=$value&advanced=true";
                                            $publisher_array[] = "<a href='$url'>$value</a>";
                                        }
                                    }
                                    ?>
                                    <?php echo implode(", ", $publisher_array); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td><?php echo date('d/m/Y', strtotime($document->PublishDate)); ?></td>
                            </tr>
                            <?php if (isset($document->Series_text)) : ?>
                                <tr>
                                    <td>Series</td>
                                    <td>
                                        <?php
                                        $series_array = [];
                                        if (count($document->Series_text) > 0) {
                                            foreach ($document->Series_text as $value) {
                                                $url = "/search-result/?search=1&start=$start&rows=$rows&sort=date&type=series&exact=$value&advanced=true";
                                                $series_array[] = "<a href='$url'>$value</a>";
                                            }
                                        }
                                        ?>
                                        <?php echo implode(", ", $series_array); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Retrieved From</td>
                                <td>
                                    <?php
                                    if (isset($document->RetrievedFrom)) :
                                        $text = strip_tags($document->RetrievedFrom);
                                        $textWithLinks = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" rel="nofollow">$1</a>', $text);
                                        echo $textWithLinks;
                                    endif;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Source</td>
                                <td><?php echo $document->Rights; ?></td>
                            </tr>
                            <tr>
                                <td>URL</td>
                                <td><a href="<?php echo $download_url; ?>"
                                       target="_blank"><?php echo $download_url; ?></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12">
                        <div class="row expand-view-summary">
                            <div class="col-12 col-lg-5 col-xl-7">
                                <span class="btn-primary-link less">Hide Summary</span>
                            </div>
                            <div class="col-12 col-lg-7 col-xl-5 btn-div">
                                <?php if($icon_class != 'video') : ?>
                                <a class="btn-download" href="<?php echo $download_url; ?>" target="_blank">Download</a>
                                <?php endif; ?>
                                <a class="btn-black-bg"
                                   href="<?php echo $abstract_link; ?>">View
                                    Detailed Info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row view-less">
                <div class="view-summary">
                    <span class="btn-primary-link more">QUICK SUMMARY</span>
                </div>
                <?php if($icon_class != 'video') : ?>
                <div class="download">
                    <a class="btn-primary-link" href="<?php echo $download_url; ?>" target="_blank">download</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
