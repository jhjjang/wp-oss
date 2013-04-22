<style>
div.twitter { margin: auto; width: 300px }
#twitter1 { height: 200px; }
#twitter2 { height: 300px; }
#twitter3 { height: 400px; width: 200px; }
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://malsup.github.com/chili-1.7.pack.js"></script>
<script type="text/javascript" src="http://www.malsup.com/jquery/twitter/jquery.twitter.search.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {	
	jQuery('#twitter').twitterSearch({
		term: '<?=$keyword?>',
		animOut: { opacity: 1 }, // no fade
		avatar: false,
		anchors: false,
		bird: false,
		colorExterior: '<?=$color?>',
		colorInterior: 'white',
		pause:   true,
		time: false,
		timeout: 2000,
		css: { 
			img: { width: '30px', height: '30px' },
			frame: { border: '5px solid #C2CFF1', borderRadius: '5px', '-moz-border-radius': '5px', '-webkit-border-radius': '5px' ,height: '<?=$height?>px',width:'<?=$width?>px'},

		}
	});
});
</script>
<div id="twitter" class="twitter" title="Mouse away to resume scrolling tweets"></div>