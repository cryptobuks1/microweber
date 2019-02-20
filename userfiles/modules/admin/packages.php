<?php only_admin_access(); ?>
<script>

    mw.require('admin_package_manager.js');


</script>

<?php
$is_update_mode = false;

$search_packages_params = array();
//$search_packages_params['cache'] = true;


if (isset($params['show_only_updates']) and $params['show_only_updates']) {
    $search_packages_params['return_local_packages'] = true;
    $search_packages_params['return_only_updates'] = true;
    $is_update_mode = true;

}

$search_packages = mw()->update->composer_search_packages($search_packages_params);
//$search_packages = mw()->update->composer_search_packages();


$packages_by_type = array();


if ($search_packages and is_array($search_packages)) {
    foreach ($search_packages as $key => $item) {
        if (!isset($packages_by_type[$item['type']])) {
            $packages_by_type[$item['type']] = array();
        }
        $packages_by_type[$item['type']][]  = $item;
    }


}


//dd($packages_by_type);
?>


<script>


</script>


<div class="section-header">

    <?php if ($is_update_mode){ ?>
        <h2 class="pull-left"><span class="mw-icon-updates"></span> <?php _e("Updates"); ?></h2>

    <?php }else{ ?>
        <h2 class="pull-left"><span class="mw-icon-updates"></span> <?php _e("Packages"); ?></h2>
    <?php } ?>





    <div class="pull-right">
        <div class="top-search">
            <input value="" name="module_keyword" placeholder="Search" type="text" autocomplete="off"
                   onkeyup="event.keyCode==13?mw.url.windowHashParam('search',this.value):false">
            <span class="top-form-submit" onclick="mw.url.windowHashParam('search',$(this).prev().val())"><span
                    class="mw-icon-search"></span></span>
        </div>
    </div>

    <div class="pull-right m-r-10" style="margin-top:1px;">


        <ul class="mw-ui-navigation mw-ui-navigation-horizontal">

            <li>
                <a href="javascript:;"><span class="mw-icon-gear"></span> <span class="mw-icon-dropdown"></span></a>
                <ul>
                    <li><a href="javascript:;" onclick="mw.admin.admin_package_manager.reload_packages_list();">Reload
                            packages</a></li>
                    <li><a href="javascript:;"
                           onclick="mw.admin.admin_package_manager.show_licenses_modal ();">Licenses</a></li>


                </ul>
            </li>

        </ul>


    </div>
</div>


<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-packages-browser-nav-tabs-nav .mw-ui-navigation a',
            tabs: '#mw-packages-browser-nav-tabs-nav .tab'
        });
    });
</script>

<div class="admin-side-content" style="max-width: 90%">
    <div id="mw-packages-browser-nav-tabs-nav" class="mw-ui-row">
        <div class="mw-ui-col" style="width: 20%;">
            <div class="mw-ui-col-container">
                <ul class="mw-ui-box mw-ui-navigation" id="nav">
                    <?php if (isset($packages_by_type['microweber-template'])): ?>
                        <li><a href="javascript:;" class="active">Templates</a></li>
                    <?php endif; ?>

                    <?php if (isset($packages_by_type['microweber-module'])): ?>
                        <li><a href="javascript:;">Modules</a></li>
                    <?php endif; ?>

                    <?php if (isset($packages_by_type['microweber-core-update'])): ?>
                        <li><a href="javascript:;">Others</a></li>
                    <?php endif; ?>




                </ul>
            </div>
        </div>

        <div class="mw-ui-col" style="width: 80%;">
            <div class="mw-ui-col-container">

                <div id="mw-updates-queue"></div>


                <div class="mw-ui-box" style="width: 100%; padding: 12px 0;">
                    <?php if ($packages_by_type) : ?>

                        <?php foreach ($packages_by_type as $pkkey => $pkitems): ?>

                            <div class="mw-ui-box-content tab">

                            <?php if ($pkitems) : ?>
                                <div class="mw-flex-row">
                                    <?php foreach ($pkitems as $key => $item): ?>
                                             <div class="mw-flex-col-xs-12  m-b-20">

                                                <?php

                                                $view_file = __DIR__ . '/developer_tools/package_manager/partials/package_item.php';

                                                $view = new \Microweber\View($view_file);
                                                $view->assign('item', $item);

                                                print    $view->display();

                                                ?>


                                            </div>
                                     <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            </div>


                        <?php endforeach; ?>




                    <?php endif; ?>





                </div>
            </div>
        </div>
    </div>
</div>
