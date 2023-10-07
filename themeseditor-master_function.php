<?php
!defined('EMLOG_ROOT') && exit('access deined!');
include(EMLOG_ROOT . '/content/plugins/themeseditor-master/themeseditor-master_config.php');
define('THEMESEDITOR_THEME_PATH', EMLOG_ROOT . '/content/templates/');
/**
 *获得所有主题
 **/
function getThemsList() {
    $themeseditor_theme_list = array();
    if (is_readable(THEMESEDITOR_THEME_PATH)) {
        $handle = opendir(THEMESEDITOR_THEME_PATH);
        if ($handle) {
            while (false !== ($filename = readdir($handle))) {
                if ($filename[0] == '.')
                    continue;
                $file = THEMESEDITOR_THEME_PATH . $filename;
                if (is_dir($file)) {
                    $themeseditor_theme_list[] = $filename;
                }
            }
            closedir($handle);
        }
    }
    return $themeseditor_theme_list;
}

/**
 *获得主题文件下的文件列表
 **/
function getThemFileList($themeName) {
    return themeseditor_plugin_read_file(THEMESEDITOR_THEME_PATH . $themeName);
}

/**
 *读取主题文件内容
 **/
function getThemFileContent($themeName, $fileName) {
    if (is_readable(THEMESEDITOR_THEME_PATH . $themeName . '/' . $fileName)) {
        $fso = fopen(THEMESEDITOR_THEME_PATH . $themeName . '/' . $fileName, 'r');
        $content = fread($fso, filesize(THEMESEDITOR_THEME_PATH . '/' . $themeName . '/' . $fileName));
        return themeseditor_esc_textarea($content);
    }
    return "";
}

/**
 *保存主题文件内容
 **/
function saveThemFileContent($themeName, $fileName, $content) {
    if (!empty($content)) {
        $fso = fopen(THEMESEDITOR_THEME_PATH . $themeName . '/' . $fileName, 'w');
        fwrite($fso, htmlspecialchars_decode($content));
        fclose($fso);
        return true;
    }
    return false;
}

function themeseditor_setting_config($themeName, $fileName, $codemirrorTheme) {
    $fso = fopen(EMLOG_ROOT . '/content/plugins/themeseditor-master/themeseditor-master_config.php', 'r');
    $config = fread($fso, filesize(EMLOG_ROOT . '/content/plugins/themeseditor-master/themeseditor-master_config.php'));
    fclose($fso);

    if (!empty($codemirrorTheme)) {
        $pattern = array("/define\('CODEMIRROR_THEME',(.*)\)/");
        $replace = array("define('CODEMIRROR_THEME','" . $codemirrorTheme . "')");
    } else {
        $pattern = array("/define\('THEMESEDITOR_CTHEME',(.*)\)/", "/define\('THEMESEDITOR_CFILE',(.*)\)/",);
        $replace = array("define('THEMESEDITOR_CTHEME','" . $themeName . "')", "define('THEMESEDITOR_CFILE','" . $fileName . "')",);
    }
    $new_config = preg_replace($pattern, $replace, $config);
    $fso = fopen(EMLOG_ROOT . '/content/plugins/themeseditor-master/themeseditor-master_config.php', 'w');
    fwrite($fso, $new_config);
    fclose($fso);
}


function getEditorMode($themeName, $fileName) {
    $shufit = pathinfo(THEMESEDITOR_THEME_PATH . $themeName . '/' . $fileName, PATHINFO_EXTENSION);
    $mode = "application/x-httpd-php";
    if ($shufit == "js") {
        $mode = "text/javascript";
    } elseif ($shufit == "css") {
        $mode = "text/css";
    }
    return $mode;
}

function themeseditor_plugin_read_file($path) {
    $theme_files = array();
    if (is_dir($path)) {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ($filename = readdir($handle))) {
                if ($filename[0] == '.' || $filename == "images") {
                    continue;
                }
                $file = $path . $filename;
                if (is_dir($file)) {
                    $dep = themeseditor_plugin_read_file($file . '/');
                    $theme_files = array_merge($theme_files, $dep);
                } else {
                    $a = explode('.', trim($file));
                    $file_ext = strtolower(array_pop($a));
                    if ($file_ext == "php" || $file_ext == "css" || $file_ext == "html" || $file_ext == "htm" || $file_ext == "js") {
                        $theme_files[] = $filename;
                    }
                }
            }
            closedir($handle);
        }
    }
    return $theme_files;
}

function themeseditor_esc_textarea($text) {
    $safe_text = htmlspecialchars($text, ENT_QUOTES);
    return $safe_text;
}