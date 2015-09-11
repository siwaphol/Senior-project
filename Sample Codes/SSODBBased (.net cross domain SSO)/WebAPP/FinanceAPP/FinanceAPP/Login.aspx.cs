using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace FirstApp
{
    public partial class Login : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

        }

        protected void Login1_Authenticate(object sender, AuthenticateEventArgs e)
        {
            if (Login1.UserName.ToUpper() == "FinanceUSER")
            {
                e.Authenticated = true;
                FormsAuthentication.SetAuthCookie(Login1.UserName, false);
                Response.Redirect("Main.aspx");
            }
            else
                FormsAuthentication.SignOut();
        }
    }
}