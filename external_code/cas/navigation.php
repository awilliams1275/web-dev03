<?php
	$navigationLinks = getAllRupaNavigation();
?>
	<div id="header_links">
<?php
		if (!empty($navigationLinks)) {
?>
		<ul id="rupa_nav">
<?php
				$i=0;
				foreach ($navigationLinks as $navLink) {
						$i++;
						$class = ($i==1) ? "class=\"first\"" : "";
?>
				<li <?php print $class; ?>><a href="<?php print encodeHtml($navLink->url); ?>"><?php print encodeHtml($navLink->linkText); ?></a></li>
<?php
				}
?>
		</ul>
<?php
		}
		
		$title=rawurlencode(encodeHtml(RUPA_INSTALLATION_NAME));
		if ($_SERVER['QUERY_STRING'] != '') {
			$url=rawurlencode(getCurrentRupaPageURL() . '?' . $_SERVER['QUERY_STRING']);
		}
		else {
			$url=rawurlencode(getCurrentRupaPageURL());
		}
?>
		<div class="right">
			<ul id="personal">
<?php
		if (Jadu_Rupa2_Authentication_User::getInstance()->isSessionLoggedIn()) {
			$message = Jadu_Rupa2_Authentication_User::getInstance()->getWelcomeMessage($user);
			if ($message != '') {
?>
				<li id="welcome_message"><?php print $message; ?></li>
<?php
			}
			
			if (Jadu_Rupa2_Authentication_User::getInstance()->canSignOut()) {
?>
			<!--	<li><a href="<?php print RUPA_HOME_URL; ?>?sign_out=1">Sign out</a></li> //-->
				<li><a href="/search/casout.php">Sign Out</a></li>

<?php				
			}
		}
		else {
			// get sign-in / register links
			$signInURL = Jadu_Rupa2_Authentication_User::getInstance()->getSignInURL();
			$registerURL = Jadu_Rupa2_Authentication_User::getInstance()->getRegisterURL();

			if ($signInURL != '') {
?>
					<li><a href="<?php print encodeHtml($signInURL); ?>">Sign in</a></li>
<?php
			}
			
			if ($registerURL != '') {
?>
					<li><a href="<?php print encodeHtml($registerURL); ?>">Register</a></li>
<?php			
			}
		}
?>
				<li>
					<a id="toggleRight" href="#">Social Bookmarks</a>
				</li>
			</ul>
			<div id="bookmark" style="display:none;">
				<h2>Social Bookmarks and media</h2>
				<ul>
					<li class="addtobookmarks"><a onkeypress="addBookmark(); return false;" onclick="addBookmark(); return false;" href="#">Bookmark this page</a></li>
					<li class="twitter"><a href="http://twitter.com/home?status=<?php print $url;?>">Twitter</a></li>
					<li class="digg"><a href="http://digg.com/submit?phase=2&amp;url=<?php print $url; ?>&amp;title=<?php print $title; ?>">digg</a></li>
					<li class="delicious"><a href="http://delicious.com/post?url=<?php print $url; ?>&amp;title=<?php print $title; ?>">delicious</a></li>
					<li class="stumbleupon"><a href="http://www.stumbleupon.com/submit?url=<?php print $url; ?>&amp;title=<?php print $title; ?>">StumbleUpon</a></li>
					<li class="reddit"><a href="http://reddit.com/submit?url=<?php print $url; ?>&amp;title=<?php print $title; ?>">Reddit</a></li>
					<li class="facebook"><a href="http://www.facebook.com/share.php?u=<?php print $url; ?>">Facebook</a></li>
					<li class="google"><a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=<?php print $url; ?>&amp;title=<?php print $title; ?>">Google</a></li>
					<li class="linkedin"><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php print $url; ?>&amp;title=<?php print $title; ?>">LinkedIn</a></li>
				</ul>
				<a id="close_bookmarks"><img alt="close" src="styles/css_img/close.gif" /></a>
			</div>
		</div>
	</div>
