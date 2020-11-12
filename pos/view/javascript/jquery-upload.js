$(document).ready(function()
{

var settings = {
	url: $('#upload_link').val(),
	method: "POST",
	allowedTypes:"jpg,png,gif,doc,pdf,zip",
	fileName: "myfile",
	multiple: true,
	onSuccess:function(files,data,xhr)
	{
		
		$("#status").html("<font color='green'>"+$('#txt_go_back').val()+"</font>");
		//console.log(data);
		
	},
    afterUploadAll:function()
    {
      //  alert("all images uploaded!!");
    },
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload is Failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(settings);

});