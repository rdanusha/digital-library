<?php if (is_auth_user() && is_mdt_user()): ?>
    <section class="bs-section--secondary-nav-mdt <?php echo (isset($_SESSION['mdt']) && $_SESSION['mdt'] == 0)?'toggle-hide':''; ?>">
        <div class="container">
            <div class="nav-container">
                <ul>
                    <li>
                        <a class="nav-link" href="<?php echo home_url('/mdt/#/'); ?>">New Record</a>
                    </li>
                    <li>
                        <a class="nav-link" href="<?php echo home_url('/mdt/#/names'); ?>">Names</a>
                    </li>
                    <li>
                        <a class="nav-link" href="<?php echo home_url('/mdt/#/publisher'); ?>">Publishers</a>
                    </li>
                    <li>
                        <a class="nav-link" href="<?php echo home_url('/mdt/#/creator'); ?>">Creators</a>
                    </li>
                    <?php if (is_hsdl_admin_user()) : ?>
                        <li>
                            <a class="nav-link" href="<?php echo home_url('/mdt/#/bulk'); ?>">Bulk</a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?php echo home_url('/mdt/#/bulkinsert'); ?>">Bulk Insert</a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?php echo home_url('/mdt/#/merge'); ?>">Merge</a>
                        </li>
                        <?php endif; ?>
                </ul>
            </div>
        </div>
    </section>
<?php endif; ?>