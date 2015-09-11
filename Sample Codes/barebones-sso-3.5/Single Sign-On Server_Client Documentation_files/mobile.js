// Set a cookie and redirect the browser if the user is running a capable mobile browser.
function Mobile_GetCookie(name)
{
	var start = document.cookie.indexOf(name + "=");
	var length = start + name.length + 1;

	if ((!start) && (name != document.cookie.substring(0, name.length)))  return null;

	if (start == -1)  return null;

	var end = document.cookie.indexOf(';', length);

	if (end == -1)  end = document.cookie.length;

	return document.cookie.substring(length, end);
}

function Mobile_GetBrowserSize()
{
	var tempwidth = 0, tempheight = 0;

	if (typeof(window.innerWidth) == 'number')
	{
		// Non-IE.
		tempwidth = window.innerWidth;
		tempheight = window.innerHeight;
	}
	else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight))
	{
		// IE 6+ in standards compliant mode.
		tempwidth = document.documentElement.clientWidth;
		tempheight = document.documentElement.clientHeight;
	}
	else if (document.body && (document.body.clientWidth || document.body.clientHeight))
	{
		// IE 4 compatible.
		tempwidth = document.body.clientWidth;
		tempheight = document.body.clientHeight;
	}

	return { width : tempwidth, height : tempheight };
}

Gx__mobile_supported = false;
if ((typeof(window.Gx__mobile_disable) != 'boolean' || !Gx__mobile_disable) && navigator.cookieEnabled)
{
	Gx__mobile_supported = true;
	if (Mobile_GetCookie("cacheprof") == null && Mobile_GetBrowserSize().width < 920)
	{
		// Initialize the cookie and reload the page.
		var expdate = new Date();
		expdate.setDate(expdate.getDate() + 1);
		document.cookie = "cacheprof=mobi; expires=" + expdate.toUTCString() + "; path=/";

		window.location.reload(true);
	}
}