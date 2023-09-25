<div class="filter-accordion">
    <div class="collections-filter-div">
        <?php if (!empty($facets_array)) : ?>
            <?php $counter = 0; ?>
            <?php foreach ($facets_array as $facet => $facet_items) : ?>
                <div class="card">
                    <div>
                        <div class="card-body">
                            <?php if (count($facet_items) > 0) : ?>
                                <?php $item_counter = 0;
                                $row_class = strtolower(str_replace(' ', '-', $facet_mapping[$facet]));

                                $row_class .= '-colfocus';
                                if ($col_focus == 'Collections') {
                                    $selected_value = get_query_var('tabsection') ?: '';
                                }
                                if ($col_focus == 'In Focus') {
                                    $selected_value = get_query_var('lists') ?: '';
                                }
                                $search_url = home_url("/search-result/?type=all&searchterm=&search=1&start=0&rows=30&sort=date&col-focus=$col_focus");
                                $unchecked_url = home_url("/search-result/?type=all&searchterm=&search=1&start=0&rows=30&sort=date&col-focus=$col_focus");

                                ?>
                                <?php foreach ($facet_items as $item) : ?>
                                    <?php
                                    if ($col_focus == 'Collections') {
                                        $search_url .= "&tabsection=$item->type";
                                    }
                                    if ($col_focus == 'In Focus') {
                                        $search_url .= "&lists=$item->type";
                                    }
                                    ?>
                                    <div class="row data-row <?php echo $row_class; ?>" <?php echo ($item_counter > 8) ? 'style="display: none"' : ''; ?>>
                                        <div class="checkbox-div">
                                            <input
                                                <?php echo ($selected_value == $item->type) ? 'checked' : ''; ?>
                                                    class="facet-filter-category-checkbox checkbox-<?php echo $row_class; ?>"
                                                    name="<?php echo $row_class . '[]'; ?>"
                                                    type="checkbox"
                                                    id="<?php echo $row_class . '_' . $item_counter; ?>"
                                                    value="<?php echo $item->type ?>"
                                                    data-type="<?php echo $facet; ?>"
                                                    data-url="<?php echo $search_url; ?>"
                                                    data-unchecked-url="<?php echo $unchecked_url; ?>"
                                            />
                                            <label for="<?php echo $row_class . '_' . $item_counter; ?>"><?php echo $item->type ?></label>
                                        </div>
                                        <div class="counter-div">
                                            <h6><?php echo $item->value ?></h6>
                                        </div>
                                    </div>
                                    <?php $item_counter++; ?>
                                <?php endforeach; ?>
                                <?php if ($item_counter > 9) : ?>
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
<!-- left -->