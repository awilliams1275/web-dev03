FRONT-END

<style>
/* -------------------------------- 

Primary style

-------------------------------- */
*, *::after, *::before {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

*::after, *::before {
  content: '';
}

body {
/* font-size: 100%;
  font-family: "PT Sans", sans-serif;
  color: #f8f7ee;
  */
}

a {
  color: #f05451;
  text-decoration: none;
}

/* -------------------------------- 

Main components 

-------------------------------- */
fordham_header {
  position: relative;
  height: 160px;
  line-height: 180px;
  text-align: center;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
fordham_header h1 {
  font-size: 20px;
  font-size: 1.25rem;
}
@media only screen and (min-width: 768px) {
  fordham_header {
    height: 200px;
    line-height: 225px;
  }
  fordham_header h1 {
    font-size: 26px;
    font-size: 1.625rem;
  }
}

.cd-tabs {
  position: relative;
  width: 90%;
  max-width: 960px;
  margin: 2em auto;
}
.cd-tabs:after {
  content: "";
  display: table;
  clear: both;
}
.cd-tabs::after {
  /* subtle gradient layer on top right - to indicate it's possible to scroll */
  position: absolute;
  top: 0;
  right: 0;
  height: 60px;
  width: 50px;
  z-index: 1;
  pointer-events: none;
  background: -webkit-linear-gradient( right , #f8f7ee, rgba(248, 247, 238, 0));
  background: linear-gradient(to left, #f8f7ee, rgba(248, 247, 238, 0));
  visibility: visible;
  opacity: 1;
  -webkit-transition: opacity .3s 0s, visibility 0s 0s;
  -moz-transition: opacity .3s 0s, visibility 0s 0s;
  transition: opacity .3s 0s, visibility 0s 0s;
}
.no-cssgradients .cd-tabs::after {
  display: none;
}
.cd-tabs.is-ended::after {
  /* class added in jQuery - remove the gradient layer when it's no longer possible to scroll */
  visibility: hidden;
  opacity: 0;
  -webkit-transition: opacity .3s 0s, visibility 0s .3s;
  -moz-transition: opacity .3s 0s, visibility 0s .3s;
  transition: opacity .3s 0s, visibility 0s .3s;
}
.cd-tabs nav {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  background: #f8f7ee;
  box-shadow: inset 0 -2px 3px rgba(203, 196, 130, 0.06);
}
@media only screen and (min-width: 768px) {
  .cd-tabs::after {
    display: none;
  }
  .cd-tabs nav {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    box-shadow: inset -2px 0 3px rgba(203, 196, 130, 0.06);
    z-index: 1;
  }
}
@media only screen and (min-width: 960px) {
  .cd-tabs nav {
    position: relative;
    float: none;
    background: transparent;
    box-shadow: none;
  }
}

.cd-tabs-navigation {
  width: 360px;
}
.cd-tabs-navigation:after {
  content: "";
  display: table;
  clear: both;
}
.cd-tabs-navigation li {
  float: left;
}
.cd-tabs-navigation a {
  position: relative;
  display: block;
  height: 60px;
  width: 60px;
  text-align: center;
  font-size: 12px;
  font-size: 0.75rem;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  font-weight: 700;
  color: #c3c2b9;
  padding-top: 34px;
}
.no-touch .cd-tabs-navigation a:hover {
  color: #29324e;
  background-color: rgba(233, 230, 202, 0.3);
}
.cd-tabs-navigation a.selected {
  background-color: #ffffff !important;
  box-shadow: inset 0 2px 0 #f05451;
  color: #29324e;
}
.cd-tabs-navigation a::before {
  /* icons */
  position: absolute;
  top: 12px;
  left: 50%;
  margin-left: -10px;
  display: inline-block;
  height: 20px;
  width: 20px;
  background-image: url("../img/vicons.svg");
  background-repeat: no-repeat;
}
.cd-tabs-navigation a[data-content='inbox']::before {
  background-position: 0 0;
}
.cd-tabs-navigation a[data-content='new']::before {
  background-position: -20px 0;
}
.cd-tabs-navigation a[data-content='gallery']::before {
  background-position: -40px 0;
}
.cd-tabs-navigation a[data-content='store']::before {
  background-position: -60px 0;
}
.cd-tabs-navigation a[data-content='settings']::before {
  background-position: -80px 0;
}
.cd-tabs-navigation a[data-content='trash']::before {
  background-position: -100px 0;
}
.cd-tabs-navigation a[data-content='inbox'].selected::before {
  background-position: 0 -20px;
}
.cd-tabs-navigation a[data-content='new'].selected::before {
  background-position: -20px -20px;
}
.cd-tabs-navigation a[data-content='gallery'].selected::before {
  background-position: -40px -20px;
}
.cd-tabs-navigation a[data-content='store'].selected::before {
  background-position: -60px -20px;
}
.cd-tabs-navigation a[data-content='settings'].selected::before {
  background-position: -80px -20px;
}
.cd-tabs-navigation a[data-content='trash'].selected::before {
  background-position: -100px -20px;
}
@media only screen and (min-width: 768px) {
  .cd-tabs-navigation {
    /* move the nav to the left on medium sized devices */
    width: 80px;
    float: left;
  }
  .cd-tabs-navigation a {
    height: 80px;
    width: 80px;
    padding-top: 46px;
  }
  .cd-tabs-navigation a.selected {
    box-shadow: inset 2px 0 0 #f05451;
  }
  .cd-tabs-navigation a::before {
    top: 22px;
  }
}
@media only screen and (min-width: 960px) {
  .cd-tabs-navigation {
    /* tabbed on top on big devices */
    width: auto;
    background-color: #f8f7ee;
    box-shadow: inset 0 -2px 3px rgba(203, 196, 130, 0.06);
  }
  .cd-tabs-navigation a {
    height: 60px;
    line-height: 60px;
    width: auto;
    text-align: left;
    font-size: 14px;
    font-size: 0.875rem;
    padding: 0 2.8em 0 4.6em;
  }
  .cd-tabs-navigation a.selected {
    box-shadow: inset 0 2px 0 #f05451;
  }
  .cd-tabs-navigation a::before {
    top: 50%;
    margin-top: -10px;
    margin-left: 0;
    left: 38px;
  }
}

.cd-tabs-content {
  background: #ffffff;
}
.cd-tabs-content li {
  display: none;
  padding: 1.4em;
}
.cd-tabs-content li.selected {
  display: block;
  -webkit-animation: cd-fade-in 0.5s;
  -moz-animation: cd-fade-in 0.5s;
  animation: cd-fade-in 0.5s;
}
.cd-tabs-content li p {
  font-size: 14px;
  font-size: 0.875rem;
  line-height: 1.6;
  color: #8493bf;
  margin-bottom: 2em;
}
@media only screen and (min-width: 768px) {
  .cd-tabs-content {
    min-height: 480px;
  }
  .cd-tabs-content li {
    padding: 2em 2em 2em 7em;
  }
}
@media only screen and (min-width: 960px) {
  .cd-tabs-content {
    min-height: 0;
  }
  .cd-tabs-content li {
    padding: 3em;
  }
  .cd-tabs-content li p {
    font-size: 16px;
    font-size: 1rem;
  }
}

@-webkit-keyframes cd-fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
@-moz-keyframes cd-fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
@keyframes cd-fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}


</style>


<div class="cd-tabs">
	<nav>
		<ul class="cd-tabs-navigation">
			<li><a data-content="inbox" class="selected" href="#0">Inbox</a></li>
			<li><a data-content="new" href="#0">New</a></li>
			<li><a data-content="gallery" href="#0">Gallery</a></li>
			
		</ul> <!-- cd-tabs-navigation -->
	</nav>

	<ul class="cd-tabs-content">
		<li data-content="inbox" class="selected">
			<p>Inbox Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum recusandae rem animi accusamus quisquam reprehenderit sed voluptates, numquam, quibusdam velit dolores repellendus tempora corrupti accusantium obcaecati voluptate totam eveniet laboriosam?</p>

			<p>Inbox Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum recusandae rem animi accusamus quisquam reprehenderit sed voluptates, numquam, quibusdam velit dolores repellendus tempora corrupti accusantium obcaecati voluptate totam eveniet laboriosam?</p>
		</li>

		<li data-content="new">
			<p>New Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non a voluptatibus, ex odit totam cumque nihil eos asperiores ea, labore rerum. Doloribus tenetur quae impedit adipisci, laborum dolorum eaque ratione quaerat, eos dicta consequuntur atque ex facere voluptate cupiditate incidunt.</p>

			<p>New Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non a voluptatibus, ex odit totam cumque nihil eos asperiores ea, labore rerum. Doloribus tenetur quae impedit adipisci, laborum dolorum eaque ratione quaerat, eos dicta consequuntur atque ex facere voluptate cupiditate incidunt.</p>
		</li>

		<li data-content="gallery">
			<p>Gallery Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque tenetur aut, cupiditate, libero eius rerum incidunt dolorem quo in officia.</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A ipsa vero, culpa doloremque voluptatum consectetur mollitia, atque expedita unde excepturi id, molestias maiores delectus quos molestiae. Ab iure provident adipisci eveniet quisquam ratione libero nam inventore error pariatur optio facilis assumenda sint atque cumque, omnis perspiciatis. Maxime minus quam voluptatum provident aliquam voluptatibus vel rerum. Soluta nulla tempora aspernatur maiores! Animi accusamus officiis neque exercitationem dolore ipsum maiores delectus asperiores reprehenderit pariatur placeat, quaerat sed illum optio qui enim odio temporibus, nulla nihil nemo quod dicta consectetur obcaecati vel. Perspiciatis animi corrupti quidem fugit deleniti, atque mollitia labore excepturi ut.</p>
		</li>

	
	</ul> <!-- cd-tabs-content -->
</div>


--------------------------------------------------
FRONT-END JAVASCRIPT
--------------------------------------------------
jQuery(document).ready(function($){
	var tabs = $('.cd-tabs');
	
	tabs.each(function(){
		var tab = $(this),
			tabItems = tab.find('ul.cd-tabs-navigation'),
			tabContentWrapper = tab.children('ul.cd-tabs-content'),
			tabNavigation = tab.find('nav');

		tabItems.on('click', 'a', function(event){
			event.preventDefault();
			var selectedItem = $(this);
			if( !selectedItem.hasClass('selected') ) {
				var selectedTab = selectedItem.data('content'),
					selectedContent = tabContentWrapper.find('li[data-content="'+selectedTab+'"]'),
					slectedContentHeight = selectedContent.innerHeight();
				
				tabItems.find('a.selected').removeClass('selected');
				selectedItem.addClass('selected');
				selectedContent.addClass('selected').siblings('li').removeClass('selected');
				//animate tabContentWrapper height when content changes 
				tabContentWrapper.animate({
					'height': slectedContentHeight
				}, 200);
			}
		});

		//hide the .cd-tabs::after element when tabbed navigation has scrolled to the end (mobile version)
		checkScrolling(tabNavigation);
		tabNavigation.on('scroll', function(){ 
			checkScrolling($(this));
		});
	});
	
	$(window).on('resize', function(){
		tabs.each(function(){
			var tab = $(this);
			checkScrolling(tab.find('nav'));
			tab.find('.cd-tabs-content').css('height', 'auto');
		});
	});

	function checkScrolling(tabs){
		var totalTabWidth = parseInt(tabs.children('.cd-tabs-navigation').width()),
		 	tabsViewport = parseInt(tabs.width());
		if( tabs.scrollLeft() >= totalTabWidth - tabsViewport) {
			tabs.parent('.cd-tabs').addClass('is-ended');
		} else {
			tabs.parent('.cd-tabs').removeClass('is-ended');
		}
	}
});