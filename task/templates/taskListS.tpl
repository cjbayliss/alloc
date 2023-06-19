{if $taskListRows}

<!-- Header -->
{if isset($_FORM["showEdit"])}<form action="{$url_alloc_taskList}" method="post">{/}
<table class="list sortable">
  <tr>
  {if isset($_FORM["showEdit"])}
    <th width="1%" data-sort="none" class="noprint"> <!-- checkbox toggler -->
      <input type="checkbox" class="toggler">
    </th>
  {/}
    <th width="1%" data-sort="num">&nbsp;</th> <!-- taskTypeImage -->
  {if isset($_FORM["showTaskID"])}<th data-sort="num" width="1%">ID</th>{/}
  {if isset($_FORM["showParentID"])}<th data-sort="num" width="1%">PID</th>{/}
    <th>Task</th>
  {if isset($_FORM["showProject"])}<th>Project</th>{/}
  {if isset($_FORM["showPriority"]) || isset($_FORM["showPriorityFactor"])}<th data-sort="num">Priority</th>{/}
  {if isset($_FORM["showPriority"])}<th data-sort="num">Task Pri</th>{/}
  {if isset($_FORM["showPriority"])}<th data-sort="num">Proj Pri</th>{/}
  {if isset($_FORM["showCreator"])}<th>Task Creator</th>{/}
  {if isset($_FORM["showManager"])}<th>Task Manager</th>{/}
  {if isset($_FORM["showAssigned"])}<th>Assigned To</th>{/}
  {if isset($_FORM["showDate1"])}<th>Targ Start</th>{/}
  {if isset($_FORM["showDate2"])}<th>Targ Compl</th>{/}
  {if isset($_FORM["showDate3"])}<th>Act Start</th>{/}
  {if isset($_FORM["showDate4"])}<th>Act Compl</th>{/}
  {if isset($_FORM["showDate5"])}<th>Task Created</th>{/}
  {if isset($_FORM["showTimes"])}<th>Best</th>{/}
  {if isset($_FORM["showTimes"])}<th>Likely</th>{/}
  {if isset($_FORM["showTimes"])}<th>Worst</th>{/}
  {if isset($_FORM["showTimes"])}<th>Actual</th>{/}
  {if isset($_FORM["showTimes"])}<th>Limit</th>{/}
  {if isset($_FORM["showTags"])}<th>Tags</th>{/}
  {if isset($_FORM["showPercent"])}<th data-sort="int">%</th>{/}
  {if isset($_FORM["showStatus"])}<th>Status</th>{/}
  {if isset($_FORM["showEdit"]) || isset($_FORM["showStarred"])}<th data-sort="num" width="1%" style="font-size:120%"><i class="icon-star"></i></th>{/}
  </tr>
  
  <!-- Rows -->
  {$n = date("Y-m-d")}
  {$gt_best = 0}
  {$gt_expected = 0}
  {$gt_worst = 0}
  {$gt_actual = 0}
  {$gt_limit = 0}
  {foreach $taskListRows as $r}
  <tr class="clickrow" id="clickrow_{$r.taskID}">
  {if isset($_FORM["showEdit"])}      <td class="nobr noprint"><input type="checkbox" id="checkbox_{$r.taskID}" name="select[{$r.taskID}]" class="task_checkboxes"></td>{/}
                               <td data-sort-value="{$r.taskTypeSeq}">{$r.taskTypeImage}</td>
  {if isset($_FORM["showTaskID"])}    <td>{$r.taskID}</td>{/}
  {if isset($_FORM["showParentID"])}  <td>{$r.parentTaskID_link}</td>{/}
                               <td style="padding-left:{echo $r["padding"]*25+6}px">{$r.taskLink}&nbsp;&nbsp;{$r.newSubTask}
  {if isset($_FORM["showDescription"])}<br>{=$r.taskDescription}{/}
  {if isset($_FORM["showComments"]) && $r["comments"]}<br>{$r.comments}{/}
                               </td>
  {if isset($_FORM["showProject"])}   <td><a href="{$url_alloc_project}projectID={$r.projectID}">{=$r.project_name}</a></td>{/}
  {if isset($_FORM["showPriority"]) || isset($_FORM["showPriorityFactor"])}  <td>{$r.priorityFactor}</td>{/}
  {if isset($_FORM["showPriority"])}  <td data-sort-value='{$r.priority}' style="color:{echo $taskPriorities[$r["priority"]]["colour"]}">{echo $taskPriorities[$r["priority"]]["label"]}</td>{/}
  {if isset($_FORM["showPriority"])}  <td data-sort-value='{$r.projectPriority}' style="color:{echo $projectPriorities[$r["projectPriority"]]["colour"]}">{echo $projectPriorities[$r["projectPriority"]]["label"]}</td>{/}
  {if isset($_FORM["showCreator"])}   <td>{=$r.creator_name}</td>{/}
  {if isset($_FORM["showManager"])}   <td>{=$r.manager_name}</td>{/}
  {if isset($_FORM["showAssigned"])}  <td>{=$r.assignee_name}</td>{/}
  {$dts = $r["dateTargetStart"]; $dtc = $r["dateTargetCompletion"]; $das = $r["dateActualStart"]; $dac = $r["dateActualCompletion"];}
  {unset($dts_style)}
  {$dts == $n   and $dts_style = 'color:green'}
  {$dts && $das > $dts and $dts_style = 'color:red'}
  {unset($dtc_style)}
  {$dtc == $n   and $dtc_style = 'color:green'}
  {$dtc && $dac > $dtc and $dtc_style = 'color:red'}
  {if isset($_FORM["showDate1"])}     <td class="nobr" style="{$dts_style}">{$dts}</td>{/}
  {if isset($_FORM["showDate2"])}     <td class="nobr" style="{$dtc_style}">{$dtc}</td>{/}
  {if isset($_FORM["showDate3"])}     <td class="nobr">{$das}</td>{/}
  {if isset($_FORM["showDate4"])}     <td class="nobr">{$dac}</td>{/}
  {if isset($_FORM["showDate5"])}     <td class="nobr">{$r.dateCreated}</td>{/}
  {if isset($_FORM["showTimes"])}     <td class="nobr">{$r.timeBestLabel}</td>{/}
  {if isset($_FORM["showTimes"])}     <td class="nobr">{$r.timeExpectedLabel}</td>{/}
  {if isset($_FORM["showTimes"])}     <td class="nobr">{$r.timeWorstLabel}</td>{/}
  {if isset($_FORM["showTimes"])}     <td class="nobr">{$r.timeActualLabel}</td>{/}
  {if isset($_FORM["showTimes"])}     <td class="nobr{$r["timeActual"] > $r["timeLimit"] and print ' bad'}">{$r.timeLimitLabel}</td>{/}
  {if isset($_FORM["showTags"])}      <td class="nobr">{$r.tags}</td>{/}
  {if isset($_FORM["showPercent"])}   <td class="nobr">{$r.percentComplete}</td>{/}
  {if isset($_FORM["showStatus"])}    <td class="nobr" style="width:1%;">
                                 <span class="corner" style="display:block;width:10em;padding:5px;text-align:center;background-color:{$r.taskStatusColour};">
                                   {$r.taskStatusLabel}
                                 </span>
                               </td>{/}
  {if isset($_FORM["showEdit"]) || isset($_FORM["showStarred"])}
    <td width="1%" data-sort-value="{Page::star_sorter("task",$r["taskID"])}">
      {Page::star("task",$r["taskID"])}
    </td>
  {/}

  {$gt_best     += $r["timeBest"]*60*60}
  {$gt_expected += $r["timeExpected"]*60*60}
  {$gt_worst    += $r["timeWorst"]*60*60}
  {$gt_actual   += ($r["timeActual"] ?? 0)*60*60}
  {$gt_limit    += $r["timeLimit"]*60*60}

  </tr>
  {/}
  {$gt_actual > $gt_limit and $gt_status=' bad'}
  {$gt_best     and $gt_best     = seconds_to_display_format($gt_best)}
  {$gt_expected and $gt_expected = seconds_to_display_format($gt_expected)}
  {$gt_worst    and $gt_worst    = seconds_to_display_format($gt_worst)}
  {$gt_actual   and $gt_actual   = seconds_to_display_format($gt_actual)}
  {$gt_limit    and $gt_limit    = seconds_to_display_format($gt_limit)}

  <!-- Footer -->
  {if isset($_FORM["showTotals"]) || isset($_FORM["showEdit"])}
  <tfoot>
  {/}

  {if isset($_FORM["showTotals"]) && $_FORM["showTimes"]}
    <tr>
  {if isset($_FORM["showEdit"])}<td></td>{/}
    <td></td> <!-- taskTypeImage -->
  {if isset($_FORM["showTaskID"])}<td></td>{/}
  {if isset($_FORM["showParentID"])}<td></td>{/}
    <td></td> <!-- task name -->
  {if isset($_FORM["showProject"])}<td></td>{/}
  {if isset($_FORM["showPriority"]) || isset($_FORM["showPriorityFactor"])}<td></td>{/}
  {if isset($_FORM["showPriority"])}<td></td>{/}
  {if isset($_FORM["showPriority"])}<td></td>{/}
  {if isset($_FORM["showCreator"])}<td></td>{/}
  {if isset($_FORM["showManager"])}<td></td>{/}
  {if isset($_FORM["showAssigned"])}<td></td>{/}
  {if isset($_FORM["showDate1"])}<td></td>{/}
  {if isset($_FORM["showDate2"])}<td></td>{/}
  {if isset($_FORM["showDate3"])}<td></td>{/}
  {if isset($_FORM["showDate4"])}<td></td>{/}
  {if isset($_FORM["showDate5"])}<td></td>{/}
  {if isset($_FORM["showTimes"])}<td class="grand_total">{$gt_best}</td>{/}
  {if isset($_FORM["showTimes"])}<td class="grand_total">{$gt_expected}</td>{/}
  {if isset($_FORM["showTimes"])}<td class="grand_total">{$gt_worst}</td>{/}
  {if isset($_FORM["showTimes"])}<td class="grand_total">{$gt_actual}</td>{/}
  {if isset($_FORM["showTimes"])}<td class="grand_total{$gt_status}">{$gt_limit}</td>{/}
  {if isset($_FORM["showTags"])}<td></td>{/}
  {if isset($_FORM["showPercent"])}<td></td>{/}
  {if isset($_FORM["showStatus"])}<td></td>{/}
  {if isset($_FORM["showEdit"]) || isset($_FORM["showStarred"])}<td></td>{/}
    </tr>
  {/}

  {if isset($_FORM["showEdit"])}
  {$person_options = Page::select_options(person::get_username_list())}
  {$taskType = new meta("taskType")}
  {$taskType_array = $taskType->get_assoc_array("taskTypeID","taskTypeID")}
    <tr id="task_editor">
      <th colspan="26" class="nobr noprint" style="padding:2px;" data-sort="none">
        <span style="margin-right:5px;">
          <select name="update_action" onChange="$('#task_editor .hidden').hide();$('#'+$(this).val()+'_span').show();$('#mass_update').show();"> 
            <option value="">Modify Checked...
            <option value="personID">Assign to --&gt;
            <option value="managerID">Manager to --&gt;
            <option value="timeLimit">Limit to --&gt;
            <option value="timeBest">Best to --&gt;
            <option value="timeWorst">Worst to --&gt;
            <option value="timeExpected">Expected to --&gt;
            <option value="priority">Task Priority to --&gt;
            <option value="taskTypeID">Task Type to --&gt;
            <option value="dateTargetStart">Target Start Date to --&gt;
            <option value="dateTargetCompletion">Target Completion Date to --&gt;
            <option value="dateActualStart">Actual Start Date to --&gt;
            <option value="dateActualCompletion">Actual Completion Date to --&gt;
            <option value="projectIDAndParentTaskID">Project and Parent Task to --&gt;
            <option value="taskStatus">Task Status to --&gt;
          </select>
        </span>
        <span class="hidden" id="dateTargetStart_span">{Page::calendar("dateTargetStart")}</span>
        <span class="hidden" id="dateTargetCompletion_span">{Page::calendar("dateTargetCompletion")}</span>
        <span class="hidden" id="dateActualStart_span">{Page::calendar("dateActualStart")}</span>
        <span class="hidden" id="dateActualCompletion_span">{Page::calendar("dateActualCompletion")}</span>
        <span class="hidden" id="personID_span"><select name="personID"><option value="">{$person_options}</select></span>
        <span class="hidden" id="managerID_span"><select name="managerID"><option value="">{$person_options}</select></span>
        <span class="hidden" id="timeLimit_span"><input name="timeLimit" type="text" size="5"></span>
        <span class="hidden" id="timeBest_span"><input name="timeBest" type="text" size="5"></span>
        <span class="hidden" id="timeWorst_span"><input name="timeWorst" type="text" size="5"></span>
        <span class="hidden" id="timeExpected_span"><input name="timeExpected" type="text" size="5"></span>
        <span class="hidden" id="priority_span"><select name="priority">{echo Task::get_task_priority_dropdown(3)}</select></span>
        <span class="hidden" id="taskTypeID_span"><select name="taskTypeID">{Page::select_options($taskType_array)}</select></span>
        <span class="hidden" id="projectIDAndParentTaskID_span">
          <select name="projectID" id="projectID" 
                  onChange="makeAjaxRequest('{$url_alloc_updateParentTasks}projectID='+$(this).val(),'parentTaskDropdown')">
            <option value="">
            {echo Task::get_project_options()}
          </select>
          <span style="display:inline" id="parentTaskDropdown"></span>
        </span>
        <span class="hidden" id="taskStatus_span"><select name="taskStatus">{Page::select_options(Task::get_task_statii_array(true))}</select></span>
        <button type="submit" id="mass_update" name="mass_update" value="1" class="hidden save_button" style="margin-left:5px;text-transform:none !important;">Update Tasks<i class="icon-ok-sign"></i></button>
      </th>
    </tr>
  <input type="hidden" name="sessID" value="{$sessID}">
  <input type="hidden" name="returnURL" value="{echo $taskListOptions["returnURL"]}">
  </form>
  {/}

  {if isset($_FORM["showTotals"]) || isset($_FORM["showEdit"])}
  </tfoot>
  {/}
</table>


{else}
  <b>No Tasks Found</b>
{/}

