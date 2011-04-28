{if $taskListRows}

<!-- Header -->
{if $_FORM["showEdit"]}<form action="{$_FORM["url_form_action"]}" method="post">{/}
<table class="list sortable">
  <tr>
  {if $_FORM["showEdit"]}
    <th width="1%" class="sorttable_nosort noprint"> <!-- checkbox toggler -->
      <input type="checkbox" class="toggler">
    </th>
  {/}
    <th width="1%"></th> <!-- taskTypeImage -->
  {if $_FORM["showTaskID"]}<th class="sorttable_numeric" width="1%">ID</th>{/}
    <th>Task</th>
  {if $_FORM["showProject"]}<th>Project</th>{/}
  {if $_FORM["showPriority"]}<th class="sorttable_numeric">Priority</th>{/}
  {if $_FORM["showPriority"]}<th>Task Pri</th>{/}
  {if $_FORM["showPriority"]}<th>Proj Pri</th>{/}
  {if $_FORM["showDateStatus"]}<th>Date Status</th>{/}
  {if $_FORM["showCreator"]}<th>Task Creator</th>{/}
  {if $_FORM["showManager"]}<th>Task Manager</th>{/}
  {if $_FORM["showAssigned"]}<th>Assigned To</th>{/}
  {if $_FORM["showDate1"]}<th>Targ Start</th>{/}
  {if $_FORM["showDate2"]}<th>Targ Compl</th>{/}
  {if $_FORM["showDate3"]}<th>Act Start</th>{/}
  {if $_FORM["showDate4"]}<th>Act Compl</th>{/}
  {if $_FORM["showDate5"]}<th>Task Created</th>{/}
  {if $_FORM["showTimes"]}<th>Best</th>{/}
  {if $_FORM["showTimes"]}<th>Likely</th>{/}
  {if $_FORM["showTimes"]}<th>Worst</th>{/}
  {if $_FORM["showTimes"]}<th>Actual</th>{/}
  {if $_FORM["showTimes"]}<th>Limit</th>{/}
  {if $_FORM["showPercent"]}<th>%</th>{/}
  {if $_FORM["showStatus"]}<th>Status</th>{/}
  </tr>
  
  <!-- Rows -->
  {$n = date("Y-m-d")}
  {foreach $taskListRows as $r}
  <tr class="clickrow" id="clickrow_{$r.taskID}">
  {if $_FORM["showEdit"]}      <td class="nobr noprint"><input type="checkbox" id="checkbox_{$r.taskID}" name="select[{$r.taskID}]" class="task_checkboxes"></td>{/}
                               <td sorttable_customkey="{$r.taskTypeID}">{$r.taskTypeImage}</td>
  {if $_FORM["showTaskID"]}    <td>{$r.taskID}</td>{/}
                               <td style="padding-left:{echo $r["padding"]*25+6}px">{$r.taskLink}&nbsp;&nbsp;{$r.newSubTask}
  {if $_FORM["showDescription"]}<br>{=$r.taskDescription}{/}
  {if $_FORM["showComments"] && $r["comments"]}<br>{$r.comments}{/}
                               </td>
  {if $_FORM["showProject"]}   <td><a href="{$url_alloc_project}projectID={$r.projectID}">{=$r.project_name}</a></td>{/}
  {if $_FORM["showPriority"]}  <td>{$r.priorityFactor}</td>{/}
  {if $_FORM["showPriority"]}  <td style="color:{echo $taskPriorities[$r["priority"]]["colour"]}">{echo $taskPriorities[$r["priority"]]["label"]}</td>{/}
  {if $_FORM["showPriority"]}  <td style="color:{echo $projectPriorities[$r["projectPriority"]]["colour"]}">{echo $projectPriorities[$r["projectPriority"]]["label"]}</td>{/}
  {if $_FORM["showDateStatus"]}<td>{$r.taskDateStatus}</td>{/}
  {if $_FORM["showCreator"]}   <td>{=$r.creator_name}</td>{/}
  {if $_FORM["showManager"]}   <td>{=$r.manager_name}</td>{/}
  {if $_FORM["showAssigned"]}  <td>{=$r.assignee_name}</td>{/}
  {$dts = $r["dateTargetStart"]; $dtc = $r["dateTargetCompletion"]; $das = $r["dateActualStart"]; $dac = $r["dateActualCompletion"];}
  {if $_FORM["showDate1"]}     <td class="nobr">{print $dts==$n ? "<b>".$dts."</b>" : $dts}</td>{/}
  {if $_FORM["showDate2"]}     <td class="nobr">{print $dtc==$n ? "<b>".$dtc."</b>" : $dtc}</td>{/}
  {if $_FORM["showDate3"]}     <td class="nobr">{print $das==$n ? "<b>".$das."</b>" : $das}</td>{/}
  {if $_FORM["showDate4"]}     <td class="nobr">{print $dac==$n ? "<b>".$dac."</b>" : $dac}</td>{/}
  {if $_FORM["showDate5"]}     <td class="nobr">{$r.dateCreated}</td>{/}
  {if $_FORM["showTimes"]}     <td class="nobr">{$r.timeBestLabel}</td>{/}
  {if $_FORM["showTimes"]}     <td class="nobr">{$r.timeExpectedLabel}</td>{/}
  {if $_FORM["showTimes"]}     <td class="nobr">{$r.timeWorstLabel}</td>{/}
  {if $_FORM["showTimes"]}     <td class="nobr">{$r.timeActualLabel}</td>{/}
  {if $_FORM["showTimes"]}     <td class="nobr{$r["timeActual"] > $r["timeLimit"] and print ' bad'}">{$r.timeLimitLabel}</td>{/}
  {if $_FORM["showPercent"]}     <td class="nobr">{$r.percentComplete}</td>{/}
  {if $_FORM["showStatus"]}    <td class="nobr" style="width:1%;">
                                 <span class="corner" style="display:block;width:10em;padding:5px;text-align:center;background-color:{$r.taskStatusColour};">
                                   {$r.taskStatusLabel}
                                 </span>
                               </td>{/}
  </tr>
  {/}

  <!-- Footer -->

  {if $_FORM["showEdit"]}
  {$person_options = page::select_options(person::get_username_list())}
  {$taskType = new meta("taskType")}
  {$taskType_array = $taskType->get_assoc_array("taskTypeID","taskTypeID")}
  <tfoot>
    <tr>
      <th colspan="25" class="nobr noprint" style="padding:2px;">
        <div style="float:left">
          <select name="update_action" onChange="$('.hidden').hide(); $('#'+$(this).val()+'_div').css('display','inline');"> 
            <option value="">Modify Checked...</options>
            <option value="personID">Assign to --&gt;</options>
            <option value="managerID">Manager to --&gt;</options>
            <option value="timeLimit">Limit to --&gt;</options>
            <option value="timeBest">Best to --&gt;</options>
            <option value="timeWorst">Worst to --&gt;</options>
            <option value="timeExpected">Expected to --&gt;</options>
            <option value="priority">Task Priority to --&gt;</options>
            <option value="taskTypeID">Task Type to --&gt;</options>
            <option value="dateTargetStart">Target Start Date to --&gt;</options>
            <option value="dateTargetCompletion">Target Completion Date to --&gt;</options>
            <option value="dateActualStart">Actual Start Date to --&gt;</options>
            <option value="dateActualCompletion">Actual Completion Date to --&gt;</options>
            <option value="projectIDAndParentTaskID">Project and Parent Task to --&gt;</options>
            <option value="taskStatus">Task Status to --&gt;</option>
          </select>
        </div>
        <div class="hidden" id="dateTargetStart_div">{page::calendar("dateTargetStart")}</div>
        <div class="hidden" id="dateTargetCompletion_div">{page::calendar("dateTargetCompletion")}</div>
        <div class="hidden" id="dateActualStart_div">{page::calendar("dateActualStart")}</div>
        <div class="hidden" id="dateActualCompletion_div">{page::calendar("dateActualCompletion")}</div>
        <div class="hidden" id="personID_div"><select name="personID"><option value="">{$person_options}</select></div>
        <div class="hidden" id="managerID_div"><select name="managerID"><option value="">{$person_options}</select></div>
        <div class="hidden" id="timeLimit_div"><input name="timeLimit" type="text" size="5"></div>
        <div class="hidden" id="timeBest_div"><input name="timeBest" type="text" size="5"></div>
        <div class="hidden" id="timeWorst_div"><input name="timeWorst" type="text" size="5"></div>
        <div class="hidden" id="timeExpected_div"><input name="timeExpected" type="text" size="5"></div>
        <div class="hidden" id="priority_div"><select name="priority">{echo task::get_task_priority_dropdown(3)}</select></div>
        <div class="hidden" id="taskTypeID_div"><select name="taskTypeID">{page::select_options($taskType_array)}</select></div>
        <div class="hidden" id="projectIDAndParentTaskID_div">
          <select name="projectID" id="projectID" 
                  onChange="makeAjaxRequest('{$url_alloc_updateParentTasks}projectID='+$(this).val(),'parentTaskDropdown')">
            <option value="">
            {echo task::get_project_options()}
          </select>
          <div style="display:inline" id="parentTaskDropdown"></div>
        </div>
        <div class="hidden" id="taskStatus_div"><select name="taskStatus">{page::select_options(task::get_task_statii_array(true))}</select></div>
        <input type="submit" name="run_mass_update" value="Update Tasks">
      </th>
    </tr>
  </tfoot>
  </form>
  {/}

</table>


{else}
  <b>No Tasks Found</b>
{/}
