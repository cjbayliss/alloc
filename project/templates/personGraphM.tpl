{Page::header()}
{Page::toolbar()}
<table class="box">
  <tr>
    <th>Person Graphs</th>
    <th class="right">{$navigation_links}</th>
  </tr>
  <tr>  
    <td colspan="2" align="center">
        {show_people("templates/personGraphR.tpl")}
    </td>
  </tr>
</table>
{Page::footer()}
