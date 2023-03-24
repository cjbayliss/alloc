<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("TICK_SIZE", 7); // Days between tick marks along the axis
define("LARGE_TICK_SIZE", 60);  // Days between tick marks along the axis
define("MAX_TICKS", 20);        // Days between tick marks along the axis
define("ALLOC_FONT", ALLOC_MOD_DIR . "util/fonts/Vera.ttf");
define("ALLOC_FONT_SIZE", "8");

// Set the enviroment variable for GD
putenv('GDFONTPATH=' . realpath('../util'));

function echo_debug($s)
{
    #echo $s;
}

// Outputs an image with an error message given by $s and then terminates the script
function image_die($s = "")
{

    $image = imageCreate(700, 40);

    // allocate all required colors
    $color_background = imageColorAllocate($image, 255, 255, 255);
    $color_text = imageColorAllocate($image, 176, 176, 176);
    $color_grid = imageColorAllocate($image, 224, 224, 224);

    // clear the image space with the background color
    imageFilledRectangle($image, 0, 0, 700, 40, $color_background);
    imagettftext($image, ALLOC_FONT_SIZE, 0, 6, 23, $color_text, ALLOC_FONT, $s);

    imageRectangle($image, 0, 0, 700 - 1, 40 - 1, $color_grid);

    if (ALLOC_GD_IMAGE_TYPE == "PNG") {
        header("Content-type: image/png");
        imagePNG($image);
    } else {
        header("Content-type: image/gif");
        imageGIF($image);
    }
    exit();
}

class task_graph
{
    // 'public' variables

    // size parameters (all in pixels)
    public $width = 700;             // Width of the image
    public $top_margin = 50;         // Distance of first bar from top of image
    public $left_margin = 330;
    public $right_margin = 20;
    public $bottom_margin = 100;
    public $task_padding = 2;        // Whitespace above and below each task bar
    public $bar_height = 10;          // Height of each task bar
    public $indent_increment = 20;   // Increase in whitespace to left of task for child tasks
    public $title;

    // 'private' variables
    public $y;                       // current y position
    public $image;                   // image handle
    public $task_colors;             // color handles for task bars
    public $height;                  // image height - set dynamically according to number of tasks and other variables such as bar_height
    public $color_background;        // Background colour of image
    public $color_text;              // Colour of text on image
    public $color_grid;              // Colour of grid lines behind bars
    public $color_milestone;         // Colour of milestone lines
    public $color_today;             // Colour of current date line
    public $milestones = [];    // Milestones are stored and then drawn over the top of the tasks

    function init($tasks = [])
    {
        global $graph_start_date;
        global $graph_completion_date;
        global $graph_type;

        if (count($tasks) == 0) {
            image_die("No Tasks Found for " . $this->title);
        }

        // Set the enviroment variable for GD
        putenv('GDFONTPATH=' . realpath('../util'));

        $this->height = count($tasks) * ($this->bar_height + $this->task_padding) * 2 + $this->top_margin + $this->bottom_margin;

        get_date_range($tasks);

        // create image
        $this->image = imageCreate($this->width, $this->height);

        // 'Constant' colours for task types
        $this->task_colors = [
            'Task'   => [
                "actual" => imageColorAllocate($this->image, 133, 164, 241),
                "target" => imageColorAllocate($this->image, 190, 219, 255)
            ],
            'Parent' => [
                "actual" => imageColorAllocate($this->image, 153, 153, 153),
                "target" => imageColorAllocate($this->image, 204, 204, 204)
            ]
        ];

        // allocate all required colors
        $this->color_background = imageColorAllocate($this->image, 255, 255, 255);
        $this->color_text = imageColorAllocate($this->image, 0, 0, 0);
        $this->color_grid = imageColorAllocate($this->image, 161, 202, 255);
        $this->color_milestone = imageColorAllocate($this->image, 255, 128, 255);
        $this->color_today = imageColorAllocate($this->image, 255, 192, 0);

        // clear the image space with the background color
        imageFilledRectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $this->color_background);

        imageRectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $this->color_grid);

        imagettftext($this->image, ALLOC_FONT_SIZE + 3, 0, 6, 28, $this->color_text, ALLOC_FONT, $this->title);

        $this->y = $this->top_margin;
    }

    function set_width($width)
    {
        $width and $this->width = $width;
    }

    function set_title($title)
    {
        $this->title = strip_tags(str_replace('\\', '', $title));
    }

    function draw_task($t)
    {
        $date_forecast_completion = null;
        $y = $this->y;              // Store y in local variable for quick access
        $y += $this->task_padding;

        $indent = $t["padding"];
        $task = $t["object"];

        // Text
        $text = $t["taskName"];
        echo_debug("task: $text<br>");
        imagettftext($this->image, ALLOC_FONT_SIZE, 0, 6 + ($indent * $this->indent_increment), $y + 13, $this->color_text, ALLOC_FONT, $text);

        // Get date values
        $date_target_start = $t["dateTargetStart"];
        $date_target_start == "0000-00-00" and $date_target_start = "";

        $date_target_completion = $t["dateTargetCompletion"];
        $date_target_completion == "0000-00-00" and $date_target_completion = "";

        $date_actual_start = $t["dateActualStart"];
        $date_actual_start == "0000-00-00" and $date_actual_start = "";

        $date_actual_completion = $t["dateActualCompletion"];
        $date_actual_completion == "0000-00-00" and $date_actual_completion = "";

        // target bar
        $color = $this->task_colors[$t["taskTypeID"]]["target"];
        $this->draw_dates($date_target_start, $date_target_completion, $y, $color, true);
        $y += $this->bar_height;

        // actual bar
        if ($date_actual_completion == "" && $date_actual_start != "") {
            // Task isn't complete but we can forecast comlpetion using percent complete and start date - show forecast
            $forecast = $task->get_forecast_completion();
            $forecast and $date_forecast_completion = date("Y-m-d", $forecast);
            $color = $this->task_colors[$t["taskTypeID"]]["actual"];
            $forecast and $this->draw_dates($date_actual_start, $date_forecast_completion, $y, $color, false);      // Forecast bar
            $this->draw_dates($date_actual_start, date("Y-m-d"), $y, $color, true);   // Solid bar for already completed portion
        } else {
            // Just show dates as usual
            $color = $this->task_colors[$t["taskTypeID"]]["actual"];
            $this->draw_dates($date_actual_start, $date_actual_completion, $y, $color, true);
        }
        $y += $this->bar_height;

        $y += $this->task_padding;
        // Grid line below current task
        imageLine($this->image, 0, $y, $this->width, $y, $this->color_grid);

        $this->y = $y;              // Store Y back in class variable for another time

        // Register milestones
        if ($t["taskTypeID"] == 'Milestone' && ($date_target_completion || $date_actual_completion)) {
            if ($date_actual_completion) {
                $date_milestone = $date_actual_completion;
            } else {
                $date_milestone = $date_target_completion;
            }
            $this->register_milestone($date_milestone);
        }
    }

    function register_milestone($date)
    {
        $this->milestones[] = $date;
    }

    function draw_milestones()
    {
        reset($this->milestones);
        while (list(, $milestone) = each($this->milestones)) {
            $x = $this->date_to_x($milestone);
            imageDashedLine($this->image, $x, $this->top_margin, $x, $this->height - $this->bottom_margin, $this->color_milestone);
            imageDashedLine($this->image, $x + 1, $this->top_margin, $x + 1, $this->height - $this->bottom_margin, $this->color_milestone);
        }
    }

    function draw_today()
    {
        #$x = $this->date_stamp_to_x(mktime());
        $x = $this->date_to_x(date("Y-m-d"));
        imageDashedLine($this->image, $x, $this->top_margin, $x, $this->height - $this->bottom_margin, $this->color_today);
        imageDashedLine($this->image, $x + 1, $this->top_margin, $x + 1, $this->height - $this->bottom_margin, $this->color_today);
    }

    function draw_dates($date_start, $date_completion, $y, $color, $filled)
    {
        echo_debug("Drawing '$date_start' to '$date_completion'<br>");
        #echo("Drawing '$date_start' to '$date_completion'<br>");
        if ($date_start && $date_completion) {
            // Task is complete - show full bar
            echo_debug("Drawing date range<br>");
            $x_start = $this->date_to_x($date_start);
            $x_completion = $this->date_to_x($date_completion);
            $this->draw_rectangle($x_start, $y, $x_completion, $y + $this->bar_height, $color, $filled);
        } else if ($date_completion) {
            // We can only show the completion date - draw a triangle
            echo_debug("Drawing completion date<br>");
            $x_completion = $this->date_to_x($date_completion);
            echo_debug("Completion date x=$x_completion<br>");
            $this->draw_polygon([$x_completion, $y, $x_completion, $y + $this->bar_height, $x_completion - 4, $y + 4], 3, $color, $filled);
        } else if ($date_start) {
            // We can only show the start date - draw a triangle
            echo_debug("Drawing start date<br>");
            $x_start = $this->date_to_x($date_start);
            $this->draw_polygon([$x_start, $y, $x_start, $y + $this->bar_height, $x_start + 4, $y + 4], 3, $color, $filled);
        }
    }

    function draw_grid()
    {
        global $graph_start_date;
        global $graph_completion_date;

        $start_stamp = format_date("U", $graph_start_date);
        $completion_stamp = format_date("U", $graph_completion_date);
        $graph_time_width = $completion_stamp - $start_stamp;

        // 7 Day increment
        $time_increment = mktime(0, 0, 0, 0, TICK_SIZE) - mktime(0, 0, 0, 0, 0);
        if ($time_increment * (MAX_TICKS + 1) < $graph_time_width) {
            $time_increment = mktime(0, 0, 0, 0, LARGE_TICK_SIZE) - mktime(0, 0, 0, 0, 0);
        }

        $current_stamp = $start_stamp;


        while ($current_stamp < $completion_stamp) {
            $x_pos = $this->date_stamp_to_x($current_stamp);
            imageLine($this->image, $x_pos, $this->top_margin - 5, $x_pos, $this->height - $this->bottom_margin, $this->color_grid);
            imagettftext($this->image, ALLOC_FONT_SIZE - 2, 45, $x_pos, $this->top_margin - 7, $this->color_text, ALLOC_FONT, date("M-d", $current_stamp));
            $current_stamp += $time_increment;
        }

        // Horizontal line above tasks
        imageLine($this->image, 0, $this->top_margin, $this->width, $this->top_margin, $this->color_grid);
    }

    function draw_polygon($points, $num_points, $color, $filled)
    {
        if ($filled) {
            imageFilledPolygon($this->image, $points, $num_points, $color);
        } else {
            imagePolygon($this->image, $points, $num_points, $color);
        }
    }

    function draw_rectangle($x1, $y1, $x2, $y2, $color, $filled)
    {
        if ($filled) {
            imageFilledRectangle($this->image, $x1, $y1, $x2, $y2, $color);
        } else {
            imageRectangle($this->image, $x1, $y1, $x2, $y2, $color);
        }
    }

    function draw_legend_bar($x, $y, $text, $color, $filled)
    {
        $legend_bar_width = 30;
        $x2 = $x + $legend_bar_width;
        $this->draw_rectangle($x, $y, $x2, $y + 8, $color, $filled);
        $x = $x2 + $this->task_padding;
        imagettftext($this->image, ALLOC_FONT_SIZE, 0, $x, $y + 10, $this->color_text, ALLOC_FONT, $text);
    }

    // If $start == true draws a starting triangle, otherwise draws an ending triangle
    function draw_legend_marker($x, $y, $text, $color, $start)
    {
        $legend_bar_width = 30;
        $x2 = $x + $legend_bar_width;
        if ($start) {
            $points = [$x, $y, $x, $y + 8, $x + 4, $y + 4];
        } else {
            $points = [$x + 4, $y, $x + 4, $y + 8, $x, $y + 4];
        }
        $this->draw_polygon($points, 3, $color, true);
        $x = $x2 + $this->task_padding;
        imagettftext($this->image, ALLOC_FONT_SIZE, 0, $x, $y + 10, $this->color_text, ALLOC_FONT, $text);
    }

    function draw_legend()
    {
        $y = $this->y;              // Store y in local variable for quick access
        $left_x = 3;
        $center_x = $this->width / 2;

        $y = $this->height - $this->bottom_margin + 20;

        imagettftext($this->image, ALLOC_FONT_SIZE, 0, $this->task_padding, $y + 10, $this->color_text, ALLOC_FONT, "Legend:");
        $y += 20;

        $this->draw_legend_bar($left_x, $y, "Target task period", $this->task_colors['Task']["target"], true);
        $this->draw_legend_bar($center_x, $y, "Target phase period", $this->task_colors['Parent']["target"], true);
        $y += 12;

        $this->draw_legend_bar($left_x, $y, "Actual task period", $this->task_colors['Task']["actual"], true);
        $this->draw_legend_bar($center_x, $y, "Actual phase period", $this->task_colors['Parent']["actual"], true);
        $y += 12;

        $this->draw_legend_bar($left_x, $y, "Forecast task period", $this->task_colors['Task']["actual"], false);
        $this->draw_legend_bar($center_x, $y, "Forecast phase period", $this->task_colors['Parent']["actual"], false);
        $y += 12;

        $this->draw_legend_marker($left_x, $y, "Start date (completion date not known)", $this->task_colors['Task']["actual"], true);
        $y += 12;

        $this->draw_legend_marker($left_x, $y, "Completion date (start date not known)", $this->task_colors['Parent']["actual"], false);
        $y += 12;

        $this->y = $y;              // Store Y back in class variable for another time
    }

    // Converts from a date string to an X coordinate
    function date_to_x($date)
    {
        echo_debug("Converting $date<br>");
        return $this->date_stamp_to_x(format_date("U", $date));
    }

    // Converts from a unix time stamp to an X coordinate
    function date_stamp_to_x($date)
    {
        $decimal_pos = null;
        global $graph_start_date;
        global $graph_completion_date;

        $graph_time_width = format_date("U", $graph_completion_date) - format_date("U", $graph_start_date);
        $time_offset = $date - format_date("U", $graph_start_date);
        $graph_time_width and $decimal_pos = $time_offset / $graph_time_width;
        $working_width = $this->width - $this->left_margin - $this->right_margin;
        $x_pos = $this->left_margin + $decimal_pos * $working_width;

        return $x_pos;
    }

    // Output the image
    function output()
    {
        if (ALLOC_GD_IMAGE_TYPE == "PNG") {
            header("Content-type: image/png");
            imagePNG($this->image);
        } else {
            header("Content-type: image/gif");
            imageGIF($this->image);
        }
    }
}

function get_date_range($tasks = [])
{
    global $graph_start_date;
    global $graph_completion_date;

    if (count($tasks) == 0) {
        return;
    }

    $graph_start_date = "9999-00-00";
    $graph_completion_date = "0000-00-00";

    reset($tasks);
    while (list(, $task) = each($tasks)) {
        if ($task->get_value("dateTargetStart") != "" && $task->get_value("dateTargetStart") != "0000-00-00" && $task->get_value("dateTargetStart") < $graph_start_date) {
            $graph_start_date = $task->get_value("dateTargetStart");
            #echo "A: $graph_start_date<br>";
        }

        if ($task->get_value("dateTargetCompletion") > $graph_completion_date) {
            $graph_completion_date = $task->get_value("dateTargetCompletion");
        }

        if ($task->get_value("dateActualStart") != "" && $task->get_value("dateActualStart") != "0000-00-00" && $task->get_value("dateActualStart") < $graph_start_date) {
            $graph_start_date = $task->get_value("dateActualStart");
            #echo "B: $graph_start_date<br>";
        }

        if ($task->get_value("dateActualCompletion") > $graph_completion_date) {
            $graph_completion_date = $task->get_value("dateActualCompletion");
        }

        if (date("Y-m-d", $task->get_forecast_completion()) > $graph_completion_date) {
            $graph_completion_date = date("Y-m-d", $task->get_forecast_completion());
        }
    }

    if ($graph_start_date == "9999-00-00") {
        image_die("No Tasks with a Start Date set");
    }

    if ($graph_completion_date == "0000-00-00") {
        image_die("No Tasks with a Completion Date set");
    }
}
