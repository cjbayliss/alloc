{page::header()}
  {page::toolbar()}

<form name="costForm" action="{$url_alloc_expenseForm}" method="post">

<strong style="color: red; text-align:center; ">{$error}</strong>

{$table_box}
  <tr> 
    <th>Expense Form</th>
    <th class="right" colspan="3">
      <a href="{$url_alloc_expenseForm}expenseFormID={$expenseFormID}&printVersion=true" TARGET="_blank">Printer Friendly Version</a>&nbsp;
      <a href="{$url_alloc_expenseFormList}">Expense Form List</a>
    </th>
  </tr>
  <tr>
    <td align="right" class="nobr" width="10%">Expense Form ID:</td>
    <td width="30%">{$expenseFormID}</td>
    <td align="right" width="10%">Client:</td>
    <td>{$field_clientID}</td>
  </tr>
  <tr>
    <td align="right">Created By:</td>
    <td>{$user}</td>
    <td align="right" class="nobr">Seek Client Reimbursement:</td>
    <td>{$seekClientReimbursementOption}</td>
  </tr>
  <tr>
    <td align="right" valign="top">Total:</td>
    <td valign="top">${$formTotal}</td>
    <td align="right" valign="top">Payment Status:</td>
    <td colspan="2">{$reimbursementRequiredOption}</td>
  </tr>
  <tr>
    <td align="right" valign="top">{$invoice_label}</td>
    <td valign="top" class="nobr">{$attach_to_invoice_button}{$invoice_link}</td>
  </tr>
  <tr>
    <td align="right" valign="top">Comment</td>
    <td colspan="3" valign="top">{page::textarea("expenseFormComment",$expenseFormComment)}</td>
  </tr>
  <tr>
    <td colspan="4" align="center">
      {$expenseFormButtons}
    </td>
  </tr>
      
</table>


{if check_optional_show_line_item_add()}

{$table_box}
  <tr>
    <th colspan="6">Create Expense Form Items</th>
  </tr>

  <tr>
    <td colspan="3">Enter the company name and address{page::mandatory($companyDetails)}</td> 
    <td colspan="3">Project</td> 
  </tr>
  <tr>
    <td colspan="3"><input type="text" size="50" name="companyDetails" value="{$companyDetails}"></td>
    <td colspan="3"><select name="projectID" value="{$projectID}"><option value="">{$projectOptions}</select></td>
  </tr>
  <tr>
    <td>Product{page::mandatory($product)}</td>
    <td>Quantity</td>
    <td>Price{page::mandatory($amount)}</td>
    <td>Source TF{page::mandatory($fromTfID)}</td>
    <td>Date Incurred{page::mandatory($transactionDate)}</td>
  </tr>
  <tr>
    <td><input type="text" size="25" name="product" value="{$product}"></td>
    <td><input type="text" size="5" name="quantity" value="{$quantity}"></td>
    <td><input type="text" size="9" name="amount" value="{$amount}"></td>
    <td><select name="fromTfID"><option value="">{$fromTfOptions}</select></td>
    <td><nobr>{page::calendar("transactionDate",$TPL["transactionDate"])}</nobr>
    <td class="right"><input type="submit" name="add" value="Add Expense Form Item"><input type="hidden" name="transactionID" value="{$transactionID}"></td>
  </tr>
</table>
{/}

<input type="hidden" name="status" value="pending">
<input type="hidden" name="expenseFormID" value="{$expenseFormID}">
</form>

{if check_optional_has_line_items()}
{$table_box}
  <tr>
    <th>Expense Form Line Items</th>
  </tr>
  <tr>
    <td>{show_all_exp("templates/expenseFormR.tpl")}</td>
  </tr>
</table>
{/}


{page::footer()}

