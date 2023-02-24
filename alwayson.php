<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>ISL AlwaysOn Generator</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    </head>
    <body style="border: 40px solid white;">
        <h2>ISL AlwaysOn Generator</h2>
        <p></p>
        <p>See: <a href="https://help.islonline.com/19824/165972">ISL Documentation</a>, <a href="https://help.islonline.com/19818/1038945">Silent Setup</a></p>
        <p></p>
        <?php

//
// 02/2023 Matthias Henze
//

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//echo var_dump($_GET);

if ( empty($_GET['download_type']) ) {
    $download_type = "start";
}
else {
    $download_type = $_GET['download_type'];
}

if ( !empty( $_GET['base_url']) ) {

    $base_url = $_GET['base_url'];

    if ( str_contains($base_url,'start')) {
        $base_url = str_replace('/start/','/'.$download_type.'/',$base_url);
    }
    elseif ( str_contains($base_url,'download')) {
        $base_url = str_replace('/download/','/'.$download_type.'/',$base_url);
    }

    if ( !empty($_GET['grant_mode']) ) {
        switch ($_GET['grant_mode']) {
            case 'grant_silent' :
                $url = str_replace('=grant+','=grant_silent+',$base_url);
                break;
            default:
                $url = $base_url;
                break;
        }
    }
    else {
        exit;
    }

    $u = explode("=", $url);

    $options = "";
    foreach ($_GET as $key => $value) {
        switch ($key) {
            case 'install_mode' :
                switch ($value) {
                    case 'VERYSILENT' :
                        $options .= '/VERYSILENT ';
                        break;
                    case 'SILENT' :
                        $options .= '/SILENT ';
                        break;
                }
                break;
            case 'grant_password' :
                if (strlen($value) > 0) {
                    $options .= 'grant_password "' . $value . '" ';
                }
                break;
            case 'password' :
                if (strlen($value) > 0) {
                    $options .= 'password "' . $value . '" ';
                }
                break;
            case 'record_path' :
                if (strlen($value) > 0) {
                    $options .= 'record_path "' . $value . '" ';
                }
                break;
            case 'share_path' :
                if (strlen($value) > 0) {
                    $options .= 'share_path "' . $value . '" ';
                }
                break;
            case 'description' :
                if (strlen($value) > 0) {
                    $options .= 'description "' . $value . '" ';
                }
                break;
            case 'push_upgrade' :
                switch ($value) {
                    case '1' :
                        $options .= 'push_upgrade true ';
                        break;
                    default:
                        $options .= 'push_upgrade false ';
                    break;
                }
                break;
            case 'shutdown_mode' :
                switch ($value) {
                    case 'shutdown' :
                        $options .= 'shutdown ';
                        break;
                    case 'shutdown_silent' :
                        $options .= 'shutdown_silent ';
                        break;
                }
                break;
            case 'skip_check_start' :
                switch ($value) {
                    case '1' :
                        $options .= 'skip_check_start ';
                        break;
                }
                break;
            case 'ignore_system_account' :
                switch ($value) {
                    case '1' :
                        $options .= 'ignore_system_account ';
                        break;
                }
                break;
            default :
                break;
        }
    }

    $result = $u[0] . "=" . urlencode(trim($options)) . '+' . $u[1];

    echo '<b>Generated URL:</b><br><br><a href="' . $result . '">' . $result . '</a>';
    echo "<p></<p>";
    echo "<p><a href='alwayson.php'>Reset Form</a></<p>";
    echo "<p></<p>";
}
?>
        <form action="alwayson.php">
            <div class="rendered-form">
                <div class="formbuilder-textarea form-group field-grant_blob">
                    <label for="base_url" class="formbuilder-textarea-label"><b>Base URL<span class="formbuilder-required">*</span></b><br>
                </label>
                    <br><textarea type="textarea" name="base_url" access="false" id="base_url" required="required" aria-required="true" cols="40" rows="5"><?php echo $_GET['base_url']; ?></textarea>
                </div>
                <p></p>
                <div class="formbuilder-radio-group form-group field-download_type">
                    <label for="download_type" class="formbuilder-radio-group-label"><b>Type of binary<span class="formbuilder-required">*</span></b>
                        <br>"download" creates a complete binary for silent deployment. "start" creates a minimal binary. </label>
                    <div class="radio-group">
                        <div class="formbuilder-radio">
                            <input name="download_type" access="false" id="download_type-0" value="download" type="radio" <?php if ($download_type == 'download') { echo 'checked="checked"'; }?>>
                            <label for="download_type-0">download</label>
                        </div>
                        <div class="formbuilder-radio">
                            <input name="download_type" access="false" id="download_type-1" value="start" type="radio" <?php if ($download_type == 'start') { echo 'checked="checked"'; }?>>
                            <label for="install_mode-1">start</label>
                        </div>
                    </div>
                </div>
                <p></p>
                <div class="formbuilder-radio-group form-group field-grant_mode">
                    <label for="grant_mode" class="formbuilder-radio-group-label"><b>Grant<span class="formbuilder-required">*</span></label></b>
                        <br>Link that is used by the default ISL AlwaysOn web page used command line option grant that is followed by grant blob. If silent install is needed (no user confirmation), grant_silent can be used instead.
                    <div class="radio-group">
                        <div class="formbuilder-radio">
                            <input name="grant_mode" access="false" id="grant_mode-0" required="required" aria-required="true" value="grant" type="radio" <?php if ( empty($_GET['grant_mode']) || $_GET['grant_mode'] == 'grant') { echo 'checked="checked"'; }?>>
                            <label for="grant_mode-0">grant</label>
                        </div>
                        <div class="formbuilder-radio">
                            <input name="grant_mode" access="false" id="grant_mode-1" required="required" aria-required="true" value="grant_silent" type="radio" <?php if ($_GET['grant_mode'] == 'grant_silent') { echo 'checked="checked"'; }?>>
                            <label for="grant_mode-1">grant silent</label>
                        </div>
                    </div>
                </div>
                <p></p>

                <div class="formbuilder-radio-group form-group field-install_mode">
                    <label for="install_mode" class="formbuilder-radio-group-label"><b>Silent Install</b>
                        <br>Command line option /SILENT instructs the installer not to display the wizard and the background window. If you use /VERYSILENT, the installation progress window will not be displayed either. The setup will not be started when ISL AlwaysOn is already installed on that computer.</label>
                    <div class="radio-group">
                        <div class="formbuilder-radio">
                            <input name="install_mode" access="false" id="install_mode-0" value="SILENT" type="radio" <?php if ($_GET['install_mode'] == 'SILENT') { echo 'checked="checked"'; }?>>
                            <label for="install_mode-0">SILENT</label>
                        </div>
                        <div class="formbuilder-radio">
                            <input name="install_mode" access="false" id="install_mode-1" value="VERYSILENT" type="radio" <?php if ($_GET['install_mode'] == 'VERYSILENT') { echo 'checked="checked"'; }?>>
                            <label for="install_mode-1">VERYSILENT</label>
                        </div>
                    </div>
                </div>
                <p></p>
                <div class="formbuilder-text form-group field-grant_password">
                    <label for="grant_password" class="formbuilder-text-label"><b>Grant Password</b>
                        <br>Option grant_password defines a new granted connection password. This option is used together with the option grant_silent.
                        <br>
                    </label>
                    <input type="text" name="grant_password" access="false" id="grant_password" value="<?php echo $_GET['grant_password']; ?>">
                </div>
                <p></p>
                <div class="formbuilder-text form-group field-password">
                    <label for="password" class="formbuilder-text-label"><b>Password</b>
                        <br>Option password will set access password if the password hasn't already been set.
                        <br>
                    </label>
                    <input type="text" class="form-control" name="password" access="false" id="password" value="<?php echo $_GET['password']; ?>">
                </div>
                <p></p>
                <div class="formbuilder-text form-group field-record_path">
                    <label for="record_path" class="formbuilder-text-label"><b>Record Path</b>
                        <br>Option record is used to set the session recordings folder. When an ISL Light session is started and session recording folder is set, the session will be recorded to the specified folder.
                        <br>
                    </label>
                    <input type="text" name="record_path" access="false" id="record_path" value="<?php echo $_GET['record_path']; ?>">
                </div>
                <p></p>
                <div class="formbuilder-text form-group field-share_path">
                    <label for="share_path" class="formbuilder-text-label"><b>Share Path</b>
                        <br>Option share is used to add a shared folder - during installation, a dialog for adding a new file share will pop up, with the provided location already entered, allowing you to set the authentication and access parameters, then confirm it by clicking OK.
                        <br>
                        <br> Example: share "C:\myshare"
                        <br>
                        <br>Link that is used by the default ISL AlwaysOn web page used command line option grant that is followed by grant blob. If silent install is needed (no user confirmation), grant_silent can be used instead.
                        <br>
                    </label>
                    <input type="text" name="share_path" access="false" id="share_path" value="<?php echo $_GET['share_path']; ?>">
                </div>
                <p></p>
                <div class="formbuilder-text form-group field-description">
                    <label for="description" class="formbuilder-text-label"><b>Description</b><br>Command line option description is followed by the description text that will be shown in the ISL AlwaysOn page. Description text can also specify environment variables that are available to ISL AlwaysOn process and some additional variables.
                        <br>
                    </label>
                    <input type="text" name="description" access="false" id="description" value="<?php echo $_GET['description']; ?>">
                </div>
                <p></p>
                <div class="formbuilder-checkbox-group form-group field-push_upgrade">
                    <label for="push_upgrade" class="formbuilder-checkbox-group-label"><b>Push Updgrade</b>
                        <br>Adding option push_upgrade will enable or disable automatic software updates initiated from the ISL Conference Proxy server - this options is useful for corporate environments where administrator wants to update multiple computers at the same time.
                        <br>
                    </label>
                    <div class="checkbox-group">
                        <div class="formbuilder-checkbox">
                            <input name="push_upgrade" access="false" id="push_upgrade-0" value="1" type="checkbox" <?php if ($_GET['push_upgrade'] == '1') { echo 'checked="checked"'; }?>>
                            <label for="push_upgrade-0">do push upgrade</label>
                        </div>
                    </div>
                </div>
                <p></p>
                <div class="formbuilder-radio-group form-group field-shutdown_mode">
                    <label for="shutdown_mode" class="formbuilder-radio-group-label"><b>Shtudown</b>
                        <br>Option shutdown stops all ISL AlwaysOn services on local computer. If no user confirmation is needed, use shutdown_silent.</label>
                    <div class="radio-group">
                        <div class="formbuilder-radio">
                            <input name="shutdown_mode" access="false" id="shutdown_mode-0" value="shutdown" type="radio" <?php if ($_GET['shutdown_mode'] == 'shutdown') { echo 'checked="checked"'; }?>>
                            <label for="shutdown_mode-0">shutdown</label>
                        </div>
                        <div class="formbuilder-radio">
                            <input name="shutdown_mode" access="false" id="shutdown_mode-1" value="shutdown_silent" type="radio" <?php if ($_GET['shutdown_mode'] == 'shutdown_silent') { echo 'checked="checked"'; }?>>
                            <label for="shutdown_mode-1">shutdown silent</label>
                        </div>
                    </div>
                </div>
            
                <p></p>
                <div class="formbuilder-checkbox-group form-group field-skip_check_start">
                    <label for="skip_check_start" class="formbuilder-checkbox-group-label"><b>Skip Check Start</b><br>Option skip_check_start will always upgrade the ISL AlwaysOn program first and then perform actions that are specified with parameters.
                        <br>
                    </label>
                    <div class="checkbox-group">
                        <div class="formbuilder-checkbox">
                            <input name="skip_check_start" access="false" id="skip_check_start-0" value="1" type="checkbox" <?php if ($_GET['skip_check_start'] == '1') { echo 'checked="checked"'; }?>>
                            <label for="skip_check_start-0">skip start check</label>
                        </div>
                    </div>
                </div>
                <p></p>
                <div class="formbuilder-checkbox-group form-group field-ignore_system_account">
                    <label for="ignore_system_account" class="formbuilder-checkbox-group-label"><b>Ignore Sytem Account</b><br>Required for MSI like silent setups.
                        <br>
                    </label>
                    <div class="checkbox-group">
                        <div class="formbuilder-checkbox">
                            <input name="ignore_system_account" access="false" id="ignore_system_account-0" value="1" type="checkbox" <?php if ($_GET['ignore_system_account'] == '1') { echo 'checked="checked"'; }?>>
                            <label for="ignore_system_account-0">ignore system account</label>
                        </div>
                    </div>
                </div>
                <p></p>
                <div class="formbuilder-button form-group field-reset">
                    <button type="submit" class="btn-default btn" name="submit" access="false" style="default" id="submit">Generate URL</button>
                </div>
            </div>
        </form>
</html>

    </body>
</html>
