<input type="hidden" name="search" value="1">
<input type="hidden" name="start" id="start" value="<?php echo $start; ?>">
<input type="hidden" name="rows" id="rows" value="<?php echo $rows; ?>">
<input type="hidden" name="sort" id="sort" value="<?php echo $sort; ?>">
<input type="hidden" name="col-focus" id="col-focus" value="<?php echo $col_focus; ?>">
<input type="hidden" name="restricted" id="restricted" value="<?php echo $is_restricted_collections ?>">
<input type="hidden" name="page-name" id="page-name" value="<?php echo ($is_restricted_collections=='true')?'restricted':'search' ?>">

<?php
$advanced = get_query_var('advanced') ?: 'false';

?>
<?php foreach ($facet_values as $val) : ?>
    <?php $set_value = get_query_var($val) ?: ''; ?>
    <input <?php echo (empty($set_value)) ? 'disabled' : ''; ?>
            type="hidden" name="<?php echo $val; ?>" id="<?php echo $val; ?>"
            value="<?php echo $set_value; ?>">
<?php endforeach; ?>