<?php
if (!(defined('DISABLE_WHO_IS_ONLINE') && DISABLE_WHO_IS_ONLINE)) {
	include('collaboration.php');
}
if (isset($editor) && method_exists($editor, 'getImageManagerCallback')) {
    print $editor->getImageManagerCallback();
}
?>
						<div id="collaborationMenu">
							<div>
<?php
	if (!(defined('DISABLE_WHO_IS_ONLINE') && DISABLE_WHO_IS_ONLINE)) {
?>
								<ul class="list--inline">
<?php 
        if (isset($lastLogin)) {                                    
?>
									<li>Last login: <?php print formatDateTime(FORMAT_DATE_FULL, $lastLogin->loginTimestamp); ?> at <?php print formatDateTime(FORMAT_TIME_SHORT, $lastLogin->loginTimestamp); ?></li>
<?php 
        }
?>
									<li><a href="#" class="onlineNowLink"><span id="onlineNowLink" class="<?php print ($showOnlineAdmins) ? 'onlineNow' : 'disabled'; ?>">Online now</span></a></li>
								</ul>
<?php
	}
?>
								<p><a href="#" onclick="scroll(0,0); return false;"><i class="icon icon-circle-arrow-up"></i></a> <a href="<?php print $SECURE_JADU_PATH; ?>/version.php" class="branding-footer">Jadu Continuum</a> &copy; <?php print date('Y'); ?></p>
							</div>
						</div>

					</div><!-- /.tabs__content -->
				</div><!-- /.content-main -->
			</div><!-- /#wrap_content -->
		</div><!-- /.inner-wrapper -->
	</div><!-- /.container -->

<?php
	if (defined('SHOW_JIRA_COLLECTOR') && SHOW_JIRA_COLLECTOR) {
?>
	<!-- jira issue collector code -->
	<script type="text/javascript" src="https://jadultd.atlassian.net/s/c7f59c1392eb12d4a153310916d264cb-T/en_UK-1sg6cb/64003/26/1.4.15/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-UK&collectorId=be51d6f3"></script>
<?php
	}
	// Ability to opt out
	$intercomEnabled = ((string)$jadu->getConfig('system')->get('disable_intelligence') == true) ? false : true;

	if (isset($admin) && $intercomEnabled) {
		$intercom = new Jadu_Intercom_Data($jadu);
?>
<script>
	window.intercomSettings = {
		app_id: "sw1k7kuk",
		name: "<?php print $adminService->getCurrentAdmin()->name;?>", // Full name
		email: "<?php print $adminService->getCurrentAdmin()->email;?>", // Email address
		"cms_domain": "<?php print getSiteRootURL();?>",
		"marketing_opt_in": <?php print (int) $adminService->getCurrentAdmin()->marketingOptIn; ?>,
		"concurrent_admins": <?php print (int) $intercom['concurrent_admins'] ?>,
		"total_admins": <?php print (int) $intercom['total_admins'] ?>,
		"total_homepages": <?php print (int) $intercom['total_homepages'] ?>,
		"total_forms": <?php print (int) $intercom['total_forms'] ?>,
		"2fa_adoption": <?php print (int) $intercom['2fa_adoption'] ?>,
		"user_hash": "<?php print hash_hmac("sha256", $adminService->getCurrentAdmin()->email, "rFO6J9VRnTiUEpQufLWDBmH8HvLmDJNkLJNB3FHr");?>",	
	};
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/sw1k7kuk';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
<?php
	}
?>
	<script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/pulsar/js/bundle.js"></script>
	<script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/pulsar/js/main.js"></script>
</body>
</html>
