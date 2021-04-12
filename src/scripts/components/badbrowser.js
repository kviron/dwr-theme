const badbrowser = document.querySelector( '.badbrowser' )

if ( badbrowser ) {
	function get_name_browser()
	{
		const ua = navigator.userAgent;
		if ( ua.search( /YaBrowser/ ) > 0 ) return 'Яндекс Браузер';
		if ( ua.search( /rv:11.0/ ) > 0 ) return 'Internet Explorer';
		if ( ua.search( /rv:11.0.20/ ) > 0 ) return 'Internet Explorer';
		if ( ua.search( /MSIE/ ) > 0 ) return 'Internet Explorer';
		if ( ua.search( /Edge/ ) > 0 ) return 'Edge';
		if ( ua.search( /Chrome/ ) > 0 ) return 'Google Chrome';
		if ( ua.search( /Firefox/ ) > 0 ) return 'Firefox';
		if ( ua.search( /Opera/ ) > 0 ) return 'Opera';
		if ( ua.search( /Safari/ ) > 0 ) return 'Safari';
		return false;
	}

	const browser = get_name_browser();
	if ( browser == 'Internet Explorer' ) {
		badbrowser.classList.remove( 'hidden' )

	} else {
		if ( badbrowser ) {
			document.cookie = "badbrowser_modal=true; path=/"
		}

	}

	const closeModal = document.querySelector( '.close-modal' )

	closeModal.addEventListener( 'click', function ( event ) {
		event.preventDefault()
		badbrowser.classList.add( 'hidden' )
		document.cookie = "badbrowser_modal=true; path=/"
	} )
}
