function SwitchSiteProfile(profile)
{
	// Initialize the cookie and reload the page.
	var expdate = new Date();
	expdate.setDate(expdate.getDate() + 1);
	document.cookie = "cacheprof=" + profile + "; expires=" + expdate.toUTCString() + "; path=/";

	window.location.reload(true);

	return false;
}