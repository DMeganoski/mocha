/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    
    // Create custom functions to refresh the list when a task is deleted.
    userID = $('#JsInfo').attr('userID');
    projectID = $('#JsInfo').attr('projectID');
    today = $('#JsInfo').attr('today');
    tomorrow = $('#JsInfo').attr('tomrorrow');
    
    
    jQuery.fn.updateTasks = function(timeStamp) {
        $.post("/task/gettasks", {UserID: userID, ProjectID: projectID, TimeStamp: timeStamp},
            function (data) {
                if (timeStamp == today) {
                    $('ul.TodayTasks').html(data);
                    $('ul.TodayTasks').slideDown();
                $('ul.Tasks').slideDown();
                } else if (timeStamp == tomorrow) {
                    $('ul.TomorrowTasks').html(data);
                    $('ul.TomorrowTasks').slideDown();
                }
            });
    }
    
    $('ul.Tasks').hide();
    $('ul.Tasks').updateTasks(today);
    
    $('a.NewTask').addClass("Popup");
    
    $('input#Form_Submit').click( function() {
        $('ul.Tasks').updateTasks(today);
        $('ul.Tasks').updateTasks(tomorrow);
    });
    
});