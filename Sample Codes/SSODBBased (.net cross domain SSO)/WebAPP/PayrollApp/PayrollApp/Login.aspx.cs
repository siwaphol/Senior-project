using System;
using System.Web.Security;
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

            if (Login1.UserName.ToUpper() == "PAYROLLUSER")
            {
                e.Authenticated = true;
                FormsAuthentication.SetAuthCookie(Login1.UserName, Login1.RememberMeSet);
                Response.Redirect("Main.aspx");
            }
            else
                FormsAuthentication.SignOut();
        }
    }
}