<?php
global $wpdb;
 
 
/* ======================================
   1. THE FIRST YEAR OF BLOG
====================================== */
$first_post_date = $wpdb->get_var("
SELECT post_date
FROM $wpdb->posts
WHERE post_status='publish'
AND post_type='post'
ORDER BY post_date ASC
LIMIT 1
");
 
$first_year = date('Y', strtotime($first_post_date));
$current_year = date('Y');
$year = isset($_GET['yr']) ? intval($_GET['yr']) : $current_year;
 
if ($year < $first_year) $year = $first_year;
if ($year > $current_year) $year = $current_year;
 
 
/* ======================================
   2. POST DATES (OPTIMISED SQL QUERY + CACHE)
====================================== */
$cache_key = 'calendar_'.$year;
$post_dates = get_transient($cache_key);
if ($post_dates === false){
    $post_dates = [];
    $results = $wpdb->get_results($wpdb->prepare("
    SELECT DATE(post_date) as post_day, COUNT(ID) as total
    FROM $wpdb->posts
    WHERE post_status='publish'
    AND post_type='post'
    AND YEAR(post_date)=%d
    GROUP BY post_day
    ", $year));
    foreach ($results as $row){
        $post_dates[$row->post_day] = $row->total;
    }
    set_transient($cache_key,$post_dates,12*HOUR_IN_SECONDS);
    }
 
 
/* ======================================
   3. BROWSE BY YEAR
====================================== */
echo '<div class="calendar-nav">';
for ($y=$first_year; $y<=$current_year; $y++){
    if ($y==$year){
        echo '<span class="current-year">'.$y.'</span>';
    }else{
        echo '<a href="?yr='.$y.'">'.$y.'</a> ';
    }
}
echo '</div>';
 
 
/* ======================================
   4. ‘TODAY IN THE PAST YEARS’ COMPARISON
====================================== */
$today_month = date('m');
$today_day = date('d');
$today_key = $year.'-'.$today_month.'-'.$today_day;
$today_has_posts = isset($post_dates[$today_key]);
 
 
/* ======================================
   5. YEAR CONTAINER
====================================== */
echo '<div class="calendar-year">';
 
 
/* ======================================
   6. MONTHLY CYCLE
====================================== */
for ($month=1; $month<=12; $month++) {
    echo '<div class="calendar-month-block">';
    $month_name = date_i18n('F', mktime(0,0,0,$month,1,$year));
    $month_has_posts = false;
    foreach ($post_dates as $date => $count){
        if (strpos($date,$year.'-'.sprintf('%02d',$month))===0){
            $month_has_posts = true;
            break;
        }
    }
 
/* month title */
if ($month_has_posts){
    echo '<h3 class="month-title">
    <a href="'.get_month_link($year,$month).'">'.$month_name.'</a>
    </h3>';
    }else{
    echo '<h2 class="month-title">'.$month_name.'</h2>';
    }
 
 
/* ======================================
   7. CALENDAR TABLE
====================================== */
echo '<table class="calendar-month">';
echo '<thead><tr>';
$weekdays = ['M','T','W','T','F','S','S']; //to change manually to your language
foreach ($weekdays as $w){
    echo '<th>'.$w.'</th>';
    }
echo '</tr></thead>';
echo '<tbody><tr>';
 
/* first day of the month */
$first_day = date('N', strtotime("$year-$month-01"));
for ($i=1;$i<$first_day;$i++){
    echo '<td class="empty"></td>';
    }
$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
$weekday = $first_day;
 
 
/* ======================================
   8. CYCLE DAYS
====================================== */
for ($day=1;$day<=$days;$day++){
    $date = $year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$day);
    $is_today_past = false;
    /* ‘today in the past years’ comparison */
    if ($year < $current_year && $month==$today_month && $day==$today_day){
        $is_today_past = true;
    }
    $classes = [];
    if ($is_today_past) $classes[] = 'today-past';
    if (isset($post_dates[$date])){
        $classes[] = 'has-posts';
    } else {
        $classes[] = 'no-posts';
    }
    echo '<td class="'.implode(' ', $classes).'">';
    if (isset($post_dates[$date])){
        $count = $post_dates[$date];
        $title = ($count==1) ? '1 post' : $count.' posts';
        echo '<a class="day-link" href="'.get_day_link($year,$month,$day).'" title="'.$title.'">'.$day.'</a>';
    }else{
        echo '<span class="day-number">'.$day.'</span>';
    }
    echo '</td>';
    if ($weekday==7){
        echo '</tr><tr>';
        $weekday=1;
    }else{
        $weekday++;
    }
    }
 
/* final table cells */
while ($weekday<=7){
    echo '<td class="empty"></td>';
    $weekday++;
    }
echo '</tr></tbody></table>';
echo '</div>';
}
 
echo '</div>';
?>