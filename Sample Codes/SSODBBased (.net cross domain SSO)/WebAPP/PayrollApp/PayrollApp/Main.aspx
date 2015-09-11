<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Main.aspx.cs" Inherits="FirstApp.Main" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
    <form id="form1" runat="server">
    <div>
        You are Logged into Payroll App
        <br/>
        <a href="http://localhost:16952/Main.aspx">MainSSO.aspx</a>
        <asp:LinkButton ID="lnkBtnFinanceApp" runat="server" OnClick="lnkBtnFinanceApp_Click" > Login to Finance </asp:LinkButton>
    </div>
    </form>
</body>
</html>
