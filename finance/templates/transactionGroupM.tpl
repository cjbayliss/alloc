{page::header()}
{page::toolbar()}

<script type="text/javascript">

$(document).ready(function() {
  // For marking all boxes
  $('.allPending').bind("click", function(event) {
    $(this).parent().parent().parent().find('.txStatus').val("pending");
    return false;
  });
  $('.allApproved').bind("click", function(event) {
    $(this).parent().parent().parent().find('.txStatus').val("approved");
    return false;
  });
  $('.allRejected').bind("click", function(event) {
    $(this).parent().parent().parent().find('.txStatus').val("rejected");
    return false;
  });
});

</script>

<form action="{$url_alloc_transactionGroup}" method="post">
<input type="hidden" name="transactionGroupID" value="{$transactionGroupID}">
<table class="box">
  <tr>
    <th>Transaction Group {$transactionGroupID}</th>
  </tr>
  <tr>
    <td>

      <table class="list" style="margin:3px 0px 3px 0px;">
        <tr>
          <th>ID</th>
          <th>Amount</th>
          <th>Source TF</th>
          <th>Destination TF</th>
          <th>Description</th>
          <th>Type</th>
          <th>Status
            <a href="##" class="magic allPending">P</a>&nbsp;
            <a href="##" class="magic allApproved">A</a>&nbsp;
            <a href="##" class="magic allRejected">R</a>&nbsp;
          </th>
          <th class="right">
            <a href="#x" class="magic" onClick="$('#transactions_footer').before('<tr>'+$('#transactionRow').html()+'</tr>');">New</a>
          </th>
        </tr>
        {show_transaction_list("templates/transactionGroupR.tpl")}
        {show_transaction_new("templates/transactionGroupR.tpl")}
        <tr id="transactions_footer">
          <th colspan="8" class="center">
            <input type="submit" name="save_transactions" value="Save Transactions">
          </th>
        </tr>
      </table>

    </td>
  </tr>
</table>
