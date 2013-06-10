$(document).ready(function() {
    
    // Create custom functions to refresh the list when a task is deleted.
    userID = $('#JsInfo').attr('userID');
    projectID = $('#JsInfo').attr('projectID');
    today = $('#JsInfo').attr('today');
    tomorrow = $('#JsInfo').attr('tomrorrow');
    
    
    jQuery.fn.updateTasks = function(timeStamp) {
        $.post("index.php?p=/task/gettasks", {UserID: userID, ProjectID: projectID, TimeStamp: timeStamp},
            function (data) {
                $('ul.TaskList').html(data);
            }
        );
    };
    console.log('Script Loaded');
    $('ul.TaskList').updateTasks(today);
    
});


