<?php
/*
  Plugin Name: 主题编辑插件
  Version: 0.1.1
  Plugin URL: https://www.emlog.net/plugin/detail/474
  Description: 可以在线直接编辑你的主题文件。
  Author: emlog
  Author URL:  https://www.emlog.net/author/index/577
 */

!defined('EMLOG_ROOT') && exit('access deined!');

//写入插件导航
function themeseditor() {
    $pluginName = isset($_GET['plugin']) ? addslashes(trim($_GET['plugin'])) : '';
    $isActive = $pluginName == 'themeseditor';
    if ($isActive) {
        echo '<script>$("#menu_ext").addClass("show");</script>';
    }
    echo '<a class="collapse-item ' . ($isActive ? 'active' : '') . '" href="./plugin.php?plugin=themeseditor">主题编辑</a>';
}

addAction('adm_menu_ext', 'themeseditor');
