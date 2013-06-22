$(document).ready(function() {
    
    // Create custom functions to refresh the list when a task is deleted.
    userID = $('#JsInfo').attr('userID');
    projectID = $('#JsInfo').attr('projectID');
    today = $('#JsInfo').attr('today');
    tomorrow = $('#JsInfo').attr('tomrorrow');
    
    
    jQuery.fn.updateTasks = function(timestamp) {
        $.post("index.php?p=/task/gettasks", {UserID: userID, ProjectID: projectID, Timestamp: timestamp},
            function (data) {
                $('div.TaskList').html(data);
            }
        );
    };
    jQuery.fn.newTask = function(projectID) {
        $.get('index.php?p=/task/create/'+projectID, function(data) {
            $('#TaskFormBox').html(data);
            $('#TaskFormBox').slideDown(300);
        });
    };
    
    $("h1.page-header").append("<div class='pull-right'><button class='Button NewTask' onclick=\"$('#TaskFormBox').newTask(projectID);\"><span class='Sprite SpEditProfile'></span>New Task</button></div>");
    
    
    console.log('Script Loaded');
    $('ul.TaskList').updateTasks(today);
    //$.datepicker.formatDate( "yy-mm-dd", new Date( 2007, 1 - 1, 26 ) );
    //$( "#datepicker" ).datepicker();
    $( "#datepicker" ).click(function() {
                $.datepicker().show();
    });
});


