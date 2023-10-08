<?php
!defined('EMLOG_ROOT') && exit('access deined!');
require_once 'themeseditor_function.php';

function plugin_setting_view() {
    if (!is_writable(EMLOG_ROOT . '/content/templates')) {
        emMsg('主题文件不可写');
    }
    $themeName = isset($_GET['themeName']) ? $_GET['themeName'] : THEMESEDITOR_CTHEME;
    $themeseditor_theme_list = getThemsList();
    $themeseditor_theme_files = getThemFileList($themeName);

    if (isset($_GET['themeFileName']) && isset($_GET['themeName'])) {
        $themeFileName = $_GET['themeFileName'];
    } elseif (isset($_GET['themeName'])) {
        $themeFileName = $themeseditor_theme_files[0];
    } else {
        $themeFileName = THEMESEDITOR_CFILE;
    }

    if (isset($_GET['themeFileName'])) {
        themeseditor_setting_config($themeName, $themeFileName, null);
    }

    $themeseditor_theme_content = getThemFileContent($themeName, $themeFileName);
    $mode = getEditorMode($themeName, $themeFileName);

    include(EMLOG_ROOT . '/content/plugins/themeseditor/themeseditor_setting_view.php');
}

function plugin_setting() {

}