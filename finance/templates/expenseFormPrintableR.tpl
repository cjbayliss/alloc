<tr>
  <td colspan="3">&nbsp;{$companyDetails}</td>
  <td colspan="3">&nbsp;{$projectID}</td>
</tr>
<tr>
  <td>{$transactionID}</td> 
  <td>{$transactionDate}</td> 
  <td>Item: {$product}</td> 
  <td>Source TF: {$fromTfIDLink} Dest Tf: {$tfIDLink}</td> 
  <td>{$quantity}pcs. @ {Page::money($currencyTypeID,$amount,"%s%mo")} each</td>
  <td>{Page::money($currencyTypeID,$lineTotal,"%s%mo")}</td>
</tr>
<tr>
  <td colspan="6">&nbsp;</td>
</tr>
