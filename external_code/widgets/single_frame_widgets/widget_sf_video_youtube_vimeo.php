VIMEO AND YOUTUBE VIDEO PLAYER:
-------------------------------------------------------
FRONT-END
-------------------------------------------------------

<?php

$VIDEOURL_B_1  = "%VIDEOURL_B_1%";
$VIDEOIMG_B_1  = "%VIDEOIMG_B_1%";
$LEFT_HEAD  = "%LEFT_HEAD%";
$RIGHT_HEAD  = "%RIGHT_HEAD%";

//get the id number of the video URL to use in the lazyYT div tag
$str = $VIDEOURL_B_1 ;
$out = array();
$arr = explode('/', $str);

$result = count($arr);
$videourl =  $arr[$result-1];

//get the video server:
if (strpos($VIDEOURL_B_1, "vimeo")){
$videosource = "https://player.vimeo.com/video/";
}
else{
$videosource = "//www.youtube.com/embed/";}
?>
<style>

.lazyYT-title {
    z-index: 100!important;
    color: #ffffff!important;
    font-family: sans-serif!important;
    font-size: 12px!important;
    top: 10px!important;
    left: 12px!important;
    position: absolute!important;
    margin: 0!important;
    padding: 0!important;
    line-height: 1!important;
    font-style: normal!important;
    font-weight: normal!important;

}

.lazyYT-button {
    margin: 0!important;
    padding: 0!important;
 width: 64px!important;
    height: 64px!important;
    z-index: 100!important;
    position: absolute!important;
    top: 50%!important;
    margin-top: -22px!important;
    left: 50%!important;
    margin-left: -30px!important;
    line-height: 1!important;
    font-style: normal!important;
    font-weight: normal!important;
    background-image: url('http://<?php print DOMAIN ?>/site/images/video_play.png')!important;
background-repeat:no-repeat;
}

#player {
        box-sizing: border-box;
        background-size: contain;
        padding: 6% 15.5% 9%;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
z-index:100;
}

.youtube-container { display: block; margin: 20px auto; width: 100%; max-width: 600px; }
.youtube-player { display: block; width: 100%; background-color:#c9c9c9;/* assuming that the video has a 16:9 ratio */ padding-bottom: 56.25%; overflow: hidden; position: relative; width: 100%; height: 100%; cursor: hand; cursor: pointer; display: block; }
img.youtube-thumb { bottom: 0; display: block; left: 0; margin: auto; max-width: 100%; width: 100%; position: absolute; right: 0; top: 0; height: auto }
div.play-button { height: 72px; width: 72px; left: 50%; top: 50%; margin-left: -36px; margin-top: -36px; position: absolute; background: url("http://i.imgur.com/TxzC70f.png") no-repeat; }
#youtube-iframe { width: 100%; height: 100%; position: absolute; top: 0; left: 0; }
</style>
<script>
 function newSource() {
var theclient = "";

               var isMobile = {

                   Android: function () {

                       return navigator.userAgent.match(/Android/i) ? true : false;

                   },

                   BlackBerry: function () {

                       return navigator.userAgent.match(/BlackBerry/i) ? true : false;

                   },

                   iOS: function () {

                       return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;

                   },

                   Windows: function () {

                       return navigator.userAgent.match(/IEMobile/i) ? true : false;

                   },

                   any: function () {

                       return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());

                   }

               };
if (!isMobile.any()) {


       var height = "100%";
var width = "100%";
document.getElementById('youtube-player').innerHTML= ('<iframe width="' + width + '" height="' + height + '" src="<?php echo $videosource?><?php echo $videourl?>?rel=0&autoplay=0&autohide=0"  style="position:absolute; top:0; left:0; width:100%; height:100%;" allowFullScreen></iframe>');
    }
else{ 
function newSource(){
 var height = "100%";
var width = "100%";
document.getElementById('youtube-player').innerHTML= ('');
}

 }
}
function refresh() {
document.getElementById('youtube-player').innerHTML= ('<iframe width="100%" height="100%" src="<?php echo $videosource?><?php echo $videourl?>?rel=0&autoplay=true"  style="position:absolute; top:0; left:0; width:100%; height:100%;" allowFullScreen></iframe>');
}
</script>

<section class="slider-numbers video image">
  <div class="w_row twelve-hun-max">
<!--  <h5 class="section-title small-12 columns fu_video_head1"><?php print $LEFT_HEAD ?></h5>
-->
    <div class="large-12 columns">
      <div class="w_row">
        <ul>
          <li>
<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_1 ?>" alt="" title="" class="fu_video_div_img" />
          <div class="orbit-caption fu_video_div">
            <p><a href="#" class="videoModal-1">

<img src="/images/video_circle.png" alt="" title="" class="fu_video_circle"  onclick="refresh();" /></p>
<p><span class="fu_video_text">
<?php 
$VIDEOS_B_1  = "%VIDEOS_B_1%";
print $VIDEOS_B_1;
?></span></a></p>
          </div>
          </li>
         
        </ul>
      </div>
    </div>
<!--    <a class="section-link" href="<?php print $RIGHT_HEAD ?>" title="">View All Videos</a>
-->
  </div>
</section>

 
<div id="videoModal-1" class="reveal-modal large">
		<div class="modal-content">
			<h2>Video 1</h2>
			<div class="flex-video" id="youtube-player">
				
		<iframe data-src="<?php echo $videosource?><?php echo $videourl?>?rel=0" width="320" height="240" allowfullscreen autoplay="1"></iframe>
			</div>
		</div>
		<a class="close-reveal-modal" id="close" onclick="newSource();">x</a>
  </div>


-------------------------------------------------------
FRONT-END JAVASCRIPT
-------------------------------------------------------
/*! Lazy Load XT v1.0.6 2014-11-19
 * http://ressio.github.io/lazy-load-xt
 * (C) 2014 RESS.io
 * Licensed under MIT */

(function ($, window, document, undefined) {
    // options
    var lazyLoadXT = 'lazyLoadXT',
        dataLazied = 'lazied',
        load_error = 'load error',
        classLazyHidden = 'lazy-hidden',
        docElement = document.documentElement || document.body,
    //  force load all images in Opera Mini and some mobile browsers without scroll event or getBoundingClientRect()
        forceLoad = (window.onscroll === undefined || !!window.operamini || !docElement.getBoundingClientRect),
        options = {
            autoInit: true, // auto initialize in $.ready
            selector: 'img[data-src]', // selector for lazyloading elements
            blankImage: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
            throttle: 99, // interval (ms) for changes check
            forceLoad: forceLoad, // force auto load all images

            loadEvent: 'pageshow', // check AJAX-loaded content in jQueryMobile
            updateEvent: 'load orientationchange resize scroll touchmove focus', // page-modified events
            forceEvent: '', // force loading of all elements

            //onstart: null,
            oninit: {removeClass: 'lazy'}, // init handler
            onshow: {addClass: classLazyHidden}, // start loading handler
            onload: {removeClass: classLazyHidden, addClass: 'lazy-loaded'}, // load success handler
            onerror: {removeClass: classLazyHidden}, // error handler
            //oncomplete: null, // complete handler

            //scrollContainer: undefined,
            checkDuplicates: true
        },
        elementOptions = {
            srcAttr: 'data-src',
            edgeX: 0,
            edgeY: 0,
            visibleOnly: true
        },
        $window = $(window),
        $isFunction = $.isFunction,
        $extend = $.extend,
        $data = $.data || function (el, name) {
            return $(el).data(name);
        },
    // $.contains is not included into DOMtastic, so implement it there
        $contains = $.contains || function (parent, el) {
            while (el = el.parentNode) {
                if (el === parent) {
                    return true;
                }
            }
            return false;
        },
        elements = [],
        topLazy = 0,
    /*
     waitingMode=0 : no setTimeout
     waitingMode=1 : setTimeout, no deferred events
     waitingMode=2 : setTimeout, deferred events
     */
        waitingMode = 0;

    $[lazyLoadXT] = $extend(options, elementOptions, $[lazyLoadXT]);

    /**
     * Return options.prop if obj.prop is undefined, otherwise return obj.prop
     * @param {*} obj
     * @param {*} prop
     * @returns *
     */
    function getOrDef(obj, prop) {
        return obj[prop] === undefined ? options[prop] : obj[prop];
    }

    /**
     * @returns {number}
     */
    function scrollTop() {
        var scroll = window.pageYOffset;
        return (scroll === undefined) ? docElement.scrollTop : scroll;
    }

    /**
     * Add new elements to lazy-load list:
     * $(elements).lazyLoadXT() or $(window).lazyLoadXT()
     *
     * @param {object} [overrides] override global options
     */
    $.fn[lazyLoadXT] = function (overrides) {
        overrides = overrides || {};

        var blankImage = getOrDef(overrides, 'blankImage'),
            checkDuplicates = getOrDef(overrides, 'checkDuplicates'),
            scrollContainer = getOrDef(overrides, 'scrollContainer'),
            elementOptionsOverrides = {},
            prop;

        // empty overrides.scrollContainer is supported by both jQuery and Zepto
        $(scrollContainer).on('scroll', queueCheckLazyElements);

        for (prop in elementOptions) {
            elementOptionsOverrides[prop] = getOrDef(overrides, prop);
        }

        return this.each(function (index, el) {
            if (el === window) {
                $(options.selector).lazyLoadXT(overrides);
            } else {
                // prevent duplicates
                if (checkDuplicates && $data(el, dataLazied)) {
                    return;
                }

                var $el = $(el).data(dataLazied, 1);

                if (blankImage && el.tagName === 'IMG' && !el.src) {
                    el.src = blankImage;
                }

                // clone elementOptionsOverrides object
                $el[lazyLoadXT] = $extend({}, elementOptionsOverrides);

                triggerEvent('init', $el);

                elements.push($el);
            }
        });
    };


    /**
     * Process function/object event handler
     * @param {string} event suffix
     * @param {jQuery} $el
     */
    function triggerEvent(event, $el) {
        var handler = options['on' + event];
        if (handler) {
            if ($isFunction(handler)) {
                handler.call($el[0]);
            } else {
                if (handler.addClass) {
                    $el.addClass(handler.addClass);
                }
                if (handler.removeClass) {
                    $el.removeClass(handler.removeClass);
                }
            }
        }

        $el.trigger('lazy' + event, [$el]);

        // queue next check as images may be resized after loading of actual file
        queueCheckLazyElements();
    }


    /**
     * Trigger onload/onerror handler
     * @param {Event} e
     */
    function triggerLoadOrError(e) {
        triggerEvent(e.type, $(this).off(load_error, triggerLoadOrError));
    }


    /**
     * Load visible elements
     * @param {bool} [force] loading of all elements
     */
    function checkLazyElements(force) {
        if (!elements.length) {
            return;
        }

        force = force || options.forceLoad;

        topLazy = Infinity;

        var viewportTop = scrollTop(),
            viewportHeight = window.innerHeight || docElement.clientHeight,
            viewportWidth = window.innerWidth || docElement.clientWidth,
            i,
            length;

        for (i = 0, length = elements.length; i < length; i++) {
            var $el = elements[i],
                el = $el[0],
                objData = $el[lazyLoadXT],
                removeNode = false,
                visible = force,
                topEdge;

            // remove items that are not in DOM
            if (!$contains(docElement, el)) {
                removeNode = true;
            } else if (force || !objData.visibleOnly || el.offsetWidth || el.offsetHeight) {

                if (!visible) {
                    var elPos = el.getBoundingClientRect(),
                        edgeX = objData.edgeX,
                        edgeY = objData.edgeY;

                    topEdge = (elPos.top + viewportTop - edgeY) - viewportHeight;

                    visible = (topEdge <= viewportTop && elPos.bottom > -edgeY &&
                        elPos.left <= viewportWidth + edgeX && elPos.right > -edgeX);
                }

                if (visible) {
                    triggerEvent('show', $el);

                    var srcAttr = objData.srcAttr,
                        src = $isFunction(srcAttr) ? srcAttr($el) : el.getAttribute(srcAttr);
                    if (src) {
                        $el.on(load_error, triggerLoadOrError);
                        el.src = src;
                    }

                    removeNode = true;
                } else {
                    if (topEdge < topLazy) {
                        topLazy = topEdge;
                    }
                }
            }

            if (removeNode) {
                elements.splice(i--, 1);
                length--;
            }
        }

        if (!length) {
            triggerEvent('complete', $(docElement));
        }
    }


    /**
     * Run check of lazy elements after timeout
     */
    function timeoutLazyElements() {
        if (waitingMode > 1) {
            waitingMode = 1;
            checkLazyElements();
            setTimeout(timeoutLazyElements, options.throttle);
        } else {
            waitingMode = 0;
        }
    }


    /**
     * Queue check of lazy elements because of event e
     * @param {Event} [e]
     */
    function queueCheckLazyElements(e) {
        if (!elements.length) {
            return;
        }

        // fast check for scroll event without new visible elements
        if (e && e.type === 'scroll' && e.currentTarget === window) {
            if (topLazy >= scrollTop()) {
                return;
            }
        }

        if (!waitingMode) {
            setTimeout(timeoutLazyElements, 0);
        }
        waitingMode = 2;
    }


    /**
     * Initialize list of hidden elements
     */
    function initLazyElements() {
        $window.lazyLoadXT();
    }


    /**
     * Loading of all elements
     */
    function forceLoadAll() {
        checkLazyElements(true);
    }


    /**
     * Initialization
     */
    $(document).ready(function () {
        triggerEvent('start', $window);

        $window
            .on(options.loadEvent, initLazyElements)
            .on(options.updateEvent, queueCheckLazyElements)
            .on(options.forceEvent, forceLoadAll);

        $(document).on(options.updateEvent, queueCheckLazyElements);

        if (options.autoInit) {
            initLazyElements(); // standard initialization
        }
    });

})(window.jQuery || window.Zepto || window.$, window, document);

(function ($) {
    var options = $.lazyLoadXT;

    options.selector += ',video,iframe[data-src]';
    options.videoPoster = 'data-poster';

    $(document).on('lazyshow', 'video', function (e, $el) {
        var srcAttr = $el.lazyLoadXT.srcAttr,
            isFuncSrcAttr = $.isFunction(srcAttr);

        $el
            .attr('poster', $el.attr(options.videoPoster))
            .children('source,track')
            .each(function (index, el) {
                var $child = $(el);
                $child.attr('src', isFuncSrcAttr ? srcAttr($child) : $child.attr(srcAttr));
            });

        // reload video
        this.load();
    });

})(window.jQuery || window.Zepto || window.$);


-------------------------------------------------------
SETTINGS
-------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />
<tr><td class="label_cell">Left Head</td>
<td class="data_cell"><input id="left_head" value="" size="45" type="text" />
</td>
</tr>

<tr>
<td class="label_cell">Right Head Link</td>
<td class="data_cell"><input id="right_head" value="" size="45" type="text" />
</td>
</tr>

<tr><td colspan="2" bgcolor="#ffffff">
 
</td></tr>


<tr><td class="label_cell">Video text 1</td><td class="data_cell"><input id="videos_b_1" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL:</td><td class="data_cell"><input id="videourl_b_1" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Image:</td><td class="data_cell"><input type="hidden" id="videoimg_b_1" value="" onchange="$('videoimgi_b_1').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="videoimgi_b_1" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=videoimgi_b_1&imageFilenameID=videoimg_b_1'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>

</table>