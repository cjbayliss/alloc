<tr>
  <td><a href="{$url_alloc_transactionList}tfID={$tfID}">{=$tfName}</a></td>
  <td style="text-align:right" class="transaction-pending obfuscate">i {Page::money(config::get_config_item("currency"),$pending_amount,"%s%m %c")}</td>
  <td style="text-align:right" class="transaction-approved obfuscate">{Page::money(config::get_config_item("currency"),$tfBalance,"%s%m %c")}</td>
</tr>
