using System;
using System.Web.Security;
using SSOAuthenticator;

namespace FirstApp
{
    public partial class Main : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

        }

        protected void lnkBtnFinanceApp_Click(object sender, EventArgs e)
        {
            var tokenId = Guid.NewGuid();
            //var membershipUser = Membership.GetUser();
            var userName = "default";
            //if (membershipUser != null)
            //{
            //    userName = membershipUser.UserName;
            //}
            var ssoAppToken = new SSOAppsToken
                {
                    TokenId = tokenId,
                    LoginId = userName,
                    Valid = true
                };

            SSOAuthenticator.SSOAuthenticator.SetAppToken(ssoAppToken, "PayrollApp");

            Response.Redirect(string.Format("http://localhost:16952/logonsso.aspx?tokenId={0}", tokenId));
        }
    }
}