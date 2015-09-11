using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using SSOAuthenticator;

namespace SecondAPP
{
    public partial class LogonSSO : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            var tokenId = Request.QueryString["tokenId"];
            var guidToken = new Guid();
            if (string.IsNullOrEmpty(tokenId) || !Guid.TryParse(tokenId, out guidToken)) Response.Redirect("ssoError.aspx");

            var ssoAppsToken = SSOAuthenticator.SSOAuthenticator.GetAppToken(guidToken);
            if (ssoAppsToken != null)
            {
                //Use SSO APP Token details
                FormsAuthentication.SetAuthCookie(ssoAppsToken.LoginId, false);
                Response.Redirect("Main.aspx");
            }
        }
    }
}