<?php
$col_focus = get_query_var('col-focus') ?: '';
if ($col_focus) {
    $facet_title = $col_focus;
    //Hide In Focus On Collection filter results
    if ($col_focus == 'Collections') {
        $facet_mapping['tabsection'] = 'Collections';
        unset($facets_array->lists);
        unset($facets_array->tabsection);
    }
    //Hide Collection on In Focus filter results
    if ($col_focus == 'In Focus') {
        unset($facets_array->lists);
        unset($facets_array->tabsection);
    }
}

?>
<div class="col-12 col-md-4 col-xl-3 left">
    <h3><?php echo $facet_title; ?></h3>
    <?php
    if ($col_focus) {
        include_once('hsdl-mdt-search-public-search-collection-infocus-filters.php');
    }
    ?>
    <form>
        <label>Sort by</label>
        <select class="select-field" name="sort-field" id="sort-field">
            <option value="relevance" <?php echo (isset($sort) && $sort == 'relevance') ? 'selected' : ''; ?>>Relevance
            </option>
            <option value="date" <?php echo (isset($sort) && $sort == 'date') ? 'selected' : ''; ?>>Date</option>
        </select>
    </form>
    <div class="filter-accordion">
        <div id="accordion">
            <?php if (!empty($facets_array)) : ?>
                <?php $counter = 0; ?>
                <?php foreach ($facets_array as $facet => $facet_items) : ?>
                    <div class="card">
                        <div class="card-header" id="heading-<?php echo $counter ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link according-toggle-custom"
                                        data-id="collapse-<?php echo $counter ?>">
                                    <?php echo $facet_mapping[$facet]; ?>
                                </button>
                            </h5>
                        </div>
                        <?php
                        $selected_value = get_query_var($facet) ?: '';
                        $is_select = '';
                        if (count($facet_items) > 0) {
                            $is_select = array_search($selected_value, array_column(json_decode(json_encode($facet_items), TRUE), 'type'));
                        }
                        ?>
                        <div id="collapse-<?php echo $counter ?>"
                             class="collapse <?php echo (isset($is_select) && is_numeric($is_select)) ? 'show' : ''; ?>">
                            <div class="card-body">
                                <?php if (count($facet_items) > 0) : ?>
                                    <?php $item_counter = 0;
                                    $row_class = strtolower(str_replace(' ', '-', $facet_mapping[$facet]));
                                    ?>
                                    <?php foreach ($facet_items as $item) : ?>
                                        <div class="row data-row <?php echo $row_class; ?>" <?php echo ($item_counter > 5) ? 'style="display: none"' : ''; ?>>
                                            <div class="checkbox-div">
                                                <input
                                                    <?php echo ($selected_value == $item->type) ? 'checked' : ''; ?>
                                                        class="facet-filter-checkbox checkbox-<?php echo $row_class; ?>"
                                                        name="<?php echo $row_class . '[]'; ?>"
                                                        type="checkbox"
                                                        id="<?php echo $row_class . '_' . $item_counter; ?>"
                                                        value="<?php echo $item->type ?>"
                                                        data-type="<?php echo $facet; ?>"
                                                />
                                                <label for="<?php echo $row_class . '_' . $item_counter; ?>"><?php echo $item->type ?></label>
                                            </div>
                                            <div class="counter-div">
                                                <h6><?php echo $item->value ?></h6>
                                            </div>
                                        </div>
                                        <?php $item_counter++; ?>
                                    <?php endforeach; ?>
                                    <?php if ($item_counter > 6) : ?>
                                        <div class="row show-more " data-type="<?php echo $row_class; ?>">
                                            show more
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php $counter++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div><!-- left -->