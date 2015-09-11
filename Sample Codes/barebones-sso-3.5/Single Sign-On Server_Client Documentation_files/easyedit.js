function BB_GetCookie(name)
{
	var start = document.cookie.indexOf(name + "=");
	var length = start + name.length + 1;

	if ((!start) && (name != document.cookie.substring(0, name.length)))  return null;

	if (start == -1)  return null;

	var end = document.cookie.indexOf(';', length);

	if (end == -1)  end = document.cookie.length;

	return document.cookie.substring(length, end);
}

if (BB_GetCookie("bbq") != null)
{
	editurl = window.location.href;
	if (window.location.hash.length)  editurl = editurl.substring(0, editurl.length - window.location.hash.length);
	if (editurl.indexOf('?') > -1)  editurl = editurl.substring(0, editurl.indexOf('?'));
	document.write('<a style="z-index: 10000; position: absolute; right: 0; top: 0; background-color: white; color: blue; padding: 5px; margin-right: 2px;" href="' + editurl + '?bb_action=bb_main_edit">Edit</a>');
}

if (BB_GetCookie("bbl") != null)
{
	document.write('<span style="background-color: white; color: red; font-weight: bold;">Javascript can access HttpOnly cookies.  Your browser is insecure.</span><br /><br />');
}
