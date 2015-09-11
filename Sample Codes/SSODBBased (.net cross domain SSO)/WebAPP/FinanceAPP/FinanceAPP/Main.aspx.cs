using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using SSOAuthenticator;

namespace FirstApp
{
    public partial class Main : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

        }

        protected void lnkBtnPayrollApp_Click(object sender, EventArgs e)
        {
            var tokenId = Guid.NewGuid();
            var ssoAppToken = new SSOAppsToken
            {
                TokenId = tokenId,
                LoginId = "ABC",
                Valid = true
            };

            SSOAuthenticator.SSOAuthenticator.SetAppToken(ssoAppToken, "FinanceApp");
        }
    }
}