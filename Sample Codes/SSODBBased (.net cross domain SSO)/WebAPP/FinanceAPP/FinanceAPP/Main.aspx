<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Main.aspx.cs" Inherits="FirstApp.Main" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
    <form id="form1" runat="server">
    <div>
       You are logged in to Finance
        <asp:LinkButton ID="lnkBtnPayrollApp" runat="server" OnClick="lnkBtnPayrollApp_Click"> Login to Payroll </asp:LinkButton>
    </div>
    </form>
</body>
</html>
