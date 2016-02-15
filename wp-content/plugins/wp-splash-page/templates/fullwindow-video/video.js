var tag 			= document.createElement( 'script' );
tag.src 			= "https://www.youtube.com/iframe_api";
var firstScriptTag	= document.getElementsByTagName( 'script' )[0];
firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );

var player;

function onYouTubeIframeAPIReady() {

	player = new YT.Player(
		'player',
		{
			events: {
				'onReady': 			onPlayerReady,
				'onStateChange':	onPlayerStateChange
			}
		}
	);
	
}

function onPlayerReady( event ) {

	event.target.playVideo();
	event.target.setPlaybackQuality( 'highres' );
	
}

function onPlayerStateChange( event ) {

	if ( event.data == YT.PlayerState.ENDED )
		window.location = document.getElementById( 'wpsp-continue' ).href;
	
}
     