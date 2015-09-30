<div class="bg-light">
    <div class="bg-white">
        <ul class="nav nav-tabs nav-tabs-alt nav-md vpadder-lg">
            <li class="active">
                <a href="<?php echo mdh_emailmagick_url(); ?>">
                    <?php _e("Manage your emails", mdh_current_plugin_name()); ?>
                </a>
            </li>
            <li class="">
                <a href="http://market.osclass.org/user/profile/madhouse" target="_blank">
                    <span class="label label-primary">
                        <?php _e("See more plugins by Madhouse", mdh_current_plugin_name()); ?>
                    </span>
                </a>
            </li>
        </ul>
    </div>
    <div class="">
        <form action="<?php echo mdh_emailmagick_do_url(); ?>" method="post">
            <div class="vpadder-lg lt b-b">
                <div class="hpadder-md">
                    <h2 class="h3 row-space-2 text-info">
                        <?php _e("Template (HTML)", mdh_current_plugin_name()); ?>
                    </h2>
                    <div class="form-group">
                        <div class="form-controls">
                            <textarea name="email_template"><?php echo View::newInstance()->_get("template"); ?></textarea>
                        </div>
                    </div>
                    <div class="row-space-2">
                        <!-- <strong class="text-info"><?php _e("Tips!", mdh_current_plugin_name()); ?></strong> -->
                        <strong>
                            <?php _e("There's 3 special keywords :", mdh_current_plugin_name()); ?>
                        </strong>
                    </div>
                    <ul>
                        <li><code>{{{CONTENT}}}</code>&nbsp;<?php _e("is the where the actual content of each email will be inserted.", mdh_current_plugin_name()); ?></li>
                        <li><code>{{{FOOTER}}}</code>&nbsp;<?php _e("lets you put content shared by all your emails, usually links to your website or copyright mention. See footer section below.", mdh_current_plugin_name()); ?></li>
                        <li><code>{{{EXCERPT}}}</code>&nbsp;<?php _e("is the summary of your email, in a few words. It's usually put at the beginning of your template to be used by email clients as a preview (when not opened yet).", mdh_current_plugin_name()); ?></li>
                    </ul>
                </div>
            </div>
            <div class="vpadder-lg dk b-b">
                <?php
                    madhouse_render_mustache(
                        mdh_current_plugin_name() . "_edit_footer",
                        array(
                            "active_locales" => View::newInstance()->_get("active_locales"),
                            "datas" => View::newInstance()->_get("datas"),
                            "texts" => array(
                                "footer" => __("Footer", mdh_current_plugin_name()),
                                "footer_help" => __("The footer is a way to have shared content for all your emails : links, copyright mention, love message to your users, etc.", mdh_current_plugin_name())
                            )
                        )
                    );
                ?>
            </div>
            <div class="vpadder-lg">
                <div class="space-out-b-xl">
                    <div class="panel-group hpadder-lg">
                        <div class="space-out-b-xl">
                            <h2 class="h3 row-space-2 text-info">
                                <?php _e("Edit your emails", mdh_current_plugin_name()); ?>
                            </h2>
                            <p class="row-space-3 text-justify">
                                <?php _e("Edit all your emails in one place, in every language without opening too many tabs or repeating yourself and keeping the design (template) apart from the real content (below). Click on one of them to open the accordion and start working and don't forget to save your changes at the bottom of the page when done.", mdh_current_plugin_name()); ?>
                                <strong><?php _e("By default", mdh_current_plugin_name()); ?>,</strong>&nbsp;<?php _e("the title and content below are the default title and content of emails (when you get Osclass or a new language installed) and not the actual emails you have.", mdh_current_plugin_name()); ?>
                            </p>
                            <p class="row-space-0 text-justify">
                                <label class="label label-danger space-out-r-xs"><?php _e("Important", mdh_current_plugin_name()); ?></label>
                                <strong class="text-danger"><?php _e("Saving your changes will <u>overwrite each and every email</u> with the combination of the template and the content you gave (title and content) unless title or content is empty. You might want to make a backup of your database the first time if you have already have worked on your emails via the default Osclass interface.", mdh_current_plugin_name()); ?></strong>
                            </p>
                        </div>
                    <?php
                        $datas = View::newInstance()->_get("datas");
                        foreach($datas["emails"] as $data) {
                            if($data["i_filled"] == 100) {
                                $data["s_label_filled"] = "label label-success";
                            } else if($data["i_filled"] == 0) {
                                $data["s_label_filled"] = "label label-danger";
                            } else {
                                $data["s_label_filled"] = "label label-warning";
                            }

                            madhouse_render_mustache(
                                mdh_current_plugin_name() . "_edit_email",
                                array(
                                    "datas" => $data,
                                    "active_locales" => View::newInstance()->_get("active_locales"),
                                    "edit_url" => osc_admin_base_url(true) . '?page=emails&action=edit&id=',
                                    "texts" => array(
                                        "title" => __("Title", mdh_current_plugin_name()),
                                        "excerpt" => __("Excerpt", mdh_current_plugin_name()),
                                        "excerpt_help" => __("Use the {{{EXCERPT}}} keyword at the top of your template to get it displayed as the excerpt in mail clients.", mdh_current_plugin_name()),
                                        "content" => __("Content", mdh_current_plugin_name()),
                                        "done" => __("done", mdh_current_plugin_name()),
                                        "delete" => __("Delete", mdh_current_plugin_name()),
                                        "confirm_delete" => __("Are you sure you want to delete this ?", mdh_current_plugin_name()),
                                        "email_link" => __("Link to the email", mdh_current_plugin_name()),
                                        "yes" => __("Yes", mdh_current_plugin_name()),
                                        "no" => __("No", mdh_current_plugin_name()),
                                    )
                                )
                            );
                        }
                    ?>
                </div>
                <div class="space-out-b-xl">
                    <p class="row-space-0 text-justify">
                        <label class="label label-danger space-out-r-xs"><?php _e("Important", mdh_current_plugin_name()); ?></label>
                        <strong class="text-danger"><?php _e("Saving your changes will <u>overwrite each and every email</u> with the combination of the template and the content you gave (title and content) unless title or content is empty. You might want to make a backup of your database the first time if you have already have worked on your emails via the default Osclass interface.", mdh_current_plugin_name()); ?></strong>
                    </p>
                </div>
                <input type="submit" id="save_changes" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-primary btn-block" />
            </div>
        </form>
    </div>
</div>