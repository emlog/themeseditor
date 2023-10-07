<?php
!defined('EMLOG_ROOT') && exit('access deined!');
?>
<script type="text/javascript">
    $("#themeseditor").addClass('active-page');
    $("#menu_mg").addClass('active');
    setTimeout(hideActived, 2600);
</script>
<link rel="stylesheet" href="../content/plugins/themeseditor-master/CodeMirror/codemirror.css">
<link rel="stylesheet" href="../content/plugins/themeseditor-master/CodeMirror/theme/<?php echo CODEMIRROR_THEME; ?>.css" id="mirrTheme">
<script src="../content/plugins/themeseditor-master/CodeMirror/codemirror.js"></script>
<script src="../content/plugins/themeseditor-master/CodeMirror/util.js"></script>

<script src="../content/plugins/themeseditor-master/CodeMirror/mode.js"></script>
<?php if (isset($_GET['setting'])): ?>
    <div class="actived alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        插件设置完成
    </div>
<?php endif; ?>
<div class="form-group text-center saveStatus" style="color:red">
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default card-view">
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label mb-10">请选择模板</label>
                    <select onchange="changeSelectTheme(this)" id="themeName" class="form-control select2 select2-hidden-accessible">
                        <?php
                        foreach ($themeseditor_theme_list as $theme) {
                            if ($theme == $themeName) {
                                echo "<option selected='selected'>$theme</option>";
                            } else {
                                echo "<option>$theme</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label mb-10">请选择要编辑的文件</label>
                    <select onchange="changeSelectThemeFile(this)" id="themeNameFile" class="form-control select2 select2-hidden-accessible">
                        <?php
                        foreach ($themeseditor_theme_files as $file) {
                            if ($file == $themeFileName) {
                                echo "<option selected='selected'>$file</option>";
                            } else {
                                echo "<option>$file</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default card-view">
            <div class="panel-body">
<textarea name="newcontent" id="newcontent" tabindex="1" style="display:none" class="form-control"><?php echo $themeseditor_theme_content; ?>
</textarea>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default card-view">
            <div class="panel-body">
                <input type="hidden" name="fileP" value="<?php echo $themeseditor_currentFile ?>">
                <input type="button" class="btn  btn-success" value="更新文件" onClick="saveFileContent()"/>
                <select onchange="selectTheme()" class="form-control" id="select" style="width:130px;float: right;">
                    <?php
                    foreach (explode(",", THEMESEDITOR_EDITOR_THEMES) as $name) {
                        if ($name == CODEMIRROR_THEME) {
                            echo "<option selected='selected'>$name</option>";
                        } else {
                            echo "<option>$name</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default card-view">
            <div class="panel-body">
                快捷键<br/>
                <b>保存更新</b>(Ctrl-S / Cmd-S) <br/><b>全屏编辑</b>(F11) <br/><b>退出全屏</b>(Esc) <br/><b>搜索</b>(Ctrl-F / Cmd-F) <br/><b>查找下一个</b>(Ctrl-G / Cmd-G) <br/><b>查找上一个</b>(Shift-Ctrl-G / Shift-Cmd-G) <br/><b>替换</b>(Shift-Ctrl-F / Cmd-Option-F)<br/><b>替换所有</b>(Shift-Ctrl-R / Shift-Cmd-Option-F)
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .CodeMirror-fullscreen {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
    }
</style>
<script>
    function isFullScreen(cm) {
        return /\bCodeMirror-fullscreen\b/.test(cm.getWrapperElement().className);
    }

    function winHeight() {
        return window.innerHeight || (document.documentElement || document.body).clientHeight;
    }

    function setFullScreen(cm, full) {
        var wrap = cm.getWrapperElement();
        if (full) {
            wrap.style.width = "100%";
            $(wrap).addClass("CodeMirror-fullscreen")
                .height(winHeight() + "px");
            document.documentElement.style.overflow = "hidden";
        } else {
            $(wrap).removeClass("CodeMirror-fullscreen");
            wrap.style.height = "";
            document.documentElement.style.overflow = "";
        }
        cm.refresh();
    }

    CodeMirror.on(window, "resize", function () {
        var showing = document.body.getElementsByClassName("CodeMirror-fullscreen")[0];
        if (!showing) return;
        showing.CodeMirror.getWrapperElement().style.height = winHeight() + "px";
    });
    var CodeMirrorEditor = CodeMirror.fromTextArea(document.getElementById("newcontent"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "<?php echo $mode;?>",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        theme: "<?php echo CODEMIRROR_THEME;?>",
        extraKeys: {
            "F11": function (cm) {
                setFullScreen(cm, !isFullScreen(cm));
            },
            "Esc": function (cm) {
                if (isFullScreen(cm)) setFullScreen(cm, false);
            },
            "Ctrl-S": saveFileContent
        }
    });
    CodeMirrorEditor.setSize(null, 500);
    var style = $("<style></style>").appendTo("head");

    function selectTheme() {
        $(".saveStatus").text("主题加载ing....").fadeIn();
        var theme = $("#select").val();
        $.ajax({
            url: "../content/plugins/themeseditor-master/CodeMirror/theme/" + theme + ".css",
            dataType: "text",
            success: function (data) {
                style.html(data);
                CodeMirrorEditor.setOption("theme", theme);
                $(".saveStatus").text("主题加载成功").delay(2000).fadeOut();
            }
        });
        $.post("../content/plugins/themeseditor-master/themeseditor-master_controler.php", {action: "saveEditorThemes", name: theme});
    }

    function changeSelectTheme(target) {
        window.location.replace('./plugin.php?plugin=themeseditor-master&themeName=' + $(target).val());
    }

    function changeSelectThemeFile(target) {
        window.location.replace('./plugin.php?plugin=themeseditor-master&themeName=' + $("#themeName").val() + "&themeFileName=" + $(target).val());
    }

    var saving = false;

    function saveFileContent() {
        if (!saving) {
            saving = true;
            $(".saveStatus").text("更新ing....").fadeIn();
            $.post("../content/plugins/themeseditor-master/themeseditor-master_controler.php", {action: "save", themeName: $("#themeName").val(), fileName: $("#themeNameFile").val(), content: CodeMirrorEditor.getValue()}, function (rsp) {
                if (rsp.status) {
                    $(".saveStatus").text("更新成功！").delay(2000).fadeOut();
                } else {
                    $(".saveStatus").text("更新失败！").delay(2000).fadeOut();
                }
                saving = false;
            }, "JSON").error(function () {
                saving = false;
            });
        }
    }
</script>
