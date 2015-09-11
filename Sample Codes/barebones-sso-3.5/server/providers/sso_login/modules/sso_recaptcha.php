<?php
	// SSO Generic Login Module for reCAPTCHA support
	// (C) 2014 CubicleSoft.  All Rights Reserved.

	if (!defined("SSO_FILE"))  exit();

	$g_sso_login_modules["sso_recaptcha"] = array(
		"name" => "reCAPTCHA",
		"desc" => "Adds reCAPTCHA support for registration and/or logins."
	);

	class sso_login_module_sso_recaptcha extends sso_login_ModuleBase
	{
		private $themes;

		public function DefaultOrder()
		{
			return 150;
		}

		private function GetInfo()
		{
			global $sso_settings;

			$this->themes = array(
				"red" => "Red",
				"white" => "White",
				"blackglass" => "Black Glass",
				"clean" => "Clean"
			);

			$info = $sso_settings["sso_login"]["modules"]["sso_recaptcha"];
			if (!isset($info["publickey"]))  $info["publickey"] = "";
			if (!isset($info["privatekey"]))  $info["privatekey"] = "";
			if (!isset($info["theme"]))  $info["theme"] = "red";
			if (!isset($info["register"]))  $info["register"] = true;
			if (!isset($info["login_interval"]))  $info["login_interval"] = 15 * 60;
			if (!isset($info["login_attempts"]))  $info["login_attempts"] = 3;

			return $info;
		}

		public function ConfigSave()
		{
			global $sso_settings;

			$info = $this->GetInfo();
			$info["publickey"] = trim($_REQUEST["sso_recaptcha_publickey"]);
			$info["privatekey"] = trim($_REQUEST["sso_recaptcha_privatekey"]);
			$info["theme"] = (isset($this->themes[$_REQUEST["sso_recaptcha_theme"]]) ? $_REQUEST["sso_recaptcha_theme"] : "red");
			$info["register"] = ($_REQUEST["sso_recaptcha_register"] > 0);
			$info["login_interval"] = (int)$_REQUEST["sso_recaptcha_login_interval"];
			$info["login_attempts"] = (int)$_REQUEST["sso_recaptcha_login_attempts"];

			if ($info["publickey"] == "")  BB_SetPageMessage("info", "The 'reCAPTCHA Public Key' field is empty.");
			else if ($info["privatekey"] == "")  BB_SetPageMessage("info", "The 'reCAPTCHA Private Key' field is empty.");

			if ($info["login_interval"] < 1)  BB_SetPageMessage("error", "The 'reCAPTCHA Login/Recovery Attempts Interval' field contains an invalid value.");
			else if ($info["login_attempts"] < 1)  BB_SetPageMessage("error", "The 'reCAPTCHA Login/Recovery Attempts Per Interval' field contains an invalid value.");

			$sso_settings["sso_login"]["modules"]["sso_recaptcha"] = $info;
		}

		public function Config(&$contentopts)
		{
			$info = $this->GetInfo();
			$contentopts["fields"][] = array(
				"title" => "reCAPTCHA Public Key",
				"type" => "text",
				"name" => "sso_recaptcha_publickey",
				"value" => BB_GetValue("sso_recaptcha_publickey", $info["publickey"]),
				"htmldesc" => "You get a public key when you <a href=\"https://www.google.com/recaptcha/admin/list\" target=\"_blank\">sign up for the reCAPTCHA service</a>.  reCAPTCHA will not work without a public key!"
			);
			$contentopts["fields"][] = array(
				"title" => "reCAPTCHA Private Key",
				"type" => "text",
				"name" => "sso_recaptcha_privatekey",
				"value" => BB_GetValue("sso_recaptcha_privatekey", $info["privatekey"]),
				"htmldesc" => "You get a private key when you <a href=\"https://www.google.com/recaptcha/admin/list\" target=\"_blank\">sign up for the reCAPTCHA service</a>.  reCAPTCHA will not work without a private key!"
			);
			$contentopts["fields"][] = array(
				"title" => "reCAPTCHA Theme",
				"type" => "select",
				"name" => "sso_recaptcha_theme",
				"options" => $this->themes,
				"select" => BB_GetValue("sso_recaptcha_theme", $info["theme"]),
				"desc" => "Select the theme to use.  The default theme works well with most web designs."
			);
			$contentopts["fields"][] = array(
				"title" => "Registration reCAPTCHA?",
				"type" => "select",
				"name" => "sso_recaptcha_register",
				"options" => array(1 => "Yes", 0 => "No"),
				"select" => BB_GetValue("sso_recaptcha_register", (string)(int)$info["register"]),
				"desc" => "Require reCAPTCHA entry during registration."
			);
			$contentopts["fields"][] = array(
				"title" => "reCAPTCHA Login/Recovery Attempts Interval",
				"type" => "text",
				"name" => "sso_recaptcha_login_interval",
				"value" => BB_GetValue("sso_recaptcha_login_interval", $info["login_interval"]),
				"desc" => "The interval, in seconds, over which failed login and recovery attempts will be measured.  Default is 900 (15 minutes)."
			);
			$contentopts["fields"][] = array(
				"title" => "reCAPTCHA Login/Recovery Attempts Per Interval",
				"type" => "text",
				"name" => "sso_recaptcha_login_attempts",
				"value" => BB_GetValue("sso_recaptcha_login_attempts", $info["login_attempts"]),
				"desc" => "The number of failed login and recovery attempts that may be made within the specified interval above from a single IP address before reCAPTCHA is required.  Default is 3."
			);
		}

		public function AddIPCacheInfo($displayname)
		{
			global $info, $contentopts;

			if (isset($info["sso_login_modules"]) && isset($info["sso_login_modules"]["sso_recaptcha"]))
			{
				$info2 = $this->GetInfo();
				$num = $info["sso_login_modules"]["sso_recaptcha"]["logins"];
				$contentopts["fields"][] = array(
					"title" => BB_Translate("%s - reCAPTCHA - Login/Recovery Attempts", $displayname),
					"type" => "custom",
					"value" => BB_Translate("%d login/recovery attempt" . ($num == 1 ? "" : "s") . " since %s.  Limit %d before showing reCAPTCHA.", $num, BB_FormatTimestamp("M j, Y @ g:i A", CSDB::ConvertFromDBTime($info["sso_login_modules"]["sso_ratelimit"]["ts"])), $info2["login_attempts"]),
				);
			}
			else
			{
				$contentopts["fields"][] = array(
					"title" => BB_Translate("%s - reCAPTCHA Information", $displayname),
					"type" => "custom",
					"value" => "<i>" . htmlspecialchars(BB_Translate("Undefined (No information found)")) . "</i>"
				);
			}
		}

		public function SignupCheck(&$result, $ajax, $admin)
		{
			global $sso_ipaddr;

			if ($admin)  return;

			if (!$ajax)
			{
				$info = $this->GetInfo();
				if ($info["register"] && $info["publickey"] != "" && $info["privatekey"] != "")
				{
					if (!isset($_REQUEST["recaptcha_challenge_field"]) || !isset($_REQUEST["recaptcha_response_field"]))  $result["errors"][] = BB_Translate("CAPTCHA challenge-response is missing.");
					else
					{
						require_once SSO_ROOT_PATH . "/" . SSO_SUPPORT_PATH . "/recaptchalib.php";

						$response = recaptcha_check_answer($info["privatekey"], $sso_ipaddr["ipv6"], $_REQUEST["recaptcha_challenge_field"], $_REQUEST["recaptcha_response_field"]);
						if (!$response->is_valid)  $result["errors"][] = BB_Translate("Incorrect CAPTCHA entered.  Try again.  (Code:  %s)", BB_Translate($response->error));
					}
				}
			}
		}

		private function DisplayreCAPTCHA($info)
		{
			global $bb_admin_lang;

			$recaptchalangs = array(
				"en" => true,
				"nl" => true,
				"fr" => true,
				"de" => true,
				"pt" => true,
				"ru" => true,
				"es" => true,
				"tr" => true,
			);
			if (isset($recaptchalangs[substr($bb_admin_lang, 0, 2)]))  $calclang = substr($bb_admin_lang, 0, 2);
			else if (!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))  $calclang = "en";
			else
			{
				$calclang = "";
				$langs = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
				foreach ($langs as $lang)
				{
					$lang = trim($lang);
					$pos = strpos($lang, ";");
					if ($pos !== false)  $lang = substr($lang, 0, $pos);
					if ($lang != "")
					{
						$lang = explode(" ", trim(preg_replace('/[^a-z]/', " ", strtolower($lang))));
						if (count($lang) && isset($recaptchalangs[$lang[0]]))
						{
							$calclang = $lang[0];
							break;
						}
					}
				}
				if ($calclang == "")  $calclang = "en";
			}

?>
			<script type="text/javascript">
			var RecaptchaOptions = {
				<?php echo htmlspecialchars(BB_JSSafe(BB_Translate("custom_translations : null"))); ?>,
				lang : '<?php echo htmlspecialchars(BB_JSSafe(substr($calclang, 0, 2))); ?>',
				theme : '<?php echo htmlspecialchars(BB_JSSafe($info["theme"])); ?>'
			};
			</script>
			<div class="sso_main_formitem">
				<div class="sso_main_formtitle"><?php echo htmlspecialchars(BB_Translate("Solve This Problem")); ?></div>
<?php
			require_once SSO_ROOT_PATH . "/" . SSO_SUPPORT_PATH . "/recaptchalib.php";

			echo recaptcha_get_html($info["publickey"], null, BB_IsSSLRequest());
?>
			</div>
<?php
		}

		public function GenerateSignup($admin)
		{
			if ($admin)  return false;

			$info = $this->GetInfo();
			if ($info["register"] && $info["publickey"] != "" && $info["privatekey"] != "")  $this->DisplayreCAPTCHA($info);
		}

		private function UpdateIPAddrInfo($inclogins)
		{
			global $sso_ipaddr_info;

			$info = $this->GetInfo();
			if (isset($sso_ipaddr_info["sso_login_modules"]["sso_recaptcha"]))  $result = $sso_ipaddr_info["sso_login_modules"]["sso_recaptcha"];
			else
			{
				$result = array(
					"ts" => CSDB::ConvertToDBTime(time()),
					"logins" => 0
				);
			}

			// Check expirations and reset if necessary.
			if (CSDB::ConvertFromDBTime($result["ts"]) < time() - $info["login_interval"])
			{
				$result["ts"] = CSDB::ConvertToDBTime(time());
				$result["logins"] = 0;
			}

			// Increment requested.
			if ($inclogins && $result["logins"] < $info["login_attempts"])  $result["logins"]++;

			$sso_ipaddr_info["sso_login_modules"]["sso_recaptcha"] = $result;

			// Save the information.
			SSO_SaveIPAddrInfo();
		}

		public function LoginCheck(&$result, $userinfo, $recoveryallowed)
		{
			global $sso_ipaddr, $sso_ipaddr_info;

			if ($userinfo === false)
			{
				$this->UpdateIPAddrInfo(false);

				$info = $this->GetInfo();
				if ($sso_ipaddr_info["sso_login_modules"]["sso_recaptcha"]["logins"] >= $info["login_attempts"])
				{
					if ($info["publickey"] != "" && $info["privatekey"] != "")
					{
						if (!isset($_REQUEST["recaptcha_challenge_field"]) || !isset($_REQUEST["recaptcha_response_field"]))  $result["errors"][] = BB_Translate("CAPTCHA challenge-response is missing.");
						else
						{
							require_once SSO_ROOT_PATH . "/" . SSO_SUPPORT_PATH . "/recaptchalib.php";

							$response = recaptcha_check_answer($info["privatekey"], $sso_ipaddr["ipv6"], $_REQUEST["recaptcha_challenge_field"], $_REQUEST["recaptcha_response_field"]);
							if (!$response->is_valid)  $result["errors"][] = BB_Translate("Incorrect CAPTCHA entered.  Try again.  (Code:  %s)", BB_Translate($response->error));
						}
					}
				}
			}
		}

		public function GenerateLogin($messages)
		{
			global $sso_ipaddr_info;

			$this->UpdateIPAddrInfo($messages !== false && count($messages["errors"]) > 0);

			$info = $this->GetInfo();
			if ($sso_ipaddr_info["sso_login_modules"]["sso_recaptcha"]["logins"] >= $info["login_attempts"])
			{
				if ($info["publickey"] != "" && $info["privatekey"] != "")  $this->DisplayreCAPTCHA($info);
			}
		}

		public function RecoveryCheck(&$result, $userinfo)
		{
			$this->LoginCheck($result, $userinfo, false);
		}

		public function GenerateRecovery($messages)
		{
			$this->GenerateLogin($messages);
		}

		public function RecoveryCheck2(&$result, $userinfo)
		{
			$this->LoginCheck($result, $userinfo, false);
		}

		public function GenerateRecovery2($messages)
		{
			$this->GenerateLogin($messages);
		}
	}
?>