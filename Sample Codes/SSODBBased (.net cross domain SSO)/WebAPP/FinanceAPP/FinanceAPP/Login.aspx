<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Login.aspx.cs" Inherits="FirstApp.Login" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <style>
        #login
        {
            width: 250px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <form id="form1" runat="server">
        <div>
            Finance Application
                <div id="login">
                    <asp:Login ID="Login1" runat="server" OnAuthenticate="Login1_Authenticate">
                    </asp:Login>
                </div>

            <a href="Main.aspx">Main.aspx</a>
        </div>
    </form>
</body>
</html>
