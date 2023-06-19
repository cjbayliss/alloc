{$phoneNo1 && $phoneNo2 and $phoneNo1.= " / "}
<tr>
{if isset($_FORM["showName"])}    <td>{$name_link}</td>{/}
{if isset($_FORM["showActive"])}  <td>{$personActive_label}</td>{/}
{if isset($_FORM["showNos"])}     <td>{=$phoneNo1}{=$phoneNo2}</td>{/}
{if isset($_FORM["showSkills"])}  <td>{$skills_list}</td>{/}
{if isset($_FORM["showHours"])}   <td>{echo sprintf("%0.1f",$hoursSum)}</td>{/}
{if isset($_FORM["showHours"])}   <td>{echo sprintf("%0.1f",$hoursAvg)}</td>{/}
{if isset($_FORM["showLinks"])}   <td class="nobr noprint" align="right" width="1%">{$navLinks}</td>{/}
</tr>

