<!--copy to clipboard button-->
   <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
<script>
new Clipboard('.fu_copytocbbtn');
</script>
     <button class="fu_copytocbbtn" data-clipboard-text="<?php print 'http://'. DOMAIN . buildDownloadsURL(-1, $fileItem->id, $download->id); ?>">Copy PDF URL</button>
