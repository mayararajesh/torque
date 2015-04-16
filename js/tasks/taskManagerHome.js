/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    /**************************************************************************/
    /**
     * Get queue list from the torque via ajax. 
     */
    var requestURL = BASE_URL + '/index.php/queue/getQueueList';
    $.post(requestURL,{},function(response){
        if(response.status == 100){
            $('#TaskForm_queue').html('');
            var html = "<option value=\"\">-- Select --</option>";
            $.each(response.response,function(k,v){
                html += "<option value=\""+v+"\">"+v+"</option>";
            });
            $('#TaskForm_queue').html(html);
        }else{
            alert(response.message);
        }
    },'json');
    /**************************************************************************/
});
