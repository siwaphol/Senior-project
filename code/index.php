<?php

# include this file at the very top of your script
require_once('preheader.php');

# the code for the class
include ('ajaxCRUD.class.php');

# this one line of code is how you implement the class
$tblCustomer = new ajaxCRUD("User",
                             "Users", "username");

# don't show the primary key in the table
// $tblCustomer->omitPrimaryKey();

# my db fields all have prefixes;
# display headers as reasonable titles
$tblCustomer->displayAs("firstnameTH", "F_th");
$tblCustomer->displayAs("firstnameEN", "F_en");
$tblCustomer->displayAs("lastnameTH", "L_th");
$tblCustomer->displayAs("lastnameEN", "L_en");
$tblCustomer->displayAs("studentid", "ID");

$tblCustomer->primaryKeyNotAutoIncrement();
$tblCustomer->onAddSpecifyPrimaryKey();
# define allowable fields for my dropdown fields
# (this can also be done for a pk/fk relationship)
// $values = array("Cash", "Credit Card", "Paypal");
// $tblCustomer->defineAllowableValues("fldPaysBy", $values);

# add the filter box (above the table)
$tblCustomer->addAjaxFilterBox("studentid");

# add validation to certain fields (via jquery in validation.js)
// $tblCustomer->modifyFieldWithClass("fldPhone", "phone");
// $tblCustomer->modifyFieldWithClass("fldZip", "zip");

# actually show to the table
$tblCustomer->showTable();
?>