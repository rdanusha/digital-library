<?php
get_header();
global $wp;
?>
<?php if (!empty($document)) : ?>
    <section class="bs-section--search-results-detail-top-nav-section">
        <div class="container secondary-nav">
            <div class="row">
                <div class="col-12">
                    <a class="secondary-nav__go-back-btn button" href="<?php echo home_url("/search-result?search=1&page-name={$page_name}&restricted={$restricted}"); ?>">Go
                        Back to Search
                        Results</a>
                    <a class="secondary-nav__weekly-report-btn"
                       href="<?php echo home_url('/critical-releases/?cr=1') ?>">Weekly Report</a>
                </div>
            </div>

        </div>
    </section>
    <?php include('hsdl-mdt-search-public-mdt-toolbar-common-display.php'); ?>
    <?php if (is_auth_user() && is_mdt_user()) : ?>
        <section
                class="bs-section--abstract-mdt-nav-section <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0) ? 'toggle-hide' : ''; ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-container">
                            <ul>
                                <li>
                                    <a class="nav-link"
                                       href="<?php echo home_url("/mdt/#/?did={$document->docID}&redirect={$current_url}"); ?>">
                                        Edit Record
                                    </a>
                                </li>
                                <?php if (in_array($document->collection, array(0, 1))) : //display only if enduser flag ?>
                                <li>
                                    <span class="nav-link" id="to-series-nav-item" data-toggle="modal"
                                          data-target="#addToSeries">To Series</span>
                                </li>
                                <li>
                                    <span class="nav-link" id="to-list-nav-item" data-toggle="modal"
                                          data-target="#addToList">To List</span>
                                </li>
                                <li>
                                    <span class="nav-link" id="fast-subj-nav-item" data-toggle="modal"
                                          data-target="#fastSubject">Fast Subject</span>
                                </li>

                                    <?php if (isset($nomination_tags) && !empty($nomination_tags)) : ?>
                                        <?php foreach ($nomination_tags as $nomination) : ?>
                                            <?php
                                            $nomination_date = date('M d', strtotime($nomination->identifier));
                                            ?>
                                            <?php if ($nomination->isNominated) : ?>
                                                <?php
                                                $nomination_text = 'Nominated ' . $nomination_date . ' CR';
                                                ?>
                                                <li class="nav-link-nominated">
                                                    <a class="nav-link"
                                                       href="/critical-releases/?cr=1&id=<?php echo $nomination->id; ?>">
                                                        <?php echo $nomination_text; ?>
                                                    </a>
                                                </li>
                                                <li class="nav-link-delete" data-docid="<?php echo $document->docID; ?>"
                                                    data-cr="<?php echo $nomination->id; ?>">
                                                    <a class="nav-link" data-docid="<?php echo $document->docID; ?>"
                                                       data-cr="<?php echo $nomination->id; ?>"
                                                       href="javascript:void(0)<?php //echo $url . '&remove=1&cr=' . $nomination->id; ?>">
                                                        <i class="delete" title="Remove from list">Delete</i>
                                                    </a>
                                                </li>
                                            <?php else : ?>
                                                <?php
                                                $nomination_text = 'Nominate ' . $nomination_date . ' CR';
                                                ?>
                                                <li class="nav-link-nominate"
                                                    data-docid="<?php echo $document->docID; ?>"
                                                    data-cr="<?php echo $nomination->id; ?>">
                                                    <a class="nav-link" data-docid="<?php echo $document->docID; ?>"
                                                       data-cr="<?php echo $nomination->id; ?>"
                                                       href="javascript:void(0)<?php //echo $url . '&nominate=1&cr=' . $nomination->id; ?>">
                                                        <?php echo $nomination_text; ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($display_flag) : ?>
                                    <div class="flag-container">
                                        <a class="mdt-flag" href="javascript:void(0)">flag</a>
                                    </div>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section
                class="bs-section--search-navbar-title <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0) ? 'toggle-hide' : ''; ?>">
            <div class="container">
                <div class="mdt-nav-title d-sm-inline-block">
                    <h2>Abstract</h2>
                </div>
                <div class="right help-btn d-sm-inline-block float-right">
                    <a class="btn-help btn-help-white" href="<?php echo home_url('/help-searching'); ?>">Help</a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section
            class="bs-section--search-results-detail-bottom-section <?php echo (!is_auth_user()) ? 'default-gap' : ''; ?> <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0) ? '' : 'toggle-gap'; ?>">
        <div class="container">
            <div class="mdt-toggle">
                <div class="row">
                    <div class="col-md-12 mdt-toggle__wrapper">
                        <?php if (is_auth_user() && is_mdt_user()) : ?>
                            <!--                            <span class="mdt-toggle__link"><a href="">MDT</a></span>-->
                            <div class="mdt-toggle__btn toggle-btn">
                                <label class="switch">
                                    <?php
                                    if (isset($_SESSION['mdt']) && $_SESSION['mdt'] != '') {
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
            <div class="row content-row">
                <div class="col-md-3 left download">
                    <div class="download__img">
                        <span></span>
                    </div>
                    <a href="<?php echo $download_url; ?>" target="_blank" download class="download__btn">Download</a>
                </div>
                <div class="col-md-9 right">
                    <div class="details-container">
                        <?php if (isset($document->publishDate)) : ?>
                            <h2 class="details-container__date"><?php echo $document->publishDate ?></h2>
                        <?php endif; ?>
                        <?php if (isset($document->title)) : ?>
                            <h1 class="details-container__title"><?php echo $document->title; ?></h1>
                        <?php endif; ?>
                        <?php if (isset($document->description)) : ?>
                            <p class="details-container__description"><?php echo $document->description; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="details-table">
                        <h3 class="details-table__title">Details</h3>
                        <table class="table details-table__title">
                            <tbody>
                            <?php if (isset($document->reportNumber) && !empty($document->reportNumber)) : ?>
                                <tr>
                                    <td scope="row">Report Number</td>
                                    <td>
                                        <?php echo $document->reportNumber; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if (isset($document->addedCreators) && count($document->addedCreators) > 0) : ?>
                                <tr>
                                    <?php
                                    $author_array = [];
                                    foreach ($document->addedCreators as $value) {
                                        $url = "/search-result/?search=1&start=$start&rows=$rows&sort=date&type=author&exact=$value->ControlledName&advanced=true";
                                        $author_array[] = "<a href='$url' class='sr-table-a'>$value->ControlledName</a>";
                                    }
                                    ?>
                                    <td scope="row">Creator</td>
                                    <td><?php echo implode(", ", $author_array); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (isset($document->addedPublishers) && count($document->addedPublishers) > 0) : ?>
                                <tr>
                                    <td scope="row">Publisher</td>
                                    <td>
                                        <?php
                                        $publisher_array = [];
                                        foreach ($document->addedPublishers as $value) {
                                            $url = "/search-result/?search=1&start=$start&rows=$rows&sort=date&type=publisher&exact=$value->ControlledName&advanced=true";
                                            $publisher_array[] = "<a href='$url' class='sr-table-a'>$value->ControlledName</a>";
                                        }
                                        ?>
                                        <?php echo implode(", ", $publisher_array); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if (isset($document->publishDate) && !empty($document->publishDate)) : ?>
                                <tr>
                                    <td scope="row">Date</td>
                                    <td><?php echo date('d/m/Y', strtotime($document->publishDate)); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (isset($document->addedSeries) && count($document->addedSeries) > 0) : ?>
                                <tr>
                                    <td scope="row">Series</td>
                                    <td>
                                        <?php
                                        $series_array = [];
                                        foreach ($document->addedSeries as $value) {
                                            $url = "/search-result/?search=1&start=$start&rows=$rows&sort=date&type=series&exact=$value->ControlledName&advanced=true";
                                            $series_array[] = "<a href='$url' class='sr-table-a'>$value->ControlledName</a>";
                                        }
                                        ?>
                                        <?php echo implode(", ", $series_array); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if (isset($document->rights) && !empty($document->rights)) : ?>
                                <tr>
                                    <td scope="row">Copyright</td>
                                    <td><?php echo $document->rights; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (isset($document->retrievedFrom) && !empty($document->retrievedFrom)) : ?>
                                <tr>
                                    <td scope="row">Retrieved From</td>
                                    <td>
                                        <?php
                                        $text = strip_tags($document->retrievedFrom);
                                        $textWithLinks = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" class="sr-table-a" rel="nofollow">$1</a>', $text);
                                        echo $textWithLinks;
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($format)) : ?>
                                <tr>
                                    <td scope="row">Format</td>
                                    <td><?php echo $format; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($media_type)) : ?>
                                <tr>
                                    <td scope="row">Media Type</td>
                                    <td>
                                        <?php echo $media_type; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($download_url)) : ?>
                                <tr>
                                    <td scope="row">URL</td>
                                    <td><a href="<?php echo $download_url; ?>" target="_blank"
                                           class="sr-table-a"><?php echo $download_url; ?></a></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (is_hsdl_restricted_participant()) : ?>
                                <?php if (isset($document->addedFASTSubjects) && !empty($document->addedFASTSubjects)) : ?>
                                    <tr>
                                        <td scope="row">Subjects</td>
                                        <td>
                                            <?php foreach ($document->addedFASTSubjects as $subjects) : ?>
                                                <?php
                                                $url = "/search-result/?search=1&start=$start&rows=$rows&sort=dates&subjects=$value->ControlledName&exact=$value->ControlledName&advanced=true";
                                                ?>
                                                <a href="<?php echo $url; ?>"><?php echo $subjects->ControlledName; ?></a>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if (is_mdt_user()) : ?>
                            <div class="staff-table-container<?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0) ? 'toggle-hide' : ''; ?>">
                                <h3>Staff Only</h3>
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td scope="row">Document ID</td>
                                        <td><?php echo (isset($document->docID)) ? $document->docID : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Collection</td>
                                        <td>
                                            <?php if (isset($document->collection) && array_key_exists($document->collection, $collections)) : ?>
                                                <?php echo $collections[$document->collection]; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Release Flag</td>
                                        <td>
                                            <?php if (isset($document->collection) && array_key_exists($document->collection, $release_flags)) : ?>
                                                <?php echo $release_flags[$document->collection]; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Audience</td>
                                        <td><?php echo (isset($document->selectedAudience)) ? $document->selectedAudience : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Entered</td>
                                        <td>
                                            <?php echo (isset($document->dateOfRecordEntry)) ? $document->dateOfRecordEntry : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Created by</td>
                                        <td>
                                            <?php echo (isset($document->addedBy)) ? $document->addedBy : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Modified</td>
                                        <td>
                                            <?php echo (isset($document->dateLastModified)) ? date('Y-m-d H:i:s', strtotime($document->dateLastModified)) : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Modified by</td>
                                        <td>
                                            <?php echo (isset($document->lastModifiedBy)) ? $document->lastModifiedBy : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Subjects</td>
                                        <td>
                                            <?php if (isset($document->addedFASTSubjects) && !empty($document->addedFASTSubjects)) : ?>
                                                <?php foreach ($document->addedFASTSubjects as $subjects) : ?>
                                                    <?php echo $subjects->ControlledName; ?>;
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Country Coverage</td>
                                        <td>
                                            <?php if (isset($document->addedCountry) && !empty($document->addedCountry)) : ?>
                                                <?php foreach ($document->addedCountry as $country) : ?>
                                                    <?php echo $country->ControlledName; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">State Coverage</td>
                                        <td>
                                            <?php if (isset($document->addedState) && !empty($document->addedState)) : ?>
                                                <?php foreach ($document->addedState as $state) : ?>
                                                    <?php echo $state->ControlledName; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Resource Type</td>
                                        <td>
                                            <?php if (isset($document->selectedResources) && !empty($document->selectedResources)) : ?>
                                                <?php foreach ($document->selectedResources as $resource) : ?>
                                                    <?php echo $resource->ControlledName; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Language</td>
                                        <td>
                                            <?php if (isset($document->selectedLaguages) && !empty($document->selectedLaguages)) : ?>
                                                <?php foreach ($document->selectedLaguages as $language) : ?>
                                                    <?php
                                                    $key = array_search($language->ControlledName, $languages_map);
                                                    if ($key) {
                                                        echo $key . ':' . $language->ControlledName;
                                                    }
                                                    ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Actual URL</td>
                                        <td>
                                            <?php echo (isset($document->URL)) ? $document->URL : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Alternate URL</td>
                                        <td>
                                            <?php echo (isset($document->transcriptURL)) ? $document->transcriptURL : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Notes</td>
                                        <td>
                                            <?php echo (isset($document->notes)) ? $document->notes : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Listed On</td>
                                        <td>
                                            <?php if (isset($document->addedLists) && !empty($document->addedLists)) : ?>
                                                <?php foreach ($document->addedLists as $list) : ?>
                                                    <?php echo $list->ControlledName; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Tab Section</td>
                                        <td>
                                            <?php if (isset($document->selectedTabSections) && !empty($document->selectedTabSections)) : ?>
                                                <ul>
                                                    <?php foreach ($document->selectedTabSections as $tab_section) : ?>
                                                        <li><?php echo $tab_section->ControlledName; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                        <div class="results-details-tags-container">
                            <?php if (!empty($bottom_tag_list)) : ?>
                                <ul>
                                    <?php foreach ($bottom_tag_list as $tag): ?>
                                        <li>Listed on <a href="/critical-releases/?cr=1&id=<?php echo $tag->id; ?>"
                                                         class="link">
                                                <?php echo $tag->identifier ?></a> <?php echo $tag->lable ?>
                                            <?php if (is_mdt_user()): ?>
                                                <a href="javascript:void(0)"
                                                   data-docid="<?php echo $document->docID; ?>"
                                                   data-id="<?php echo $tag->id; ?>" class="delete-list">delete</a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="help-with-cite">
                            <a href="/faq/citations/" class="btn-help">Help with citations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bs-section--search-results-details-tags-section">

    </section>
    <section class="bs-section--search-results-details-popup-section">
        <!-- <button type="button" id="to-list-nav-item" class="btn btn-primary" data-toggle="modal" data-target="#addToList">
            Add to List
        </button>
        <button type="button" id="to-series-nav-item" class="btn btn-primary" data-toggle="modal" data-target="#addToSeries">
            Add to Series
        </button>
        <button type="button" id="fast-subj-nav-item" class="btn btn-primary" data-toggle="modal" data-target="#fastSubject">
        Fast Subjects
        </button> -->
        <div class="modal fade bd-example-modal-lg" id="addToList" tabindex="-1" role="dialog"
             aria-labelledby="addToListTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addToListLongTitle">Add to List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p id="to-list-loading">loading...</p>
                    <div id="to-list-popup-inner-wrapper">
                        <div class="popup-search">
                            <input type="text" placeholder="Search" class="search-field">
                            <button>
                                <div class="search-btn">
                                </div>
                            </button>
                        </div>
                        <div class="chkbox-list">
                            <p class="loading">loading...</p>
                            <div class="list-inner">
                            </div>
                        </div>
                        <div class="added-list">
                            <h4>Added Lists</h4>
                            <div class="added-details">
                            </div>
                        </div>
                        <p class="server-error-msg"></p>
                        <p class="submitting">Submitting...</p>
                        <div class="popup-buttons">
                            <button class="btn-submit btn-prussian-blue-bg">Submit</button>
                            <button class="btn-cancel btn-black-bg" data-toggle="modal" data-target="#addToList">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="addToSeries" tabindex="-1" role="dialog"
             aria-labelledby="addToSeriesTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addToSeriesLongTitle">Add to Series</h5>
                        <button type="button" id="to-series-modal-close" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-inner-body">
                        <div class="popup-search">
                            <input type="text" placeholder="Search" class="search-field">
                            <button>
                                <div class="search-btn">
                                </div>
                            </button>
                        </div>
                        <div class="chkbox-list">
                            <p class="loading">loading...</p>
                            <div class="list-inner">
                            </div>
                        </div>
                        <div class="added-list">
                            <h4>Added Series</h4>
                            <div class="added-details">

                            </div>
                        </div>
                        <p class="server-error-msg"></p>
                        <p class="submitting">Submitting...</p>
                        <div class="popup-buttons">
                            <button class="btn-submit btn-prussian-blue-bg">Add Document To Selected Series</button>
                            <button class="btn-cancel btn-black-bg" data-toggle="modal" data-target="#addToSeries">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="fastSubject" tabindex="-1" role="dialog"
             aria-labelledby="fastSubjectTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fastSubjectLongTitle">Fast Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div>
                        <div class="popup-search">
                            <input type="text" placeholder="Search" class="search-field">
                            <button>
                                <div class="search-btn">
                                </div>
                            </button>
                        </div>
                        <div class="chkbox-list">
                            <p class="loading">loading...</p>
                            <div class="list-inner">
                            </div>
                        </div>
                        <div class="added-list">
                            <h4>Fast Subjects</h4>
                            <div class="added-details">
                            </div>
                        </div>
                        <p class="submitting">Submitting...</p>
                        <p class="server-error-msg"></p>
                        <div class="popup-buttons">
                            <button class="btn-submit btn-prussian-blue-bg">Submit</button>
                            <button class="btn-cancel btn-black-bg" data-toggle="modal" data-target="#fastSubject">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>