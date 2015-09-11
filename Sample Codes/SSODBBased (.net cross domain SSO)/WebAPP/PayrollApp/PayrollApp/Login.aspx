<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Login.aspx.cs" Inherits="FirstApp.Login" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <style>
        .input-wrap
{
  position:relative;
  margin-bottom:0px; 
  float:right;
  width:380px;
  margin: 8px 20px 9px 0px;
  text-align:left;
  color:White;  
}
          #login
        {
            width: 250px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <form id="form1" runat="server">
    <div class=".input-wrap"  style="horiz-align: center;vert-align: middle">
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
    </div  >
        <div id="login">
        <asp:Login ID="Login1" runat="server" style="horiz-align:right"  OnAuthenticate="Login1_Authenticate">
        </asp:Login>
            </div>
    </form>
</body>
</html>
