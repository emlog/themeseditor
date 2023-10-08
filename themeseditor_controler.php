<?php
require_once '../../../init.php';
$result = array();
if (ISLOGIN === false) {
    $result["status"] = false;
    $result["msg"] = "没有登陆！";
    echo json_encode($result);
    exit;
}

require_once 'themeseditor_function.php';

$action = Input::postStrVar('action');

if (empty($action)) {
    $result["status"] = false;
    $result["msg"] = "操作类型错误！";
} else {
    if ($action == 'save') {
        $themeName = $_POST['themeName'];
        $fileName = $_POST['fileName'];
        $content = $_POST['content'];
        $status = saveThemFileContent($themeName, $fileName, $content);
        if ($status) {
            $result["status"] = true;
        } else {
            $result["status"] = false;
            $result["msg"] = "保存失败！";
        }
    } elseif ($action == 'themesFileList') {

    } elseif ($action == 'saveEditorThemes') {
        $name = isset($_POST['name']) ? addslashes($_POST['name']) : '';
        if (!empty($name)) {
            themeseditor_setting_config(null, null, $name);
        }
    }
}

echo json_encode($result);
exit;