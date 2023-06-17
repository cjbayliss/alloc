{Page::header()}
{Page::toolbar()}

<table class="box">
  <tr>
    <th>Calendar: {$username}</th>
  </tr>
  <tr>
    <td>
      {show_task_calendar_recursive()}
    </td>
  </tr>
</table>



{Page::footer()}
