{if $productListRows}
<table class="list sortable">
<tr>
  <th>Product</th>
  <th>Description</th>
  <th class='nobr'>Price</th>
  <th>Active</th>
</tr>

{foreach $productListRows as $r}
<tr>
  <td class="nobr" data-sort-value="{print $r["productActive"] ? "1" : "2"}{$r.productName}">{echo product::get_link($r)}&nbsp;</td>
  <td>{Page::htmlentities($r["description"])}&nbsp;</td>
  <td class="nobr">{Page::money($r["sellPriceCurrencyTypeID"],$r["sellPrice"],"%s%mo %c")}&nbsp;</td>
  <td class="nobr">{print $r["productActive"] ? "Yes" : "No"}</td>
</tr>
{/}

</table>
{/}
